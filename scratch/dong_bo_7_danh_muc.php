<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "Synchronizing categories...\n";

// Target categories defined by user:
$targetCategories = [
    [
        'name' => 'Võ phục Karate-Do',
        'slug' => 'vo-phuc-karate-do',
        'description' => 'Võ phục Kata, Kumite, Nhập môn tiêu chuẩn WKF từ Shureido, Tokaido, Rikaido, Bushido.',
        'image' => 'assets/clients/img/karate/vo-phuc-rikaido.png',
    ],
    [
        'name' => 'Đai Karate-Do',
        'slug' => 'dai-karate-do',
        'description' => 'Đai đen thêu chữ Nhật Kanji, đai màu Kyu cấp 1 đến cấp 10 chính hãng SMAI, Tokaido, Bushido.',
        'image' => 'assets/clients/img/karate/dai-den-smai.png',
    ],
    [
        'name' => 'Bảo hộ Karate-Do',
        'slug' => 'bao-ho-karate-do',
        'description' => 'Găng tay Kumite, giáp thân bảo vệ ngực bụng, bảo hộ cẳng chân, bảo hộ mu bàn chân tiêu chuẩn WKF.',
        'image' => 'assets/clients/img/karate/gang-tay-kumite.png',
    ],
    [
        'name' => 'Dụng cụ tập luyện',
        'slug' => 'dung-cu-tap-luyen',
        'description' => 'Trụ nộm đấm đá, bao cát tập quyền, đích đấm đỡ makiwara, dây đàn hồi phát lực Karate.',
        'image' => 'assets/clients/img/karate/tru-dam-budo.png',
    ],
    [
        'name' => 'Phụ kiện Karate-Do',
        'slug' => 'phu-kien-karate-do',
        'description' => 'Huy hiệu thêu môn phái Suzucho Karate-Do, dây buộc tóc, móc khóa đai đen, túi thể thao võ đường.',
        'image' => 'assets/clients/img/karate/huy-hieu-suzucho.png',
    ],
    [
        'name' => 'Đồ thi đấu Karate-Do',
        'slug' => 'do-thi-dau-karate-do',
        'description' => 'Mũ giáp bảo hộ có mặt nạ trong suốt WKF, bịt răng silicone chống chấn động, giáp ngực thi đấu WKF.',
        'image' => 'assets/clients/img/karate/mu-giap-karate.png',
    ],
    [
        'name' => 'Trang phục & phụ kiện CLB',
        'slug' => 'trang-phuc-phu-kien-clb',
        'description' => 'Áo thun đồng phục Dojo, áo khoác gió đội tuyển Karate, thảm xốp ghép sân tập, băng rôn cờ lưu niệm võ đường.',
        'image' => 'assets/clients/img/karate/dung-cu-dojo.png',
    ],
];

// Temporarily clear foreign keys or update products first
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Delete old categories and insert the new 7
Category::truncate();

$catMap = [];
foreach ($targetCategories as $item) {
    $cat = Category::create($item);
    $catMap[$cat->slug] = $cat->id;
    echo "Created Category: [ID: {$cat->id}] {$cat->name} (Slug: {$cat->slug})\n";
}

// Remap products to the new category IDs
$productMapping = [
    'vo-phuc-karate-kata-cao-cap-rikaido-master' => 'vo-phuc-karate-do',
    'vo-phuc-karate-thi-dau-kumite-bushido-wkf' => 'vo-phuc-karate-do',
    'vo-phuc-quyen-fujido-kata-master-wkf' => 'vo-phuc-karate-do',
    'vo-phuc-karate-bushido-nhap-mon-kem-dai-trang' => 'vo-phuc-karate-do',
    'dai-den-smai-karate-wkf-approved-4-5cm' => 'dai-karate-do',
    'dai-den-bushido-theu-chu-nhat-ban-kanji-kata-obi' => 'dai-karate-do',
    'gang-tay-thi-dau-kumite-bushido-wkf-approved' => 'bao-ho-karate-do',
    'bo-bao-ho-cang-chan-va-mu-ban-chan-fujido-wkf' => 'bao-ho-karate-do',
    'tru-nom-nguoi-tap-dam-da-vo-thuat-chuyen-nghiep-budo' => 'dung-cu-tap-luyen',
    'huy-hieu-theu-suzucho-karate-do-chinh-hang' => 'phu-kien-karate-do',
    'mu-giap-bao-ho-dau-mat-thi-dau-karate-co-kinh-chan' => 'do-thi-dau-karate-do',
    'trang-thiet-bi-phong-tap-dojo-dung-cu-huan-luyen-fujido' => 'trang-phuc-phu-kien-clb',
];

foreach (Product::all() as $prod) {
    $targetSlug = $productMapping[$prod->slug] ?? null;
    if (!$targetSlug) {
        if (strpos($prod->slug, 'vo-phuc') !== false) $targetSlug = 'vo-phuc-karate-do';
        elseif (strpos($prod->slug, 'dai') !== false) $targetSlug = 'dai-karate-do';
        elseif (strpos($prod->slug, 'gang') !== false || strpos($prod->slug, 'chan') !== false) $targetSlug = 'bao-ho-karate-do';
        elseif (strpos($prod->slug, 'mu-giap') !== false || strpos($prod->slug, 'thi-dau') !== false) $targetSlug = 'do-thi-dau-karate-do';
        elseif (strpos($prod->slug, 'huy-hieu') !== false) $targetSlug = 'phu-kien-karate-do';
        elseif (strpos($prod->slug, 'tru') !== false || strpos($prod->slug, 'dam') !== false) $targetSlug = 'dung-cu-tap-luyen';
        else $targetSlug = 'trang-phuc-phu-kien-clb';
    }

    $newCatId = $catMap[$targetSlug] ?? 1;
    $prod->update(['category_id' => $newCatId]);
    echo "Mapped Product [{$prod->name}] -> Category ID {$newCatId} ({$targetSlug})\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "\nSummary of Categories & Product Counts:\n";
foreach (Category::all() as $c) {
    echo "- {$c->name}: {$c->products()->count()} sản phẩm (Slug: {$c->slug})\n";
}
