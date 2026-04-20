<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'nama_lengkap')) {
                $table->string('nama_lengkap')->nullable()->after('id');
            }

            if (! Schema::hasColumn('registrations', 'nama_panggilan')) {
                $table->string('nama_panggilan')->nullable()->after('nama_lengkap');
            }

            if (! Schema::hasColumn('registrations', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable()->after('nama_panggilan');
            }

            if (! Schema::hasColumn('registrations', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            }

            if (! Schema::hasColumn('registrations', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }

            if (! Schema::hasColumn('registrations', 'kewarganegaraan')) {
                $table->string('kewarganegaraan')->nullable()->after('tanggal_lahir');
            }

            if (! Schema::hasColumn('registrations', 'alamat')) {
                $table->text('alamat')->nullable()->after('kewarganegaraan');
            }

            if (! Schema::hasColumn('registrations', 'no_hp_siswa')) {
                $table->string('no_hp_siswa', 30)->nullable()->after('alamat');
            }

            if (! Schema::hasColumn('registrations', 'nama_ortu')) {
                $table->string('nama_ortu')->nullable()->after('email');
            }

            if (! Schema::hasColumn('registrations', 'pekerjaan_ortu')) {
                $table->string('pekerjaan_ortu')->nullable()->after('nama_ortu');
            }

            if (! Schema::hasColumn('registrations', 'no_hp_ortu')) {
                $table->string('no_hp_ortu', 30)->nullable()->after('pekerjaan_ortu');
            }

            if (! Schema::hasColumn('registrations', 'email_ortu')) {
                $table->string('email_ortu')->nullable()->after('no_hp_ortu');
            }

            if (! Schema::hasColumn('registrations', 'instrumen')) {
                $table->string('instrumen')->nullable()->after('email_ortu');
            }

            if (! Schema::hasColumn('registrations', 'program_tambahan')) {
                $table->json('program_tambahan')->nullable()->after('instrumen');
            }

            if (! Schema::hasColumn('registrations', 'hari_pilihan')) {
                $table->json('hari_pilihan')->nullable()->after('program_tambahan');
            }

            if (! Schema::hasColumn('registrations', 'pengalaman')) {
                $table->boolean('pengalaman')->default(false)->after('hari_pilihan');
            }

            if (! Schema::hasColumn('registrations', 'deskripsi_pengalaman')) {
                $table->text('deskripsi_pengalaman')->nullable()->after('pengalaman');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $columns = [
                'nama_lengkap',
                'nama_panggilan',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'kewarganegaraan',
                'alamat',
                'no_hp_siswa',
                'nama_ortu',
                'pekerjaan_ortu',
                'no_hp_ortu',
                'email_ortu',
                'instrumen',
                'program_tambahan',
                'hari_pilihan',
                'pengalaman',
                'deskripsi_pengalaman',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
