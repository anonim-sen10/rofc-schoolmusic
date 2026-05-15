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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('ig_siswa', 100)->nullable()->after('no_hp_siswa');
            $table->string('ig_ortu', 100)->nullable()->after('no_hp_ortu');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('ig_siswa', 100)->nullable()->after('phone');
            $table->string('ig_ortu', 100)->nullable()->after('no_hp_ortu');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['ig_siswa', 'ig_ortu']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['ig_siswa', 'ig_ortu']);
        });
    }
};
