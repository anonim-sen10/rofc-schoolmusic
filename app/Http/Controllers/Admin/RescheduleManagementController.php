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
            DB::transaction(function () use ($id, $request): void {
                $resRequest = RescheduleRequest::lockForUpdate()->findOrFail($id);

                if ($resRequest->status !== 'pending') {
                    throw new \Exception('Request ini sudah diproses.');
                }

                $oldSession = \App\Models\ScheduleSession::lockForUpdate()->find($resRequest->old_session_id);
                $newScheduleTemplate = Schedule::lockForUpdate()->findOrFail($resRequest->new_schedule_id);

                if ($oldSession) {
                    // Update the session to the new template's day/time (or keep same date if same day)
                    // For now, let's just update the teacher and time/day if template is different.
                    // Accurate logic would be picking a specific date, but here we'll assume it's moved to the new template's next occurrence.
                    
                    $oldSession->update([
                        'status' => 'rescheduled',
                    ]);

                    // Calculate the correct date for the new session based on the template's day
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
                    // Find the date for the new day in the same week as the old date
                    $newSessionDate = $oldDate->copy()->startOfWeek()->addDays($newDay - 1);
                    
                    // If the calculated date is in the past relative to the old session's start of week logic, 
                    // or we want to ensure it's the "nearest" one, we could refine this.
                    // But usually, reschedule stays in the same week block or moves forward.
                    
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
            });

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
