<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('schedules', function (Blueprint $table) {
            // Drop old unique indexes if they exist
            try {
                $table->dropUnique('schedules_class_id_day_time_unique');
            } catch (\Exception $e) {}
            
            try {
                $table->dropUnique('schedules_class_day_time_unique');
            } catch (\Exception $e) {}

            try {
                $table->dropUnique(['class_id', 'day', 'time']);
            } catch (\Exception $e) {}
            
            // Add new unique index including teacher_id
            $table->unique(['class_id', 'day', 'time', 'teacher_id'], 'schedules_full_unique');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropUnique('schedules_full_unique');
            $table->unique(['class_id', 'day', 'time'], 'schedules_class_day_time_unique');
        });
    }
};
