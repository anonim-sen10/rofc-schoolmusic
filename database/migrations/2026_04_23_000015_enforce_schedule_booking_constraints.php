<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('schedules')) {
            try {
                Schema::table('schedules', function (Blueprint $table): void {
                    $table->unique(['class_id', 'day', 'time'], 'schedules_class_day_time_unique');
                });
            } catch (\Throwable $exception) {
                // Index may already exist in previous schema versions.
            }
        }

        if (Schema::hasTable('students') && Schema::hasColumn('students', 'schedule_id')) {
            try {
                Schema::table('students', function (Blueprint $table): void {
                    $table->unique('schedule_id', 'students_schedule_id_unique');
                });
            } catch (\Throwable $exception) {
                // Existing data/index may already satisfy or block this constraint.
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students')) {
            try {
                Schema::table('students', function (Blueprint $table): void {
                    $table->dropUnique('students_schedule_id_unique');
                });
            } catch (\Throwable $exception) {
                // Ignore when index does not exist.
            }
        }

        if (Schema::hasTable('schedules')) {
            try {
                Schema::table('schedules', function (Blueprint $table): void {
                    $table->dropUnique('schedules_class_day_time_unique');
                });
            } catch (\Throwable $exception) {
                // Ignore when index does not exist.
            }
        }
    }
};
