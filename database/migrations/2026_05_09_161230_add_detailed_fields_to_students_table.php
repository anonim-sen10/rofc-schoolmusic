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
        Schema::table('students', function (Blueprint $table) {
            $table->string('nama_panggilan', 80)->nullable()->after('name');
            $table->string('jenis_kelamin', 20)->nullable()->after('nama_panggilan');
            $table->string('tempat_lahir', 120)->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('kewarganegaraan', 120)->nullable()->after('tanggal_lahir');
            $table->string('nama_ortu', 120)->nullable()->after('address');
            $table->string('pekerjaan_ortu', 120)->nullable()->after('nama_ortu');
            $table->string('no_hp_ortu', 30)->nullable()->after('pekerjaan_ortu');
            $table->string('email_ortu', 120)->nullable()->after('no_hp_ortu');
            $table->json('program_tambahan')->nullable()->after('class_id');
            $table->boolean('pengalaman')->default(false)->after('program_tambahan');
            $table->text('deskripsi_pengalaman')->nullable()->after('pengalaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'nama_panggilan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 
                'kewarganegaraan', 'nama_ortu', 'pekerjaan_ortu', 'no_hp_ortu', 
                'email_ortu', 'program_tambahan', 'pengalaman', 'deskripsi_pengalaman'
            ]);
        });
    }
};
