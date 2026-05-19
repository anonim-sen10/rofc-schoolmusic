<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Copy existing teacher assignments to pivot table
        $classes = DB::table('classes')->whereNotNull('teacher_id')->get();

        foreach ($classes as $class) {
            $exists = DB::table('class_teacher')
                ->where('class_id', $class->id)
                ->where('teacher_id', $class->teacher_id)
                ->exists();

            if (!$exists) {
                // Ensure teacher exists to prevent foreign key errors
                $teacherExists = DB::table('teachers')->where('id', $class->teacher_id)->exists();
                if ($teacherExists) {
                    DB::table('class_teacher')->insert([
                        'class_id' => $class->id,
                        'teacher_id' => $class->teacher_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Down migration not required or safe
    }
};
