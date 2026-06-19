<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $duplicates = \Illuminate\Support\Facades\DB::table('schedule_sessions')
            ->select('student_id', 'session_date', 'time', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->where('status', 'booked')
            ->groupBy('student_id', 'session_date', 'time')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $sessions = \Illuminate\Support\Facades\DB::table('schedule_sessions')
                ->where('student_id', $dup->student_id)
                ->where('session_date', $dup->session_date)
                ->where('time', $dup->time)
                ->where('status', 'booked')
                ->orderBy('id', 'asc')
                ->get();

            if ($sessions->count() > 1) {
                $keepId = $sessions->first()->id;

                \Illuminate\Support\Facades\DB::table('schedule_sessions')
                    ->where('student_id', $dup->student_id)
                    ->where('session_date', $dup->session_date)
                    ->where('time', $dup->time)
                    ->where('status', 'booked')
                    ->where('id', '!=', $keepId)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
