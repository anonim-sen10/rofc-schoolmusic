<?php

use App\Models\MusicClass;
use App\Models\Student;
use App\Models\Teacher;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ambil guru yang sedang login (andi)
$teacher = Teacher::where('name', 'andi')->first();
if (!$teacher) {
    echo "Guru 'andi' tidak ditemukan.\n";
    exit;
}

// Ambil siswa ID 9
$student = Student::find(9);
if (!$student) {
    echo "Siswa ID 9 tidak ditemukan.\n";
    exit;
}

echo "Teacher: {$teacher->name} (ID: {$teacher->id})\n";
echo "Student: {$student->name} (ID: {$student->id})\n";

// Cari kelas yang terhubung dengan guru ini
$class = MusicClass::where('teacher_id', $teacher->id)->first();

if (!$class) {
    echo "Guru ini belum punya kelas. Membuat kelas baru...\n";
    $class = MusicClass::create([
        'name' => 'Drum',
        'teacher_id' => $teacher->id,
        'price' => 500000,
        'status' => 'active',
        'assignment_status' => 'accepted'
    ]);
} else {
    echo "Ditemukan kelas: {$class->name} (ID: {$class->id})\n";
    $class->update(['assignment_status' => 'accepted']);
}

// Pastikan siswa ID 9 ada di kelas ini
if (!$class->students()->where('student_id', $student->id)->exists()) {
    echo "Menghubungkan siswa {$student->name} ke kelas {$class->name}...\n";
    $class->students()->attach($student->id);
} else {
    echo "Siswa sudah terhubung ke kelas.\n";
}

echo "Selesai. Silahkan refresh halaman.\n";
