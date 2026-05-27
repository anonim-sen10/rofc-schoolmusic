<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendClassReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-class-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders to teachers via Fonnte for classes starting in 30 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');
        
        // Cari sesi hari ini yang belum dikirimi reminder dan statusnya bukan canceled/rescheduled
        $sessions = ScheduleSession::with(['teacher.user', 'student.user', 'student', 'musicClass'])
            ->whereDate('session_date', $now->toDateString())
            ->where('is_reminder_sent', false)
            ->whereNotIn('status', ['rescheduled', 'completed', 'canceled'])
            ->get();

        $token = env('FONNTE_TOKEN');
        $groupId = env('FONNTE_GROUP_ID');

        if (!$token || !$groupId) {
            $this->error('Fonnte Token or Group ID is not configured.');
            return;
        }

        $sentCount = 0;

        foreach ($sessions as $session) {
            $sessionTime = Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->time, 'Asia/Jakarta');
            $diffInMinutes = $now->diffInMinutes($sessionTime, false);

            // Jika sesi akan dimulai dalam 0 hingga 30 menit ke depan
            if ($diffInMinutes >= 0 && $diffInMinutes <= 30) {
                
                $teacherName = $session->teacher->user->name ?? ($session->teacher->name ?? 'Instruktur');
                $studentName = $session->student->user->name ?? ($session->student->name ?? 'Siswa');
                $className = $session->musicClass->name ?? '-';
                $timeFormatted = Carbon::parse($session->time)->format('H:i');
                
                $message = "📢 *INFO JADWAL KELAS ROFC MUSIC*\n\n";
                $message .= "Mohon perhatian kepada instruktur yang bertugas hari ini:\n\n";
                $message .= "🎸 *Coach {$teacherName}*\n";
                $message .= "Siswa: {$studentName}\n";
                $message .= "Kelas: {$className}\n";
                $message .= "Jam: *{$timeFormatted} WIB*\n\n";
                $message .= "_Sesi akan dimulai dalam waktu kurang dari 30 menit. Jangan lupa untuk bersiap dan mengisi kehadiran (Mark Attendance) setelah kelas selesai. Semangat! 🔥_\n\n";
                $message .= "🔗 *Link Login Portal Guru:*\n";
                $message .= url('/login') . "\n";

                try {
                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $groupId,
                        'message' => $message,
                    ]);

                    if ($response->successful()) {
                        $session->update(['is_reminder_sent' => true]);
                        $this->info("Reminder sent for session {$session->id} to group.");
                        $sentCount++;
                    } else {
                        Log::error("Fonnte API Error: " . $response->body());
                        $this->error("Failed to send reminder for session {$session->id}.");
                    }
                } catch (\Exception $e) {
                    Log::error("Fonnte Request Failed: " . $e->getMessage());
                    $this->error("Exception sending reminder for session {$session->id}.");
                }
            }
        }

        $this->info("Finished sending {$sentCount} reminders.");
    }
}
