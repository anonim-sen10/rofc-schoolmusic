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

        // Drop indexes using raw SQL one by one so they don't break the whole process
        $indexes = [
            'schedules_class_id_day_time_unique',
            'schedules_class_day_time_unique'
        ];

        foreach ($indexes as $index) {
            try {
                DB::statement("ALTER TABLE schedules DROP INDEX $index");
            } catch (\Exception $e) {
                // Skip if doesn't exist
            }
        }

        // Also try dropping by column-based name if needed
        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropUnique(['class_id', 'day', 'time']);
            });
        } catch (\Exception $e) {
            // Skip
        }

        // Add new unique index including teacher_id
        Schema::table('schedules', function (Blueprint $table) {
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
