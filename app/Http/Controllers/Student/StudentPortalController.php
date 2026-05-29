<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MusicClass;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    private function studentFromUser(int $userId, string $name, string $email): Student
    {
        return Student::firstOrCreate(
            ['user_id' => $userId],
            ['name' => $name, 'email' => $email, 'is_active' => true]
        );
    }

    public function dashboard(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);
        
        // Total Sessions (booked schedules)
        $totalSessions = \App\Models\Schedule::whereHas('student', fn($q) => $q->where('id', $student->id))
            ->where('status', 'booked')
            ->count();
            
        // Total Present
        $totalPresent = \App\Models\Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();
            
        // Total Absent
        $totalAbsent = \App\Models\Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->count();

        // My Sessions
        $schedules = \App\Models\ScheduleSession::where('student_id', $student->id)
            ->whereIn('status', ['booked', 'rescheduled'])
            ->with(['musicClass', 'teacher'])
            ->orderBy('session_date')
            ->orderBy('time')
            ->get();

        // Attendance History
        $attendances = \App\Models\Attendance::where('student_id', $student->id)
            ->with('schedule')
            ->latest('created_at')
            ->get();

        return view('portal.student.dashboard', [
            'student' => $student,
            'totalSessions' => $totalSessions,
            'totalPresent' => $totalPresent,
            'totalAbsent' => $totalAbsent,
            'schedules' => $schedules,
            'attendances' => $attendances,
            'paymentsCount' => Payment::where('student_id', $student->id)->count(),
            'progressCount' => StudentProgress::where('student_id', $student->id)->count(),
        ]);
    }

    public function myClass(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);
        
        // Load the main class assigned to the student (via class_id column)
        $assignedClass = $student->class()->with('teacher')->first();

        return view('portal.student.my-class', [
            'student' => $student,
            'assignedClass' => $assignedClass,
        ]);
    }

    public function schedule(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        // Fetch booked sessions for this student
        $schedules = \App\Models\ScheduleSession::where('student_id', $student->id)
            ->whereIn('status', ['booked', 'rescheduled', 'completed'])
            ->with(['musicClass', 'teacher', 'attendance'])
            ->orderBy('session_date')
            ->orderBy('time')
            ->get();

        return view('portal.student.schedule', [
            'student' => $student,
            'schedules' => $schedules,
        ]);
    }

    public function payment(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.payment', [
            'student' => $student,
            'payments' => Payment::where('student_id', $student->id)->latest()->get(),
        ]);
    }

    public function paymentStatus(Request $request): JsonResponse
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        $latest = Payment::where('student_id', $student->id)->latest()->first();

        return response()->json([
            'latest_status' => $latest?->status ?? 'no-payment',
            'latest_amount' => $latest?->amount,
            'latest_date' => optional($latest?->paid_at)->format('Y-m-d'),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    public function progress(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.progress', [
            'records' => StudentProgress::where('student_id', $student->id)->latest()->get(),
        ]);
    }

    public function materials(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);
        $classIds = $student->classes()->pluck('classes.id');

        return view('portal.student.materials', [
            'materials' => Material::whereIn('class_id', $classIds)->orWhereNull('class_id')->latest()->get(),
        ]);
    }

    public function profile(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.profile', [
            'student' => $student,
        ]);
    }

    public function updateProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:4', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        $student->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function availableSlots(Request $request): JsonResponse
    {
        $teacherId = $request->query('teacher_id');
        $classId = $request->query('class_id');
        
        if (!$teacherId || !$classId) return response()->json(['grouped' => []]);

        $slots = \App\Models\Schedule::query()
            ->where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('status', 'available')
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('time')
            ->get(['id', 'day', 'time']);

        $dayMap = [
            'Senin' => \Carbon\Carbon::MONDAY,
            'Selasa' => \Carbon\Carbon::TUESDAY,
            'Rabu' => \Carbon\Carbon::WEDNESDAY,
            'Kamis' => \Carbon\Carbon::THURSDAY,
            'Jumat' => \Carbon\Carbon::FRIDAY,
            'Sabtu' => \Carbon\Carbon::SATURDAY,
            'Minggu' => \Carbon\Carbon::SUNDAY,
        ];

        $grouped = $slots->groupBy('day')->map(function($daySlots, $dayName) use ($dayMap) {
            $carbonDay = $dayMap[$dayName] ?? \Carbon\Carbon::MONDAY;
            
            // Calculate the date for the upcoming occurrence of this day
            $now = \Carbon\Carbon::now();
            if ($now->dayOfWeek === $carbonDay) {
                $date = $now;
            } else {
                $date = $now->copy()->next($carbonDay);
            }

            return $daySlots->map(fn($s) => [
                'id' => $s->id,
                'day' => $s->day,
                'date_label' => $date->translatedFormat('l, d M Y'),
                'time' => substr((string)$s->time, 0, 5),
            ]);
        });

        return response()->json(['grouped' => $grouped]);
    }

    public function requestReschedule(Request $request): \Illuminate\Http\RedirectResponse
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        $validated = $request->validate([
            'old_session_id' => ['required', 'exists:schedule_sessions,id'],
            'new_schedule_id' => ['required', 'exists:schedules,id'], // User still picks from available template slots? Or available sessions?
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        // Security: Check if old_session belongs to student
        $oldSession = \App\Models\ScheduleSession::where('id', $validated['old_session_id'])
            ->where('student_id', $student->id)
            ->where('status', 'booked')
            ->firstOrFail();

        // Check if there is already a pending request for this session
        $exists = \App\Models\RescheduleRequest::where('student_id', $student->id)
            ->where('old_session_id', $validated['old_session_id'])
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah memiliki permintaan reschedule yang sedang diproses untuk sesi ini.');
        }

        \App\Models\RescheduleRequest::create([
            'student_id' => $student->id,
            'old_schedule_id' => $oldSession->schedule_id,
            'old_session_id' => $oldSession->id,
            'new_schedule_id' => $validated['new_schedule_id'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Send Fonnte Notification for new reschedule request
        try {
            $fonnteToken = env('FONNTE_TOKEN');
            $groupId = env('FONNTE_GROUP_ID', '120363425095640755@g.us');
            if ($fonnteToken && $groupId) {
                $studentName = $student->name ?? 'Siswa';
                $className = $oldSession->musicClass->name ?? 'Kelas';
                $oldTime = $oldSession->session_date->format('d M Y') . ' ' . \Carbon\Carbon::parse($oldSession->time)->format('H:i');
                
                $newTimeText = "Dorong Mundur 1 Minggu";
                if ($validated['new_schedule_id'] != $oldSession->schedule_id) {
                    $newSlot = \App\Models\Schedule::find($validated['new_schedule_id']);
                    if ($newSlot) {
                        $newTimeText = $newSlot->day_of_week . ' ' . \Carbon\Carbon::parse($newSlot->start_time)->format('H:i');
                    }
                }

                $message = "*PERMINTAAN RESCHEDULE BARU*\n\n";
                $message .= "Siswa: *$studentName*\n";
                $message .= "Kelas: *$className*\n";
                $message .= "Jadwal Lama: *$oldTime*\n";
                $message .= "Jadwal Baru: *$newTimeText*\n";
                $message .= "Alasan: " . ($validated['reason'] ?: '-') . "\n\n";
                $message .= "Diajukan oleh: *Siswa*\n";
                $message .= "Silakan cek dashboard Super Admin untuk memproses permintaan ini.";

                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $fonnteToken,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $groupId,
                    'message' => $message,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Reschedule Request Notification Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Permintaan reschedule telah dikirim dan menunggu persetujuan admin.');
    }
}
