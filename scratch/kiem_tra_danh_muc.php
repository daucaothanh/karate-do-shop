<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "=== Current Categories ===\n";
foreach (Category::all() as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name} | Slug: {$cat->slug} | Products: {$cat->products()->count()}\n";
}

echo "\n=== Current Products ===\n";
foreach (Product::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Category ID: {$p->category_id} | Slug: {$p->slug}\n";
}
