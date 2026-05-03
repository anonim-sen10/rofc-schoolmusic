<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('email');
            $table->integer('duration_months')->nullable()->after('start_date');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('address');
            $table->integer('duration_months')->nullable()->after('start_date');
            $table->date('end_date')->nullable()->after('duration_months');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'duration_months']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'duration_months', 'end_date']);
        });
    }
};
