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
        $absenTime = $attendance ? $attendance->created_at->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : 'Sekarang';
        $teacherName = $teacher->user->name ?? $teacher->name;

        $mapsLink = ($attendance && $attendance->latitude && $attendance->longitude) 
            ? "https://www.google.com/maps?q={$attendance->latitude},{$attendance->longitude}" 
            : "Tidak ada lokasi";

        $fonnteToken = env('FONNTE_TOKEN');
        $groupFull = '120363425095640755@g.us';
        $groupBasic = '120363426453491701@g.us';

        if (!$fonnteToken) {
            $this->error('Fonnte token belum disetting di .env');
            return;
        }

        $this->info('Mengirim ke Fonnte...');

        // 1. Pesan Lengkap (Full)
        $messageFull = "✅ *LAPORAN KEHADIRAN KELAS LENGKAP (ROFC MUSIC)*\n\n";
        $messageFull .= "Terima kasih Coach *{$teacherName}*!\n";
        $messageFull .= "Kehadiran untuk kelas berikut telah berhasil dicatat:\n\n";
        $messageFull .= "Siswa: *{$studentName}*\n";
        $messageFull .= "Kelas: *{$className}*\n";
        $messageFull .= "Jam Sesi: *{$timeFormatted} WIB*\n";
        $messageFull .= "Waktu Absen: *{$absenTime}*\n";
        $messageFull .= "Status Kehadiran: *{$statusText}*\n";
        $messageFull .= "Catatan: {$noteText}\n";
        $messageFull .= "📍 Lokasi: {$mapsLink}\n\n";
        $messageFull .= "_Laporan ini tercatat secara otomatis di sistem. Semangat untuk kelas selanjutnya! 🥁_";

        $payloadFull = [
            'target' => $groupFull,
            'message' => $messageFull,
            'countryCode' => '62',
        ];

        if ($attendance && $attendance->image_path) {
            $payloadFull['url'] = url('storage/' . $attendance->image_path);
        }

        $responseFull = Http::withHeaders([
            'Authorization' => $fonnteToken,
        ])->post('https://api.fonnte.com/send', $payloadFull);

        if ($responseFull->successful()) {
            $this->info('Sukses! Laporan absen LENGKAP berhasil terkirim ke Grup WA Lengkap.');
        } else {
            $this->error('Gagal mengirim pesan LENGKAP via Fonnte.');
            $this->line($responseFull->body());
        }

        // 2. Pesan Biasa (Basic) - Dinonaktifkan
        /*
        $messageBasic = "✅ *INFO KEHADIRAN KELAS (ROFC MUSIC)*\n\n";
        $messageBasic .= "Coach *{$teacherName}* baru saja mencatat kehadiran untuk sesi:\n\n";
        $messageBasic .= "Siswa: *{$studentName}*\n";
        $messageBasic .= "Kelas: *{$className}*\n";
        $messageBasic .= "Jam Sesi: *{$timeFormatted} WIB*\n";
        $messageBasic .= "Status Kehadiran: *{$statusText}*\n\n";
        $messageBasic .= "_Laporan ini tercatat secara otomatis di sistem._";

        $responseBasic = Http::withHeaders([
            'Authorization' => $fonnteToken,
        ])->post('https://api.fonnte.com/send', [
            'target' => $groupBasic,
            'message' => $messageBasic,
            'countryCode' => '62',
        ]);

        if ($responseBasic->successful()) {
            $this->info('Sukses! Laporan absen BIASA berhasil terkirim ke Grup WA Biasa.');
        } else {
            $this->error('Gagal mengirim pesan BIASA via Fonnte.');
            $this->line($responseBasic->body());
        }
        */
    }
}
