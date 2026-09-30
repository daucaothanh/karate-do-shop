<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Altering order_status_history table...\n";
DB::statement("ALTER TABLE `order_status_history` MODIFY COLUMN `status` VARCHAR(100) NOT NULL;");
echo "Altered order_status_history status column successfully to VARCHAR(100)!\n";
