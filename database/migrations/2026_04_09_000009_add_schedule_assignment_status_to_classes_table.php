<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->enum('assignment_status', ['pending', 'accepted', 'rejected'])->default('pending')->after('teacher_id');
            $table->text('assignment_note')->nullable()->after('assignment_status');
            $table->timestamp('assigned_at')->nullable()->after('assignment_note');
            $table->timestamp('responded_at')->nullable()->after('assigned_at');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['assignment_status', 'assignment_note', 'assigned_at', 'responded_at']);
        });
    }
};
