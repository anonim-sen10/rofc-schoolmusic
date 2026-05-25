<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleSession;
use Illuminate\Support\Facades\Http;

class ForceAttendanceNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-attendance-notification {--id= : ID of the ScheduleSession} {--teacher= : Name of the teacher} {--time= : Time of the session e.g. 18:00}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force send a WhatsApp attendance confirmation for a specific schedule session.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->option('id');
        $teacherName = $this->option('teacher');
        $time = $this->option('time');

        $query = ScheduleSession::with(['teacher', 'student', 'musicClass', 'attendance']);

        if ($id) {
            $query->where('id', $id);
        } else {
            $query->whereDate('session_date', now()->toDateString());
            
            if ($teacherName) {
                $query->whereHas('teacher', function($q) use ($teacherName) {
                    $q->where('name', 'like', '%' . $teacherName . '%');
                });
            }
            if ($time) {
                $query->where('time', 'like', $time . '%');
            }
        }

        $sessions = $query->get();

        if ($sessions->isEmpty()) {
            $this->error('Sesi kelas tidak ditemukan dengan kriteria tersebut.');
            return;
        }

        if ($sessions->count() > 1 && !$id) {
            $this->warn('Ditemukan lebih dari 1 sesi. Menampilkan daftar:');
            foreach ($sessions as $s) {
                $this->line("ID: {$s->id} | Jam: {$s->time} | Guru: " . ($s->teacher->name ?? '-'));
            }
            $this->info('Silakan gunakan --id=ID_SESI agar lebih spesifik.');
            return;
        }

        $session = $sessions->first();

        if (!$session->attendance) {
            $this->error('Sesi ini belum diabsen oleh guru. Silakan absen melalui portal terlebih dahulu, atau abaikan peringatan ini jika Anda hanya ingin mencoba pesan WA.');
        }

        $this->info("Menemukan Sesi: Jam {$session->time} - Guru: " . ($session->teacher->name ?? '-'));

        if ($this->confirm('Kirim pesan konfirmasi absen WhatsApp sekarang?', true)) {
            $this->sendWhatsAppMessage($session);
        }
    }

    private function sendWhatsAppMessage($session)
    {
        $teacher = $session->teacher;
        
        if (!$teacher) {
            $this->error('Guru tidak ditemukan untuk sesi ini.');
            return;
        }

        $studentName = $session->student->user->name ?? ($session->student->name ?? 'Siswa');
        $className = $session->musicClass->name ?? '-';
        $timeFormatted = \Carbon\Carbon::parse($session->time)->format('H:i');
        
        $attendance = $session->attendance;
        $statusText = $attendance ? ucfirst($attendance->status) : 'Hadir (Manual Test)';
        $noteText = $attendance ? ($attendance->note ?: '-') : '-';

        $teacherName = $teacher->user->name ?? $teacher->name;

        $message = "✅ *LAPORAN KEHADIRAN KELAS (ROFC MUSIC)*\n\n";
        $message .= "Terima kasih Coach *{$teacherName}*!\n";
        $message .= "Kehadiran untuk kelas berikut telah berhasil dicatat:\n\n";
        $message .= "Siswa: *{$studentName}*\n";
        $message .= "Kelas: *{$className}*\n";
        $message .= "Jam Sesi: *{$timeFormatted} WIB*\n";
        $message .= "Status Kehadiran: *{$statusText}*\n";
        $message .= "Catatan: {$noteText}\n\n";
        $message .= "_Laporan ini tercatat secara otomatis di sistem. Semangat untuk kelas selanjutnya! 🥁_";

        $fonnteToken = env('FONNTE_TOKEN');
        $groupId = env('FONNTE_GROUP_ID');

        if (!$fonnteToken || !$groupId) {
            $this->error('Fonnte token atau Group ID belum disetting di .env');
            return;
        }

        $this->info('Mengirim ke Fonnte...');

        $response = Http::withHeaders([
            'Authorization' => $fonnteToken,
        ])->post('https://api.fonnte.com/send', [
            'target' => $groupId,
            'message' => $message,
            'countryCode' => '62',
        ]);

        if ($response->successful()) {
            $this->info('Sukses! Pesan laporan absen berhasil terkirim ke Grup WhatsApp.');
        } else {
            $this->error('Gagal mengirim pesan via Fonnte.');
            $this->line($response->body());
        }
    }
}
