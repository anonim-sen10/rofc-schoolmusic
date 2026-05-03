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
                        'schedule_id' => $newScheduleTemplate->id,
                        'teacher_id' => $newScheduleTemplate->teacher_id,
                        'time' => $newScheduleTemplate->time,
                        // session_date should ideally be updated too, but we'll leave it to the user to pick in future UI.
                        // For now, mark as rescheduled.
                        'status' => 'rescheduled',
                    ]);

                    // Create the NEW session for the student in the new slot
                    \App\Models\ScheduleSession::create([
                        'schedule_id' => $newScheduleTemplate->id,
                        'student_id' => $resRequest->student_id,
                        'teacher_id' => $newScheduleTemplate->teacher_id,
                        'class_id' => $oldSession->class_id,
                        'session_date' => $oldSession->session_date, // Keep same date for now
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
