<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Product;

echo "Adding columns if missing...\n";

Schema::table('products', function (Blueprint $table) {
    if (!Schema::hasColumn('products', 'colors')) {
        $table->string('colors')->nullable()->after('unit');
    }
    if (!Schema::hasColumn('products', 'sizes')) {
        $table->string('sizes')->nullable()->after('colors');
    }
});

Schema::table('cart_items', function (Blueprint $table) {
    if (!Schema::hasColumn('cart_items', 'color')) {
        $table->string('color')->nullable()->after('quantity');
    }
    if (!Schema::hasColumn('cart_items', 'size')) {
        $table->string('size')->nullable()->after('color');
    }
});

Schema::table('order_items', function (Blueprint $table) {
    if (!Schema::hasColumn('order_items', 'color')) {
        $table->string('color')->nullable()->after('price');
    }
    if (!Schema::hasColumn('order_items', 'size')) {
        $table->string('size')->nullable()->after('color');
    }
});

echo "Columns checked/added successfully!\n";

// Update colors and sizes for all products based on their category
$products = Product::all();
foreach ($products as $p) {
    $slug = $p->slug;
    $colors = 'Trắng';
    $sizes = 'S, M, L, XL';

    if (strpos($slug, 'gang-tay') !== false || strpos($slug, 'gang') !== false) {
        $colors = 'Đỏ, Xanh dương, Trắng, Đen';
        $sizes = 'S, M, L';
    } elseif (strpos($slug, 'dai-') !== false || strpos($slug, 'dai') !== false) {
        $colors = 'Đen, Đỏ, Xanh dương, Xanh lá, Nâu, Vàng, Cam, Trắng';
        $sizes = '240cm, 260cm, 280cm, 300cm';
    } elseif (strpos($slug, 'giap-bao-ho-chan') !== false || strpos($slug, 'bao-ho-cang-chan') !== false) {
        $colors = 'Đỏ, Xanh dương, Trắng';
        $sizes = 'S, M, L, XL';
    } elseif (strpos($slug, 'mu-giap') !== false) {
        $colors = 'Trắng, Đỏ, Xanh dương';
        $sizes = 'S, M, L';
    } elseif (strpos($slug, 'vo-phuc') !== false) {
        $colors = 'Trắng, Đen, Xanh dương';
        $sizes = 'Số 3 (1m40-1m50), Số 4 (1m50-1m60), Số 5 (1m60-1m70), Số 6 (1m70-1m80), Số 7 (Trên 1m80)';
    } elseif (strpos($slug, 'huy-hieu') !== false) {
        $colors = 'Tiêu chuẩn Suzucho, Viền Đỏ, Viền Vàng';
        $sizes = 'Tiêu chuẩn';
    } elseif (strpos($slug, 'tru-nom') !== false) {
        $colors = 'Đỏ, Đen, Xanh';
        $sizes = 'Cao 1m70, Cao 1m80';
    }

    $p->update([
        'colors' => $colors,
        'sizes' => $sizes
    ]);
    echo "Updated Product [{$p->name}]: Colors: {$colors} | Sizes: {$sizes}\n";
}

echo "All products updated with colors and sizes!\n";
