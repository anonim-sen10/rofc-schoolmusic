<?php
$f = 'app/Http/Controllers/Student/StudentPortalController.php';
file_put_contents($f, str_replace('->class()->', '->musicClass()->', file_get_contents($f)));

$f = 'resources/views/portal/super-admin/module.blade.php';
file_put_contents($f, str_replace('->class)', '->musicClass)', file_get_contents($f)));

$f = 'resources/views/portal/teacher/materials.blade.php';
file_put_contents($f, str_replace('->class)', '->musicClass)', file_get_contents($f)));
