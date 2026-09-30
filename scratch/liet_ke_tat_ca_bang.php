<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
echo "=== ALL TABLES IN shopkaratedo ===\n";
foreach ($tables as $t) {
    foreach ($t as $k => $v) {
        echo "- $v\n";
    }
}
