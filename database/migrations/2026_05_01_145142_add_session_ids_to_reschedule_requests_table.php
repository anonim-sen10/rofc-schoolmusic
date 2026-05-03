<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reschedule_requests', function (Blueprint $table) {
            $table->foreignId('old_session_id')->nullable()->after('old_schedule_id')->constrained('schedule_sessions')->onDelete('cascade');
            $table->foreignId('new_session_id')->nullable()->after('new_schedule_id')->constrained('schedule_sessions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reschedule_requests', function (Blueprint $table) {
            $table->dropForeign(['old_session_id']);
            $table->dropForeign(['new_session_id']);
            $table->dropColumn(['old_session_id', 'new_session_id']);
        });
    }
};
