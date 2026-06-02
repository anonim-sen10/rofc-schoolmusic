<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$teacher = \App\Models\Teacher::where('name', 'like', '%heri%')->first();
$student = \App\Models\Student::where('email', 'siswa.heri@example.com')->first();
$schedule = \App\Models\Schedule::where('teacher_id', $teacher->id)->first();

if (!$teacher || !$student || !$schedule) {
    echo "Missing data\n";
    exit;
}

// Assign student to the schedule template
$schedule->update([
    'student_id' => $student->id,
    'status' => 'booked'
]);

// Create upcoming sessions
for ($i = 0; $i < 4; $i++) {
    $date = \Carbon\Carbon::parse('next monday')->addWeeks($i);
    \App\Models\ScheduleSession::firstOrCreate([
        'schedule_id' => $schedule->id,
        'student_id' => $student->id,
        'teacher_id' => $teacher->id,
        'class_id' => $schedule->class_id,
        'session_date' => $date->format('Y-m-d'),
        'time' => $schedule->time,
        'status' => 'booked'
    ]);
}

echo "Assigned student to teacher {$teacher->name} and generated 4 sessions.\n";
