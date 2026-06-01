<?php

$fonnteToken = env('FONNTE_TOKEN');
$groupFull = '120363425095640755@g.us'; // Group 1
$groupBasic = '120363426453491701@g.us'; // Group 2

// Ambil 1 data guru secara acak
$teacher = \App\Models\Teacher::with('user')->first();
$teacherName = $teacher ? ($teacher->user->name ?? $teacher->name) : 'SHYAKIRA FATIHA';

// Ambil 1 data siswa secara acak
$student = \App\Models\Student::with('user')->first();
$studentName = $student ? ($student->user->name ?? $student->name) : 'Winola';

$message = "🔄 *INFO RESCHEDULE KELAS (DISETUJUI)*\n\n";
$message .= "Siswa: *{$studentName}*\n";
$message .= "Coach: *{$teacherName}*\n\n";
$message .= "Jadwal Lama yang Dibatalkan:\n";
$message .= "Tanggal: Rabu, 27 Mei 2026\n";
$message .= "Jam: 19:00 WIB\n\n";
$message .= "*Jadwal Pengganti Baru:*\n";
$message .= "Tanggal: *Rabu, 03 Jun 2026*\n";
$message .= "Jam: *19:00 WIB*\n\n";
$message .= "_Perubahan jadwal ini telah dikonfirmasi oleh Admin._";

// Send to Group 1
\Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => $fonnteToken,
])->post('https://api.fonnte.com/send', [
    'target' => $groupFull,
    'message' => $message,
    'countryCode' => '62',
]);

// Send to Group 2 - Dinonaktifkan
/*
\Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => $fonnteToken,
])->post('https://api.fonnte.com/send', [
    'target' => $groupBasic,
    'message' => $message,
    'countryCode' => '62',
]);
*/

echo "Pesan berhasil dikirim ke dua grup secara nyata!\n";
