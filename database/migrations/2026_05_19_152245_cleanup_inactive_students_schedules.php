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
        // 1. Find all inactive student IDs
        $inactiveStudentIds = \DB::table('students')->where('is_active', false)->pluck('id');

        if ($inactiveStudentIds->isNotEmpty()) {
            // 2. Release schedules booked by these students
            \DB::table('schedules')
                ->whereIn('student_id', $inactiveStudentIds)
                ->update([
                    'student_id' => null,
                    'status' => 'available'
                ]);

            // 3. Delete future booked sessions for these students (on or after today)
            \DB::table('schedule_sessions')
                ->whereIn('student_id', $inactiveStudentIds)
                ->where('status', 'booked')
                ->where('session_date', '>=', now()->toDateString())
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
