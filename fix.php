<?php

$models = ['Student.php', 'Registration.php', 'Schedule.php', 'Attendance.php'];
foreach ($models as $model) {
    $path = 'app/Models/' . $model;
    $content = file_get_contents($path);
    $content = str_replace('public function class():', 'public function musicClass():', $content);
    file_put_contents($path, $content);
}

function searchAndReplace($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->getExtension() == 'php') {
            $content = file_get_contents($file->getPathname());
            $original = $content;
            
            // Replace with('class') or with(['class', ...])
            $content = preg_replace("/with\(\s*(['\"])class\\1\s*\)/", "with($1musicClass$1)", $content);
            $content = preg_replace("/with\(\s*\[([^\]]*)['\"]class['\"]([^\]]*)\]\s*\)/", "with([$1'musicClass'$2])", $content);
            
            // Replace $var->class->name with $var->musicClass->name
            // We'll just replace ->class-> with ->musicClass-> 
            // and ->class?-> with ->musicClass?->
            $content = preg_replace('/->class(->|\?->)/', '->musicClass$1', $content);
            
            if ($original !== $content) {
                file_put_contents($file->getPathname(), $content);
                echo "Updated: " . $file->getPathname() . "\n";
            }
        }
    }
}

searchAndReplace('app/Http/Controllers');
searchAndReplace('resources/views');
