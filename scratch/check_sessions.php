<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Explicitly set SQLite database path
config(['database.connections.sqlite.database' => database_path('database.sqlite')]);
\Illuminate\Support\Facades\DB::setDefaultConnection('sqlite');

$student = \App\Models\Student::where('name', 'like', '%saddam%')->first();
if (!$student) {
    echo "Student saddam not found in SQLite.\n";
    exit;
}

echo "Student: {$student->name} (ID: {$student->id})\n";
echo "Schedules registered for student in schedules table:\n";
$schedules = \App\Models\Schedule::where('student_id', $student->id)->get();
foreach ($schedules as $s) {
    echo "  - ID: {$s->id}, Day: {$s->day}, Time: {$s->time}, Class ID: {$s->class_id}, Teacher ID: {$s->teacher_id}\n";
}

echo "Schedule Sessions:\n";
$sessions = \App\Models\ScheduleSession::where('student_id', $student->id)
    ->orderBy('session_date')
    ->orderBy('time')
    ->get();
foreach ($sessions as $sess) {
    echo "  - ID: {$sess->id}, Date: {$sess->session_date}, Time: {$sess->time}, Schedule ID: {$sess->schedule_id}, Status: {$sess->status}\n";
}
