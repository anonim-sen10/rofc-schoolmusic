<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\MusicClass;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrialAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data yang sudah ada
        $teacher = Teacher::first();
        $student = Student::first();
        
        if (!$teacher || !$student) {
            $this->command->error('Pastikan ada minimal 1 guru dan 1 siswa di database.');
            return;
        }

        // 2. Pastikan ada Kelas dan Siswa terdaftar di kelas tersebut
        $musicClass = MusicClass::first();
        if (!$musicClass) {
            $musicClass = MusicClass::create([
                'name' => 'Trial Music Class',
                'teacher_id' => $teacher->id,
                'price' => 500000,
                'status' => 'active',
                'assignment_status' => 'accepted'
            ]);
        } else {
            $musicClass->update(['teacher_id' => $teacher->id, 'assignment_status' => 'accepted']);
        }

        // Hubungkan siswa ke kelas
        if (!$musicClass->students()->where('student_id', $student->id)->exists()) {
            $musicClass->students()->attach($student->id);
        }

        // 3. Buat Schedule (Weekly)
        $dayName = strtolower(now()->format('l'));
        $schedule = Schedule::updateOrCreate(
            [
                'class_id' => $musicClass->id,
                'teacher_id' => $teacher->id,
                'student_id' => $student->id,
                'day' => $dayName,
            ],
            [
                'time' => now()->format('H:i:s'),
                'status' => 'booked' // Sesuai enum: available, booked
            ]
        );

        // 4. Buat Session untuk HARI INI (Ready to Mark Attendance)
        ScheduleSession::where('session_date', now()->toDateString())
            ->where('schedule_id', $schedule->id)
            ->delete();

        ScheduleSession::create([
            'schedule_id' => $schedule->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'class_id' => $musicClass->id,
            'session_date' => now()->toDateString(),
            'time' => now()->format('H:i:s'),
            'status' => 'booked',
        ]);

        // 5. Buat Session untuk KEMARIN (Sudah Selesai/Completed)
        $yesterday = now()->subDay();
        ScheduleSession::where('session_date', $yesterday->toDateString())
            ->where('schedule_id', $schedule->id)
            ->delete();

        $sessionYesterday = ScheduleSession::create([
            'schedule_id' => $schedule->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'class_id' => $musicClass->id,
            'session_date' => $yesterday->toDateString(),
            'time' => '14:00:00',
            'status' => 'completed',
        ]);

        // 6. Buat data Attendance untuk session kemarin
        Attendance::where('session_id', $sessionYesterday->id)->delete();
        Attendance::create([
            'schedule_id' => $schedule->id,
            'session_id' => $sessionYesterday->id,
            'teacher_id' => $teacher->id,
            'student_id' => $student->id,
            'status' => 'present',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'note' => 'Hadir tepat waktu (Dummy Data)',
            'created_at' => $yesterday,
        ]);

        $this->command->info('Dummy data absen berhasil dibuat!');
        $this->command->info('Guru: ' . $teacher->name);
        $this->command->info('Siswa: ' . $student->name);
    }
}
