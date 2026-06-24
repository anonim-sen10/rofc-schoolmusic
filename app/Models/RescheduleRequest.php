<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescheduleRequest extends Model
{
    protected $fillable = [
        'student_id',
        'old_schedule_id',
        'old_session_id',
        'new_schedule_id',
        'new_date',
        'new_session_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function oldSession(): BelongsTo
    {
        return $this->belongsTo(ScheduleSession::class, 'old_session_id');
    }

    public function newSession(): BelongsTo
    {
        return $this->belongsTo(ScheduleSession::class, 'new_session_id');
    }

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function oldSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'old_schedule_id');
    }

    public function newSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'new_schedule_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getOldLabelAttribute(): string
    {
        if ($this->oldSession) {
            return \Carbon\Carbon::parse($this->oldSession->session_date)->translatedFormat('l, d M Y') . ' - ' . substr((string) $this->oldSession->time, 0, 5);
        }
        if ($this->oldSchedule) {
            return $this->oldSchedule->day . ' ' . substr((string) $this->oldSchedule->time, 0, 5);
        }
        return '-';
    }

    public function getNewLabelAttribute(): string
    {
        if (!$this->newSchedule) return '-';

        $isPushBack = $this->oldSession && ($this->new_schedule_id == $this->oldSession->schedule_id);
        if ($isPushBack) {
            return '➡️ Dorong Mundur 1 Minggu';
        }

        if ($this->new_date) {
            return \Carbon\Carbon::parse($this->new_date)->translatedFormat('l, d M Y') . ' - ' . substr((string) $this->newSchedule->time, 0, 5);
        }

        // Fallback to calculate from oldSession if no new_date
        if ($this->oldSession) {
            $dayMap = [
                'Senin' => \Carbon\Carbon::MONDAY,
                'Selasa' => \Carbon\Carbon::TUESDAY,
                'Rabu' => \Carbon\Carbon::WEDNESDAY,
                'Kamis' => \Carbon\Carbon::THURSDAY,
                'Jumat' => \Carbon\Carbon::FRIDAY,
                'Sabtu' => \Carbon\Carbon::SATURDAY,
                'Minggu' => \Carbon\Carbon::SUNDAY,
            ];
            $newDayNum = $dayMap[$this->newSchedule->day] ?? \Carbon\Carbon::MONDAY;
            $oldDate = \Carbon\Carbon::parse($this->oldSession->session_date);
            $newDate = $oldDate->copy()->startOfWeek()->addDays($newDayNum - 1);
            
            if ($newDate->lt(now()->startOfDay())) {
                $newDate->addWeek();
            }
            
            return $newDate->translatedFormat('l, d M Y') . ' - ' . substr((string) $this->newSchedule->time, 0, 5);
        }

        return $this->newSchedule->day . ' ' . substr((string) $this->newSchedule->time, 0, 5);
    }

    public static function autoApprove(self $resRequest, $approvedBy = null): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($resRequest, $approvedBy): void {
            // Lock request
            $resRequest->lockForUpdate();

            if ($resRequest->status !== 'pending') {
                return;
            }

            $oldSession = \App\Models\ScheduleSession::lockForUpdate()->find($resRequest->old_session_id);
            $newScheduleTemplate = \App\Models\Schedule::lockForUpdate()->findOrFail($resRequest->new_schedule_id);

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
                    if ($resRequest->new_date) {
                        $newSessionDate = \Carbon\Carbon::parse($resRequest->new_date);
                    } else {
                        // Fallback untuk request lama yang belum punya new_date
                        $dayMap = [
                            'Senin' => \Carbon\Carbon::MONDAY,
                            'Selasa' => \Carbon\Carbon::TUESDAY,
                            'Rabu' => \Carbon\Carbon::WEDNESDAY,
                            'Kamis' => \Carbon\Carbon::THURSDAY,
                            'Jumat' => \Carbon\Carbon::FRIDAY,
                            'Sabtu' => \Carbon\Carbon::SATURDAY,
                            'Minggu' => \Carbon\Carbon::SUNDAY,
                        ];
                        
                        $carbonDay = $dayMap[$newScheduleTemplate->day] ?? \Carbon\Carbon::MONDAY;
                        
                        $requestDate = \Carbon\Carbon::parse($resRequest->created_at);
                        if ($requestDate->dayOfWeek === $carbonDay) {
                            $newSessionDate = $requestDate->copy();
                        } else {
                            $newSessionDate = $requestDate->copy()->next($carbonDay);
                        }
                        
                        if ($newSessionDate->lt(now()->startOfDay())) {
                            $newSessionDate->addWeek();
                        }
                    }
                }
                
                // Create the NEW session for the student in the new slot
                $newSession = \App\Models\ScheduleSession::create([
                    'schedule_id' => $newScheduleTemplate->id,
                    'student_id' => $resRequest->student_id,
                    'teacher_id' => $newScheduleTemplate->teacher_id,
                    'class_id' => $oldSession->class_id,
                    'session_date' => $newSessionDate,
                    'time' => $newScheduleTemplate->time,
                    'status' => 'booked',
                ]);

                // Save new session id in reschedule request
                $resRequest->update([
                    'new_session_id' => $newSession->id,
                ]);
            } else {
                // Fallback for legacy template-level reschedule
                $newScheduleTemplate->update([
                    'student_id' => $resRequest->student_id,
                    'status' => 'booked',
                ]);

                $oldSchedule = \App\Models\Schedule::lockForUpdate()->find($resRequest->old_schedule_id);
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
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);

            // Send Fonnte Notification (DISETUJUI)
            try {
                $fonnteToken = env('FONNTE_TOKEN');
                $groupFull = '120363425095640755@g.us'; // Target group 1

                if ($fonnteToken) {
                    $studentName = $resRequest->student->user->name ?? ($resRequest->student->name ?? 'Siswa');
                    $teacherName = $newScheduleTemplate->teacher->user->name ?? ($newScheduleTemplate->teacher->name ?? 'Coach');
                    $oldDate = $oldSession ? \Carbon\Carbon::parse($oldSession->session_date)->translatedFormat('l, d M Y') : '-';
                    $oldTime = $oldSession ? \Carbon\Carbon::parse($oldSession->time)->format('H:i') : '-';
                    $newDateText = $newSessionDate ? $newSessionDate->translatedFormat('l, d M Y') : '-';
                    $newTimeText = \Carbon\Carbon::parse($newScheduleTemplate->time)->format('H:i');

                    $message = "🔄 *INFO RESCHEDULE KELAS (DISETUJUI OTOMATIS)*\n\n";
                    $message .= "Siswa: *{$studentName}*\n";
                    $message .= "Coach: *{$teacherName}*\n\n";
                    $message .= "Jadwal Lama yang Dibatalkan:\n";
                    $message .= "Tanggal: {$oldDate}\n";
                    $message .= "Jam: {$oldTime} WIB\n\n";
                    $message .= "*Jadwal Pengganti Baru:*\n";
                    $message .= "Tanggal: *{$newDateText}*\n";
                    $message .= "Jam: *{$newTimeText} WIB*\n\n";
                    $message .= "_Perubahan jadwal ini disetujui otomatis oleh sistem._";

                    // Determine targets: Group and Teacher (if available)
                    $target = $groupFull;
                    $teacherPhone = $newScheduleTemplate->teacher->phone ?? null;
                    if ($teacherPhone) {
                        $formattedPhone = preg_replace('/[^0-9]/', '', $teacherPhone);
                        if (str_starts_with($formattedPhone, '0')) {
                            $formattedPhone = '62' . substr($formattedPhone, 1);
                        }
                        $target .= ',' . $formattedPhone;
                    }

                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => $fonnteToken,
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $message,
                        'countryCode' => '62',
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Fonnte Auto-Approve Reschedule Notification Error: ' . $e->getMessage());
            }
        });
    }
}

