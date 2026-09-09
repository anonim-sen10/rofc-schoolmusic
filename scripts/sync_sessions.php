<?php
require 'c:/Users/Dion/Documents/ProjectRofcDrum/vendor/autoload.php';
$app = require_once 'c:/Users/Dion/Documents/ProjectRofcDrum/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use Carbon\Carbon;

$dayMap = [
    'senin' => 'monday',
    'selasa' => 'tuesday',
    'rabu' => 'wednesday',
    'kamis' => 'thursday',
    'jumat' => 'friday',
    'sabtu' => 'saturday',
    'minggu' => 'sunday',
];

$activeStudents = Student::where('is_active', true)->with('schedules')->get();
$generatedCount = 0;

foreach ($activeStudents as $student) {
    foreach ($student->schedules as $schedule) {
        // Check existing sessions count for this schedule
        $existingCount = ScheduleSession::where('student_id', $student->id)
            ->where('schedule_id', $schedule->id)
            ->count();

        $targetSessions = (int)(($student->duration_months ?: 1) * 4);

        if ($existingCount < $targetSessions) {
            $dayName = $dayMap[strtolower($schedule->day)] ?? strtolower($schedule->day);
            
            // Find last session date or start from student start_date / today
            $lastSession = ScheduleSession::where('student_id', $student->id)
                ->where('schedule_id', $schedule->id)
                ->orderBy('session_date', 'desc')
                ->first();

            if ($lastSession) {
                $startDate = Carbon::parse($lastSession->session_date->format('Y-m-d'))->addWeek();
            } else {
                $startDate = Carbon::parse($student->start_date ?: now()->toDateString());
                if (strtolower($startDate->format('l')) !== $dayName) {
                    $startDate->modify("next {$dayName}");
                }
            }

            $needed = $targetSessions - $existingCount;
            for ($i = 0; $i < $needed; $i++) {
                ScheduleSession::create([
                    'schedule_id' => $schedule->id,
                    'student_id' => $student->id,
                    'teacher_id' => $schedule->teacher_id,
                    'class_id' => $schedule->class_id,
                    'session_date' => $startDate->toDateString(),
                    'time' => $schedule->time,
                    'status' => 'booked',
                ]);
                $generatedCount++;
                $startDate->addWeek();
            }
            echo "Generated {$needed} missing sessions for {$student->name} (Schedule #{$schedule->id})\n";
        }
    }
}

echo "Done! Total new sessions generated: {$generatedCount}\n";
