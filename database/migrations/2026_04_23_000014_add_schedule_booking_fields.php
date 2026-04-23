<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('schedules') && ! Schema::hasColumn('schedules', 'status')) {
            Schema::table('schedules', function (Blueprint $table): void {
                $table->enum('status', ['available', 'booked'])->default('available')->after('teacher_id');
            });
        }

        if (Schema::hasTable('registrations') && ! Schema::hasColumn('registrations', 'schedule_id')) {
            Schema::table('registrations', function (Blueprint $table): void {
                $table->foreignId('schedule_id')->nullable()->after('class_id')->constrained('schedules')->nullOnDelete();
            });
        }

        if (Schema::hasTable('students') && ! Schema::hasColumn('students', 'schedule_id')) {
            Schema::table('students', function (Blueprint $table): void {
                $table->foreignId('schedule_id')->nullable()->after('email')->constrained('schedules')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'schedule_id')) {
            Schema::table('students', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('schedule_id');
            });
        }

        if (Schema::hasTable('registrations') && Schema::hasColumn('registrations', 'schedule_id')) {
            Schema::table('registrations', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('schedule_id');
            });
        }

        if (Schema::hasTable('schedules') && Schema::hasColumn('schedules', 'status')) {
            Schema::table('schedules', function (Blueprint $table): void {
                $table->dropColumn('status');
            });
        }
    }
};
