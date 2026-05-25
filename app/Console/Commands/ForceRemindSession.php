<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ForceRemindSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-remind-session {--id= : ID of the ScheduleSession} {--teacher= : Name of the teacher} {--time= : Time of the session e.g. 18:00}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force send a WhatsApp reminder for a specific schedule session, even if the time has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->option('id');
        $teacherName = $this->option('teacher');
        $time = $this->option('time');

        $query = ScheduleSession::with(['teacher', 'student', 'musicClass']);

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

        $this->info("Menemukan Sesi: Jam {$session->time} - Guru: " . ($session->teacher->name ?? '-'));

        if ($this->confirm('Kirim pesan peringatan WhatsApp sekarang?', true)) {
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

        $studentName = $session->student->name ?? 'Siswa';
        $className = $session->musicClass->name ?? 'Kelas';
        $timeFormatted = \Carbon\Carbon::parse($session->time)->format('H:i');
        $dateFormatted = \Carbon\Carbon::parse($session->session_date)->locale('id')->isoFormat('dddd, D MMMM YYYY');

        $message = "🔔 *PENGINGAT KELAS (MANUAL) - ROFC MUSIC SCHOOL* 🔔\n\n";
        $message .= "Halo *Kak {$teacher->name}*,\n\n";
        $message .= "Ini adalah pesan pengingat manual bahwa Anda memiliki sesi kelas pada:\n\n";
        $message .= "Siswa: *{$studentName}*\n";
        $message .= "Kelas: *{$className}*\n";
        $message .= "Tanggal: *{$dateFormatted}*\n";
        $message .= "Jam: *{$timeFormatted} WIB*\n\n";
        $message .= "_Harap segera berkoordinasi dengan siswa jika ada penyesuaian waktu. Jangan lupa mengisi kehadiran (Mark Attendance) setelah kelas selesai. Semangat! 🔥_\n\n";
        $message .= "🔗 *Link Login Portal Guru:*\n";
        $message .= url('/login');

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
            $session->is_reminder_sent = true;
            $session->save();
            $this->info('Sukses! Pesan berhasil terkirim ke Grup WhatsApp.');
        } else {
            $this->error('Gagal mengirim pesan via Fonnte.');
            $this->line($response->body());
        }
    }
}
