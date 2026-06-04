<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$teachers = \App\Models\Teacher::all();
foreach($teachers as $t) {
    echo "ID: " . $t->id . " Name: " . $t->name . "\n";
}
