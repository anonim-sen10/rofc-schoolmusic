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
        $saddam = \Illuminate\Support\Facades\DB::table('students')
            ->where('name', 'like', '%saddam%')
            ->first();

        if ($saddam) {
            \Illuminate\Support\Facades\DB::table('schedule_sessions')
                ->where('student_id', $saddam->id)
                ->where('status', 'booked')
                ->delete();
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
