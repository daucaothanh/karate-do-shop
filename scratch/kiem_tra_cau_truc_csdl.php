<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== payments table ===\n";
foreach (DB::select('DESCRIBE payments') as $col) {
    echo "{$col->Field} | {$col->Type} | {$col->Null} | {$col->Default}\n";
}

echo "\n=== orders table ===\n";
foreach (DB::select('DESCRIBE orders') as $col) {
    echo "{$col->Field} | {$col->Type} | {$col->Null} | {$col->Default}\n";
}

echo "\n=== shipping_addresses table ===\n";
foreach (DB::select('DESCRIBE shipping_addresses') as $col) {
    echo "{$col->Field} | {$col->Type} | {$col->Null} | {$col->Default}\n";
}

echo "\n=== order_items table ===\n";
foreach (DB::select('DESCRIBE order_items') as $col) {
    echo "{$col->Field} | {$col->Type} | {$col->Null} | {$col->Default}\n";
}
