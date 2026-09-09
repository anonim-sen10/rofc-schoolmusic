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

                case 'list_files':
                case 'get_file_tree':
                    return $this->listFiles($request);

                case 'read_file':
                case 'view_file':
                    return $this->readFile($request);

                case 'write_file':
                case 'edit_file':
                    return $this->writeFile($request);

                case 'search_code':
                case 'grep':
                    return $this->searchCode($request);

                default:
                    return response()->json([
                        'success' => false,
                        'error' => "Action '{$action}' tidak dikenal. Actions yang tersedia: add_schedule, get_student, list_files, read_file, write_file, search_code."
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil daftar struktur folder dan file project.
     */
    public function listFiles(Request $request)
    {
        $relPath = trim($request->input('path', ''), '/');
        $baseDir = base_path();
        $targetDir = $relPath ? $baseDir . '/' . $relPath : $baseDir;

        if (!str_starts_with(realpath($targetDir) ?: '', realpath($baseDir))) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak: Path di luar project.'], 403);
        }

        if (!is_dir($targetDir)) {
            return response()->json(['success' => false, 'error' => "Folder '{$relPath}' tidak ditemukan."], 404);
        }

        $ignoredDirs = ['.git', 'node_modules', 'vendor', '.idea', '.vscode', 'storage/framework', 'storage/logs'];
        $items = [];

        $scan = scandir($targetDir);
        foreach ($scan as $item) {
            if ($item === '.' || $item === '..') continue;

            $itemRel = $relPath ? $relPath . '/' . $item : $item;
            $fullPath = $targetDir . '/' . $item;

            // Skip ignored
            $isIgnored = false;
            foreach ($ignoredDirs as $ig) {
                if ($item === $ig || str_starts_with($itemRel, $ig)) {
                    $isIgnored = true;
                    break;
                }
            }
            if ($isIgnored) continue;

            $isDir = is_dir($fullPath);
            $items[] = [
                'name' => $item,
                'path' => $itemRel,
                'type' => $isDir ? 'directory' : 'file',
                'size' => $isDir ? null : filesize($fullPath),
            ];
        }

        return response()->json([
            'success' => true,
            'current_path' => $relPath ?: '/',
            'total_items' => count($items),
            'items' => $items
        ]);
    }

    /**
     * Membaca isi file tertentu di dalam project.
     */
    public function readFile(Request $request)
    {
        $relPath = trim($request->input('path', ''), '/');

        if (empty($relPath)) {
            return response()->json(['success' => false, 'error' => 'Parameter "path" tidak boleh kosong.'], 400);
        }

        // Keamanan: Cegah membaca file sensitif .env
        if ($relPath === '.env' || str_ends_with($relPath, '/.env') || str_contains($relPath, '.env.')) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak: File .env dilindungi.'], 403);
        }

        $baseDir = base_path();
        $fullPath = $baseDir . '/' . $relPath;
        $realPath = realpath($fullPath);

        if (!$realPath || !str_starts_with($realPath, realpath($baseDir))) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak atau file tidak ditemukan.'], 404);
        }

        if (!is_file($realPath)) {
            return response()->json(['success' => false, 'error' => "Path '{$relPath}' adalah folder, bukan file."], 400);
        }

        $content = file_get_contents($realPath);
        $lines = explode("\n", $content);

        return response()->json([
            'success' => true,
            'path' => $relPath,
            'lines_count' => count($lines),
            'size_bytes' => strlen($content),
            'content' => $content
        ]);
    }

    /**
     * Memperbarui / membuat file di dalam project.
     */
    public function writeFile(Request $request)
    {
        $relPath = trim($request->input('path', ''), '/');
        $content = $request->input('content');

        if (empty($relPath) || $content === null) {
            return response()->json(['success' => false, 'error' => 'Parameter "path" dan "content" wajib diisi.'], 400);
        }

        // Keamanan: Cegah modifikasi file sensitif .env
        if ($relPath === '.env' || str_ends_with($relPath, '/.env') || str_contains($relPath, '.env.')) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak: File .env dilindungi.'], 403);
        }

        $baseDir = base_path();
        $fullPath = $baseDir . '/' . $relPath;

        // Pastikan folder parent ada
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($fullPath, $content);

        return response()->json([
            'success' => true,
            'message' => "File '{$relPath}' berhasil disimpan.",
            'path' => $relPath,
            'size_bytes' => strlen($content)
        ]);
    }

    /**
     * Mencari kata kunci/kode dalam file-file project.
     */
    public function searchCode(Request $request)
    {
        $query = $request->input('query', $request->input('keyword', ''));
        $searchDir = trim($request->input('path', 'app'), '/');

        if (empty($query)) {
            return response()->json(['success' => false, 'error' => 'Parameter "query" atau "keyword" wajib diisi.'], 400);
        }

        $baseDir = base_path();
        $targetDir = $baseDir . '/' . $searchDir;

        if (!is_dir($targetDir)) {
            return response()->json(['success' => false, 'error' => "Folder '{$searchDir}' tidak ditemukan."], 404);
        }

        $matches = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($targetDir));

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;
            $filePath = $file->getPathname();

            // Skip vendor/node_modules/.git
            if (str_contains($filePath, '/vendor/') || str_contains($filePath, '/node_modules/') || str_contains($filePath, '/.git/')) {
                continue;
            }

            $content = @file_get_contents($filePath);
            if ($content && str_contains($content, $query)) {
                $lines = explode("\n", $content);
                $matchingLines = [];
                foreach ($lines as $lineNum => $line) {
                    if (str_contains($line, $query)) {
                        $matchingLines[] = [
                            'line' => $lineNum + 1,
                            'content' => trim($line)
                        ];
                    }
                }

                $relFile = str_replace($baseDir . '/', '', str_replace('\\', '/', $filePath));
                $matches[] = [
                    'file' => $relFile,
                    'match_count' => count($matchingLines),
                    'lines' => array_slice($matchingLines, 0, 5) // Return max 5 matching lines per file
                ];
            }
        }

        return response()->json([
            'success' => true,
            'keyword' => $query,
            'total_files_matched' => count($matches),
            'matches' => $matches
        ]);
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
            ], 404);
        }

        $time = strlen($timeStr) === 5 ? $timeStr . ':00' : $timeStr;

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
