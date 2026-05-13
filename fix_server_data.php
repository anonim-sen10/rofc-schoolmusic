<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MusicClass;
use App\Models\Schedule;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

try {
    echo "Starting server-side class deduplication...\n";
    
    // Disable FK checks to be safe during movement
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');

    $classes = MusicClass::all()->groupBy('name');

    foreach ($classes as $name => $duplicates) {
        if ($duplicates->count() > 1) {
            echo "Merging duplicates for: $name\n";
            $mainClass = $duplicates->first();
            $others = $duplicates->slice(1);

            foreach ($others as $other) {
                echo " - Merging ID {$other->id} into ID {$mainClass->id}\n";
                
                // Move schedules
                Schedule::where('class_id', $other->id)->update(['class_id' => $mainClass->id]);

                // Move teacher associations to pivot table
                $teacherIds = DB::table('class_teacher')->where('class_id', $other->id)->pluck('teacher_id');
                $mainClass->teachers()->syncWithoutDetaching($teacherIds);
                
                // Also add the primary teacher of the duplicate class
                if ($other->teacher_id) {
                    $mainClass->teachers()->syncWithoutDetaching([$other->teacher_id]);
                }

                // Delete the duplicate class
                $other->delete();
            }
        }
    }

    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    echo "Deduplication complete! All double classes have been merged.\n";

} catch (\Exception $e) {
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    echo "Error: " . $e->getMessage() . "\n";
}
