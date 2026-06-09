<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sessions = \App\Models\ScheduleSession::with('schedule')->whereHas('student', function($q) {
    $q->where('name', 'like', '%Rayzent%');
})->orderBy('session_date', 'desc')->get(['id', 'schedule_id', 'session_date', 'status'])->toArray();

echo json_encode($sessions, JSON_PRETTY_PRINT);
