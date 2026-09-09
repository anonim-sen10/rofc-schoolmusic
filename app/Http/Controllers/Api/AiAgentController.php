<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use App\Models\MusicClass;
use App\Models\Teacher;
use Carbon\Carbon;

class AiAgentController extends Controller
{
    /**
     * Mengembalikan daftar semua tabel dan kolom dalam database.
     * Berguna agar AI Bot Telegram tahu struktur data yang tersedia.
     */
    public function getSchema()
    {
        try {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $schema = [];

            foreach ($tables as $table) {
                if (in_array($table, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens'])) {
                    continue;
                }

                $columns = Schema::getColumnListing($table);
                $schema[$table] = $columns;
            }

            return response()->json([
                'success' => true,
                'data' => $schema
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengeksekusi SQL Query (SELECT, INSERT, UPDATE, DELETE).
     */
    public function runQuery(Request $request)
    {
        $query = trim($request->input('query', ''));

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'error' => 'Query SQL tidak boleh kosong. Kirim parameter "query".'
            ], 400);
        }

        $queryUpper = strtoupper($query);

        // Keamanan dasar: Blokir perintah berbahaya seperti DROP, TRUNCATE, ALTER
        $forbidden = ['DROP ', 'TRUNCATE ', 'ALTER ', 'FLUSH '];
        foreach ($forbidden as $word) {
            if (strpos($queryUpper, $word) !== false) {
                return response()->json([
                    'success' => false,
                    'error' => "Keamanan: Perintah {$word} tidak diizinkan."
                ], 403);
            }
        }

        try {
            if (str_starts_with($queryUpper, 'SELECT')) {
                $results = DB::select($query);
                return response()->json([
                    'success' => true,
                    'count' => count($results),
                    'data' => $results
                ]);
            } elseif (str_starts_with($queryUpper, 'INSERT')) {
                $inserted = DB::insert($query);
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil ditambahkan.',
                    'affected' => $inserted
                ]);
            } elseif (str_starts_with($queryUpper, 'UPDATE')) {
                $affected = DB::update($query);
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diperbarui.',
                    'affected' => $affected
                ]);
            } elseif (str_starts_with($queryUpper, 'DELETE')) {
                $affected = DB::delete($query);
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus.',
                    'affected' => $affected
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Keamanan: Hanya operasi SELECT, INSERT, UPDATE, DELETE yang diizinkan.'
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eksekusi Aksi Terstruktur (High-level Actions).
     * Contoh: add_schedule, reschedule, add_student.
     */
    public function executeAction(Request $request)
    {
        $action = strtolower(trim($request->input('action', '')));

        if (empty($action)) {
            return response()->json([
                'success' => false,
                'error' => 'Parameter "action" tidak boleh kosong.'
            ], 400);
        }

        try {
            switch ($action) {
                case 'add_schedule':
                case 'create_schedule':
                    return $this->handleAddSchedule($request);

                case 'get_student':
                case 'student_info':
                    return $this->handleGetStudent($request);

                default:
                    return response()->json([
                        'success' => false,
                        'error' => "Action '{$action}' tidak dikenal. Action yang tersedia: add_schedule, get_student."
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function handleAddSchedule(Request $request)
    {
        $studentName = $request->input('student_name');
        $studentId = $request->input('student_id');
        $startDateStr = $request->input('start_date', now()->toDateString());
        $timeStr = $request->input('time', '15:00:00');
        $durationMonths = (int) $request->input('duration_months', 1);

        $student = $studentId
            ? Student::find($studentId)
            : Student::where('name', 'like', "%{$studentName}%")->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'error' => "Siswa '{$studentName}' tidak ditemukan dalam database."
            ], 44);
        }

        // Format waktu
        $time = strlen($timeStr) === 5 ? $timeStr . ':00' : $timeStr;

        // Resolve Class & Teacher
        $classId = $student->class_id ?? MusicClass::first()->id;
        $schedule = Schedule::where('student_id', $student->id)->first();
        $teacherId = $schedule ? $schedule->teacher_id : Teacher::first()->id;

        $startDate = Carbon::parse($startDateStr);
        $dayNameMap = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];
        $dayName = $dayNameMap[$startDate->dayOfWeek];

        if (!$schedule) {
            $schedule = Schedule::where('class_id', $classId)
                ->where('day', $dayName)
                ->where('time', $time)
                ->where('teacher_id', $teacherId)
                ->first();

            if (!$schedule) {
                $schedule = Schedule::create([
                    'class_id' => $classId,
                    'day' => $dayName,
                    'time' => $time,
                    'teacher_id' => $teacherId,
                    'student_id' => $student->id,
                    'status' => 'booked',
                ]);
            } else {
                $schedule->update([
                    'student_id' => $student->id,
                    'status' => 'booked',
                ]);
            }
        }

        // Generate 4 sesi bulanan x durasi
        $totalSessions = $durationMonths * 4;
        $createdSessions = [];
        $currentDate = $startDate->copy();

        for ($i = 0; $i < $totalSessions; $i++) {
            $session = ScheduleSession::create([
                'schedule_id' => $schedule->id,
                'student_id' => $student->id,
                'teacher_id' => $teacherId,
                'class_id' => $classId,
                'session_date' => $currentDate->toDateString(),
                'time' => $time,
                'status' => 'booked',
            ]);

            $createdSessions[] = [
                'session_id' => $session->id,
                'date' => $currentDate->format('Y-m-d'),
                'day' => $dayName,
                'time' => $time,
            ];

            $currentDate->addWeek();
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil menambahkan {$totalSessions} sesi jadwal untuk {$student->name} mulai {$startDateStr} jam {$time}.",
            'data' => [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'schedule_id' => $schedule->id,
                'total_sessions_created' => count($createdSessions),
                'sessions' => $createdSessions,
            ]
        ]);
    }

    private function handleGetStudent(Request $request)
    {
        $name = $request->input('name');
        $students = Student::where('name', 'like', "%{$name}%")->with(['musicClass', 'schedules'])->get();

        return response()->json([
            'success' => true,
            'count' => $students->count(),
            'data' => $students
        ]);
    }
}
