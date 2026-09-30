<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Altering payments table payment_method column...\n";

DB::statement("ALTER TABLE `payments` MODIFY COLUMN `payment_method` VARCHAR(100) NOT NULL DEFAULT 'COD';");

echo "Altered payment_method successfully to VARCHAR(100)!\n";
