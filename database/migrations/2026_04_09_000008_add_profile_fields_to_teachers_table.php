<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('name');
            $table->text('address')->nullable()->after('phone');
            $table->string('gender', 20)->nullable()->after('address');
            $table->string('religion', 30)->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'gender', 'religion']);
        });
    }
};
