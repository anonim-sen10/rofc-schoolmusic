<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RescheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RescheduleManagementController extends Controller
{
    public function approve(Request $request, int $id): RedirectResponse
    {
        try {
            $newSessionData = [];

            DB::transaction(function () use ($id, $request, &$newSessionData): void {
                $resRequest = RescheduleRequest::with(['student.user', 'oldSession', 'newSchedule.teacher.user'])->lockForUpdate()->findOrFail($id);

                if ($resRequest->status !== 'pending') {
                    throw new \Exception('Request ini sudah diproses.');
                }

                $oldSession = \App\Models\ScheduleSession::lockForUpdate()->find($resRequest->old_session_id);
                $newScheduleTemplate = Schedule::lockForUpdate()->findOrFail($resRequest->new_schedule_id);

                $newSessionDate = null;

                if ($oldSession) {
                    $oldSession->update([
                        'status' => 'rescheduled',
                    ]);

                    $isPushBack = ($resRequest->new_schedule_id == $oldSession->schedule_id);

                    if ($isPushBack) {
                        // Logika Dorong Mundur (Push Back)
                        $maxDate = \App\Models\ScheduleSession::where('student_id', $resRequest->student_id)
                            ->where('class_id', $oldSession->class_id)
                            ->max('session_date');
                        
                        // Buat sesi baru 1 minggu setelah sesi paling ujung
                        $newSessionDate = \Carbon\Carbon::parse($maxDate)->addWeek();
                    } else {
                        // Logika Ganti Hari di minggu yang sama (Original logic)
                        $dayMap = [
                            'Senin' => \Carbon\Carbon::MONDAY,
                            'Selasa' => \Carbon\Carbon::TUESDAY,
                            'Rabu' => \Carbon\Carbon::WEDNESDAY,
                            'Kamis' => \Carbon\Carbon::THURSDAY,
                            'Jumat' => \Carbon\Carbon::FRIDAY,
                            'Sabtu' => \Carbon\Carbon::SATURDAY,
                            'Minggu' => \Carbon\Carbon::SUNDAY,
                        ];
                        
                        $newDay = $dayMap[$newScheduleTemplate->day] ?? \Carbon\Carbon::MONDAY;
                        $oldDate = \Carbon\Carbon::parse($oldSession->session_date);
                        $newSessionDate = $oldDate->copy()->startOfWeek()->addDays($newDay - 1);
                        
                        // Jika ternyata tanggal baru berada di masa lalu, pindahkan ke minggu depan
                        if ($newSessionDate->lt(now()->startOfDay())) {
                            $newSessionDate->addWeek();
                        }
                    }
                    
                    // Create the NEW session for the student in the new slot
                    \App\Models\ScheduleSession::create([
                        'schedule_id' => $newScheduleTemplate->id,
                        'student_id' => $resRequest->student_id,
                        'teacher_id' => $newScheduleTemplate->teacher_id,
                        'class_id' => $oldSession->class_id,
                        'session_date' => $newSessionDate,
                        'time' => $newScheduleTemplate->time,
                        'status' => 'booked',
                    ]);
                } else {
                    // Fallback for legacy template-level reschedule
                    $newScheduleTemplate->update([
                        'student_id' => $resRequest->student_id,
                        'status' => 'booked',
                    ]);

                    $oldSchedule = Schedule::lockForUpdate()->find($resRequest->old_schedule_id);
                    if ($oldSchedule) {
                        $oldSchedule->update([
                            'student_id' => null,
                            'status' => 'available',
                        ]);
                    }
                }

                // Update Request
                $resRequest->update([
                    'status' => 'approved',
                    'approved_by' => $request->user()->id,
                    'approved_at' => now(),
                ]);

                // Store data for notification
                $newSessionData = [
                    'student_name' => $resRequest->student->user->name ?? $resRequest->student->name,
                    'teacher_phone' => $newScheduleTemplate->teacher->phone ?? '',
                    'teacher_name' => $newScheduleTemplate->teacher->user->name ?? $newScheduleTemplate->teacher->name,
                    'old_date' => $oldSession ? \Carbon\Carbon::parse($oldSession->session_date)->translatedFormat('l, d M Y') : '-',
                    'old_time' => $oldSession ? \Carbon\Carbon::parse($oldSession->time)->format('H:i') : '-',
                    'new_date' => $newSessionDate ? $newSessionDate->translatedFormat('l, d M Y') : '-',
                    'new_time' => \Carbon\Carbon::parse($newScheduleTemplate->time)->format('H:i'),
                ];
            });

            // Send Fonnte Notification
            try {
                $fonnteToken = env('FONNTE_TOKEN');
                $groupFull = '120363425095640755@g.us'; // Target group 1
                $groupBasic = '120363426453491701@g.us'; // Target group 2

                if ($fonnteToken && !empty($newSessionData)) {
                    $teacherPhone = $newSessionData['teacher_phone'];
                    $phoneTag = '';
                    if (!empty($teacherPhone)) {
                        $cleanPhone = preg_replace('/[^0-9]/', '', $teacherPhone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $phoneTag = " (@" . $cleanPhone . ")";
                    }

                    $message = "🔄 *INFO RESCHEDULE KELAS (DISETUJUI)*\n\n";
                    $message .= "Siswa: *{$newSessionData['student_name']}*\n";
                    $message .= "Coach: *{$newSessionData['teacher_name']}*{$phoneTag}\n\n";
                    $message .= "Jadwal Lama yang Dibatalkan:\n";
                    $message .= "Tanggal: {$newSessionData['old_date']}\n";
                    $message .= "Jam: {$newSessionData['old_time']} WIB\n\n";
                    $message .= "*Jadwal Pengganti Baru:*\n";
                    $message .= "Tanggal: *{$newSessionData['new_date']}*\n";
                    $message .= "Jam: *{$newSessionData['new_time']} WIB*\n\n";
                    $message .= "_Perubahan jadwal ini telah dikonfirmasi oleh Admin._";

                    // Send to Group 1
                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => $fonnteToken,
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $groupFull,
                        'message' => $message,
                        'countryCode' => '62',
                    ]);
                    
                    // Send to Group 2
                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => $fonnteToken,
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $groupBasic,
                        'message' => $message,
                        'countryCode' => '62',
                    ]);
                }
            } catch (\Exception $e) {
                // Ignore notification errors
            }

            return back()->with('success', 'Permintaan reschedule berhasil disetujui.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui reschedule: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $resRequest = RescheduleRequest::findOrFail($id);
        
        if ($resRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $resRequest->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Permintaan reschedule telah ditolak.');
    }
}
