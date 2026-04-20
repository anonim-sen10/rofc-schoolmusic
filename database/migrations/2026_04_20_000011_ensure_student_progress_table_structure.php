<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('student_progress')) {
            Schema::create('student_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
                $table->string('topic')->nullable();
                $table->integer('score')->nullable();
                $table->text('note')->nullable();
                $table->date('recorded_at')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('student_progress', function (Blueprint $table) {
            if (! Schema::hasColumn('student_progress', 'student_id')) {
                $table->foreignId('student_id')->nullable()->constrained('students')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('student_progress', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('classes')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('student_progress', 'topic')) {
                $table->string('topic')->nullable();
            }

            if (! Schema::hasColumn('student_progress', 'score')) {
                $table->integer('score')->nullable();
            }

            if (! Schema::hasColumn('student_progress', 'note')) {
                $table->text('note')->nullable();
            }

            if (! Schema::hasColumn('student_progress', 'recorded_at')) {
                $table->date('recorded_at')->nullable();
            }

            if (! Schema::hasColumn('student_progress', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('student_progress', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        if (Schema::hasColumn('student_progress', 'score')) {
            Schema::table('student_progress', function (Blueprint $table) {
                $table->integer('score')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('student_progress') && Schema::hasColumn('student_progress', 'score')) {
            Schema::table('student_progress', function (Blueprint $table) {
                $table->string('score')->nullable()->change();
            });
        }
    }
};
