<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Product::all() as $p) {
    echo "ID: {$p->id} | Slug: {$p->slug} | Colors: {$p->colors} | Sizes: {$p->sizes}\n";
}
