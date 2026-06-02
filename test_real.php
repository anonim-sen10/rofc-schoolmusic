<?php
require 'vendor/autoload.php';
$now = \Carbon\Carbon::parse('2026-06-08'); // Monday
echo 'Next Sunday (using 0): ' . $now->copy()->next(\Carbon\Carbon::SUNDAY)->toDateString() . PHP_EOL;
echo 'Next Sunday (using 7): ' . $now->copy()->next(7)->toDateString() . PHP_EOL;
