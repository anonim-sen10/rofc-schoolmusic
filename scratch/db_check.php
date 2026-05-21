<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;
use App\Models\MusicClass;
use Illuminate\Support\Facades\DB;

echo "--- CLASSES ---\n";
foreach (MusicClass::all() as $c) {
    echo "Class ID: {$c->id}, Name: {$c->name}, teacher_id field: {$c->teacher_id}\n";
}

echo "\n--- TEACHERS ---\n";
foreach (Teacher::all() as $t) {
    echo "Teacher ID: {$t->id}, Name: {$t->name}\n";
}

echo "\n--- CLASS_TEACHER PIVOT TABLE ---\n";
$pivot = DB::table('class_teacher')->get();
foreach ($pivot as $row) {
    echo "ID: {$row->id}, Class ID: {$row->class_id}, Teacher ID: {$row->teacher_id}\n";
}
