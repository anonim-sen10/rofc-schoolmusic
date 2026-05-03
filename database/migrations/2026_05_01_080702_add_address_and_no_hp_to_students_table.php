<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('students', 'no_hp')) {
                $table->string('no_hp')->nullable();
            }
            if (!Schema::hasColumn('students', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
            if (Schema::hasColumn('students', 'class_id')) {
                $table->dropConstrainedForeignId('class_id');
            }
            // we don't drop address since it might have already existed
        });
    }
};
