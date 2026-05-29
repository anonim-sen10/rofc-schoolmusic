<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AiAgentController extends Controller
{
    /**
     * Mengembalikan daftar semua tabel dan kolom dalam database.
     * Berguna agar AI tahu struktur data yang tersedia.
     */
    public function getSchema()
    {
        try {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $schema = [];

            foreach ($tables as $table) {
                // Sembunyikan tabel yang bersifat internal/rahasia jika perlu
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
     * Mengeksekusi raw SQL query (HANYA SELECT).
     */
    public function runQuery(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'error' => 'Query SQL tidak boleh kosong. Kirim parameter "query".'
            ], 400);
        }

        // VALIDASI KEAMANAN DASAR: Pastikan query hanya diawali dengan SELECT
        $queryUpper = strtoupper(trim($query));
        if (strpos($queryUpper, 'SELECT') !== 0) {
            return response()->json([
                'success' => false,
                'error' => 'Keamanan: Hanya operasi SELECT yang diizinkan.'
            ], 403);
        }

        // Mencegah multiple statements yang berpotensi menyisipkan perintah DROP/UPDATE
        if (strpos($query, ';') !== false && strpos($query, ';') !== strlen(trim($query)) - 1) {
             return response()->json([
                'success' => false,
                'error' => 'Keamanan: Multiple statement SQL tidak diizinkan.'
            ], 403);
        }

        try {
            // Mengeksekusi query. DB::select() secara inheren lebih aman untuk baca data.
            $results = DB::select($query);

            return response()->json([
                'success' => true,
                'count' => count($results),
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
