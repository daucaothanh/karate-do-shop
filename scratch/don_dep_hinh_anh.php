<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

echo "=== 1. Chuẩn bị thư mục Karate & Copy ảnh chuẩn ===\n";

$karateDir = public_path('assets/clients/img/karate');
if (!is_dir($karateDir)) {
    mkdir($karateDir, 0777, true);
}

$map = [
    'assets/clients/img/banner/1.png' => 'assets/clients/img/karate/vo-phuc-rikaido.png',
    'assets/clients/img/banner/2.png' => 'assets/clients/img/karate/bo-do-dau-bushido.png',
    'assets/clients/img/banner/3.png' => 'assets/clients/img/karate/vo-phuc-fujido-kata.png',
    'assets/clients/img/banner/4.png' => 'assets/clients/img/karate/vo-phuc-fujido-blue.png',
    'assets/clients/img/banner/513841876_1189380376321405_1144498992022741687_n.jpg' => 'assets/clients/img/karate/vo-phuc-bushido-kumite.jpg',
    'assets/clients/img/banner/514276949_1193238609268915_1060004173210862688_n.jpg' => 'assets/clients/img/karate/vo-phuc-bushido-nhap-mon.jpg',
    'assets/clients/img/banner/710058987_977910341511683_7087089073048355448_n.jpg' => 'assets/clients/img/karate/dai-den-smai-wkf.jpg',
    'assets/clients/img/banner/46302880_716066718779394_131506443995054080_n.jpg' => 'assets/clients/img/karate/dai-den-bushido-kanji.jpg',
    'assets/clients/img/banner/480867629_1100945021831608_8606318001015126828_n.jpg' => 'assets/clients/img/karate/gang-tay-kumite-bushido.jpg',
    'assets/clients/img/banner/495294678_2428496100869772_2549878525696636235_n.jpg' => 'assets/clients/img/karate/mu-giap-bao-ho-dau.jpg',
    'assets/clients/img/banner/495560451_2429551097430939_3338807772639885995_n.jpg' => 'assets/clients/img/karate/huy-hieu-theu-suzucho.jpg',
    'assets/clients/img/service/2.jpg' => 'assets/clients/img/karate/giap-bao-ho-chan-fujido.jpg',
    'assets/clients/img/service/4.jpg' => 'assets/clients/img/karate/showroom-dung-cu-fujido.jpg',
    'assets/clients/img/service/6.jpg' => 'assets/clients/img/karate/tru-nom-dam-da-budo.jpg',
    'assets/clients/img/service/11.jpg' => 'assets/clients/img/karate/hlv-nghia-dung-karatedo.jpg',
    'assets/clients/img/team/3.jpg' => 'assets/clients/img/karate/vo-sinh-chao-co.jpg',
    'assets/clients/img/team/10.jpg' => 'assets/clients/img/karate/dai-hoi-vo-sinh-danang.jpg',
    'assets/clients/img/slider/13.png' => 'assets/clients/img/karate/cau-lac-bo-karatedo.png',
    'assets/clients/img/slider/14.png' => 'assets/clients/img/karate/ky-thi-thang-dang-2026.png'
];

foreach ($map as $src => $dst) {
    $srcPath = public_path($src);
    $dstPath = public_path($dst);
    if (file_exists($srcPath)) {
        copy($srcPath, $dstPath);
        echo "Copied: $dst\n";
    }
}

echo "\n=== 2. Cập nhật Danh mục trong Database ===\n";

$categories = [
    [
        'name' => 'Võ phục Karate',
        'slug' => 'vo-phuc-karate',
        'description' => 'Võ phục Karate Kumite, Kata chính hãng Rikaido, Bushido, Fujido đạt chuẩn WKF.',
        'image' => 'assets/clients/img/karate/vo-phuc-rikaido.png',
    ],
    [
        'name' => 'Đai võ thuật Karate',
        'slug' => 'dai-vo-thuat-karate',
        'description' => 'Đai trắng, đai màu, đai đen SMAI, Bushido thêu chữ Nhật Bản (Kanji) cao cấp.',
        'image' => 'assets/clients/img/karate/dai-den-smai-wkf.jpg',
    ],
    [
        'name' => 'Giáp & Găng bảo hộ Karate',
        'slug' => 'giap-gang-bao-ho-karate',
        'description' => 'Găng tay Kumite đỏ xanh, bảo hộ cẳng chân, bảo hộ mu bàn chân, mũ giáp bảo hộ mặt.',
        'image' => 'assets/clients/img/karate/gang-tay-kumite-bushido.jpg',
    ],
    [
        'name' => 'Dụng cụ tập luyện & Phòng tập',
        'slug' => 'binh-khi-dung-cu-tap-luyen',
        'description' => 'Trụ nộm người silicon tập đấm đá võ thuật Budo, đích đấm, bia đỡ đòn phản xạ.',
        'image' => 'assets/clients/img/karate/tru-nom-dam-da-budo.jpg',
    ],
    [
        'name' => 'Phụ kiện & Huy hiệu Karate',
        'slug' => 'phu-kien-huy-hieu-karate',
        'description' => 'Huy hiệu thêu Suzucho Karate-Do, logo phân đường, móc khóa võ thuật, túi đựng võ phục.',
        'image' => 'assets/clients/img/karate/huy-hieu-theu-suzucho.jpg',
    ],
];

$catMap = [];
foreach ($categories as $catData) {
    $c = Category::updateOrCreate(
        ['slug' => $catData['slug']],
        $catData
    );
    $catMap[$catData['slug']] = $c->id;
    echo "Category: [{$c->name}] (ID: {$c->id}) -> {$c->image}\n";
}

echo "\n=== 3. Cập nhật Sản phẩm chuẩn Karate trong Database ===\n";

$productsData = [
    [
        'name' => 'Võ phục Karate Kata cao cấp Rikaido Master (Vải bố đứng form)',
        'slug' => 'vo-phuc-karate-kata-cao-cap-rikaido-master',
        'category_id' => $catMap['vo-phuc-karate'],
        'price' => 750000,
        'quantity' => 45,
        'image' => 'assets/clients/img/karate/vo-phuc-rikaido.png',
        'unit' => 'Bộ',
        'description' => 'Võ phục Kata chuyên dụng thương hiệu Rikaido, vải dày đứng form chuẩn thi đấu WKF, tạo tiếng đanh giòn khi ra đòn.',
        'content' => 'Võ phục Rikaido Master được may từ chất liệu vải bố cotton 100% cao cấp, độ dày 12oz, cắt may theo tiêu chuẩn Kata Nhật Bản. Form áo rộng rãi, cổ đứng vững chãi giúp võ sinh thực hiện các bài quyền dứt khoát và đẹp mắt.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/vo-phuc-fujido-blue.png',
            'assets/clients/img/karate/vo-phuc-fujido-kata.png'
        ]
    ],
    [
        'name' => 'Võ phục Karate thi đấu Kumite Bushido siêu nhẹ WKF Approved',
        'slug' => 'vo-phuc-karate-thi-dau-kumite-bushido-wkf',
        'category_id' => $catMap['vo-phuc-karate'],
        'price' => 850000,
        'quantity' => 60,
        'image' => 'assets/clients/img/karate/vo-phuc-bushido-kumite.jpg',
        'unit' => 'Bộ',
        'description' => 'Võ phục thi đấu đối kháng Kumite cao cấp Bushido, công nghệ dệt tổ ong thoáng khí siêu nhẹ, co giãn thoải mái.',
        'content' => 'Trang phục Kumite Bushido đạt chuẩn thi đấu Liên đoàn Karate Thế giới (WKF Approved). Vải polyester siêu nhẹ, thấm hút mồ hôi cực nhanh, hỗ trợ di chuyển linh hoạt và tung đòn chuẩn xác.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/bo-do-dau-bushido.png',
            'assets/clients/img/karate/gang-tay-kumite-bushido.jpg'
        ]
    ],
    [
        'name' => 'Võ phục Quyền Fujido Kata Master WKF (Tiêu chuẩn Đội tuyển)',
        'slug' => 'vo-phuc-quyen-fujido-kata-master-wkf',
        'category_id' => $catMap['vo-phuc-karate'],
        'price' => 1250000,
        'quantity' => 30,
        'image' => 'assets/clients/img/karate/vo-phuc-fujido-kata.png',
        'unit' => 'Bộ',
        'description' => 'Dòng võ phục quyền Kata đỉnh cao thương hiệu Fujido, dệt từ sợi bông cao cấp Nhật Bản, đứng dáng hoàn hảo.',
        'content' => 'Fujido Kata Master là sự lựa chọn hàng đầu của các vận động viên đội tuyển quốc gia. Đường may tỉ mỉ 8 hàng chỉ ở viền ống tay và gấu áo tạo độ giòn tuyệt hảo trong từng bài quyền.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/vo-phuc-fujido-blue.png',
            'assets/clients/img/karate/showroom-dung-cu-fujido.jpg'
        ]
    ],
    [
        'name' => 'Võ phục Karate Bushido Nhập môn kèm Đai trắng tiêu chuẩn',
        'slug' => 'vo-phuc-karate-bushido-nhap-mon-kem-dai-trang',
        'category_id' => $catMap['vo-phuc-karate'],
        'price' => 280000,
        'quantity' => 120,
        'image' => 'assets/clients/img/karate/vo-phuc-bushido-nhap-mon.jpg',
        'unit' => 'Bộ',
        'description' => 'Bộ võ phục Karate dành cho võ sinh mới tập luyện, chất vải kaki mềm mát, bền bỉ, tặng kèm đai trắng.',
        'content' => 'Võ phục nhập môn Bushido phù hợp cho cả trẻ em và người lớn mới bắt đầu học võ thuật Karate. Chất vải mềm nhẹ thấm hút mồ hôi, dễ giặt, form áo chuẩn truyền thống.',
        'status' => 'active',
        'featured' => false,
        'gallery' => [
            'assets/clients/img/karate/vo-phuc-rikaido.png'
        ]
    ],
    [
        'name' => 'Đai đen SMAI Karate WKF Approved (Bản rộng 4.5cm)',
        'slug' => 'dai-den-smai-karate-wkf-approved-4-5cm',
        'category_id' => $catMap['dai-vo-thuat-karate'],
        'price' => 550000,
        'quantity' => 50,
        'image' => 'assets/clients/img/karate/dai-den-smai-wkf.jpg',
        'unit' => 'Sợi',
        'description' => 'Đai đen thương hiệu SMAI quốc tế bản 4.5cm, thêu nhãn WKF Approved, chất vải cotton 100% đầm tay.',
        'content' => 'Đai đen SMAI là dòng đai cao cấp được cấp phép bởi Liên đoàn Karate Thế giới WKF. Bản đai rộng 4.5cm với 12 đường chỉ may chắc chắn, cầm đầm tay và thắt nút đẹp mắt.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/dai-den-bushido-kanji.jpg'
        ]
    ],
    [
        'name' => 'Đai đen Bushido thêu chữ Nhật Bản (Kanji Kata Obi cao cấp)',
        'slug' => 'dai-den-bushido-theu-chu-nhat-ban-kanji-kata-obi',
        'category_id' => $catMap['dai-vo-thuat-karate'],
        'price' => 420000,
        'quantity' => 40,
        'image' => 'assets/clients/img/karate/dai-den-bushido-kanji.jpg',
        'unit' => 'Sợi',
        'description' => 'Đai đen Karate Bushido thêu chữ Kanji tinh xảo sắc nét, vải cotton dầy dặn, độ bền trên 10 năm.',
        'content' => 'Đai đen Bushido Kata Obi thêu chỉ lụa bạc/vàng cao cấp chữ Nhật Bản. Lõi bông ép đanh chắc, không bị mềm nhão sau thời gian dài sử dụng.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/dai-den-smai-wkf.jpg'
        ]
    ],
    [
        'name' => 'Găng tay thi đấu Kumite Bushido WKF Approved (Cặp Xanh/Đỏ)',
        'slug' => 'gang-tay-thi-dau-kumite-bushido-wkf-approved',
        'category_id' => $catMap['giap-gang-bao-ho-karate'],
        'price' => 380000,
        'quantity' => 75,
        'image' => 'assets/clients/img/karate/gang-tay-kumite-bushido.jpg',
        'unit' => 'Cặp',
        'description' => 'Găng tay bảo hộ thi đấu Kumite Karate thương hiệu Bushido, đệm mút đúc PU giảm chấn lực va chạm tối đa.',
        'content' => 'Găng tay Kumite Bushido chuẩn thi đấu quốc tế. Da PU phủ ngoài chống trầy, đệm xốp PU định hình công thái học ôm sát bàn tay, giảm thiểu chấn thương khớp ngón và cổ tay.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/giap-bao-ho-chan-fujido.jpg',
            'assets/clients/img/karate/bo-do-dau-bushido.png'
        ]
    ],
    [
        'name' => 'Mũ giáp bảo hộ đầu mặt thi đấu Karate có kính chắn trong suốt',
        'slug' => 'mu-giap-bao-ho-dau-mat-thi-dau-karate-co-kinh-chan',
        'category_id' => $catMap['giap-gang-bao-ho-karate'],
        'price' => 690000,
        'quantity' => 35,
        'image' => 'assets/clients/img/karate/mu-giap-bao-ho-dau.jpg',
        'unit' => 'Cái',
        'description' => 'Mũ giáp bảo hộ đầu và mặt bằng vật liệu xốp NBR + mặt nạ Mica polycarbonate chịu lực cao, an toàn tuyệt đối.',
        'content' => 'Mũ bảo hộ thi đấu chuyên dụng cho giải đấu Karate trẻ em và đối kháng. Mặt nạ mica trong suốt chống đọng sương, thông thoáng tai và đỉnh đầu, dây dán Velcro điều chỉnh kích cỡ linh hoạt.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/gang-tay-kumite-bushido.jpg'
        ]
    ],
    [
        'name' => 'Bộ bảo hộ cẳng chân và mu bàn chân Fujido WKF (Xanh/Đỏ)',
        'slug' => 'bo-bao-ho-cang-chan-va-mu-ban-chan-fujido-wkf',
        'category_id' => $catMap['giap-gang-bao-ho-karate'],
        'price' => 450000,
        'quantity' => 50,
        'image' => 'assets/clients/img/karate/giap-bao-ho-chan-fujido.jpg',
        'unit' => 'Bộ',
        'description' => 'Bộ giáp bảo hộ ống đồng và mu bàn chân tháo rời tiện lợi, chất liệu mút EVA đàn hồi giảm sốc tối ưu.',
        'content' => 'Bộ bảo hộ chân Fujido gồm 2 phần: ốp ống chân và giáp mu bàn chân nối với nhau bằng băng dính dán chắc chắn. Phù hợp cho tập luyện và thi đấu các giải đấu Karate toàn quốc.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/bo-do-dau-bushido.png'
        ]
    ],
    [
        'name' => 'Huy hiệu thêu Suzucho Karate-Do chính hãng (Logo gắn ngực)',
        'slug' => 'huy-hieu-theu-suzucho-karate-do-chinh-hang',
        'category_id' => $catMap['phu-kien-huy-hieu-karate'],
        'price' => 45000,
        'quantity' => 300,
        'image' => 'assets/clients/img/karate/huy-hieu-theu-suzucho.jpg',
        'unit' => 'Cái',
        'description' => 'Huy hiệu thêu logo phân phái Suzucho Karatedo Nhật Bản, đường thêu viền nổi mật độ cao sắc sảo.',
        'content' => 'Huy hiệu thêu tay nghề cao biểu tượng hoa mai và nắm đấm Karate-do. Đường thêu chỉ kim tuyến bóng sáng, mặt sau phủ keo ủi nhiệt hoặc may trực tiếp lên ngực áo võ phục.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/dai-hoi-vo-sinh-danang.jpg'
        ]
    ],
    [
        'name' => 'Trụ nộm người tập đấm đá võ thuật chuyên nghiệp BUDO Heavy Duty',
        'slug' => 'tru-nom-nguoi-tap-dam-da-vo-thuat-chuyen-nghiep-budo',
        'category_id' => $catMap['binh-khi-dung-cu-tap-luyen'],
        'price' => 4850000,
        'quantity' => 15,
        'image' => 'assets/clients/img/karate/tru-nom-dam-da-budo.jpg',
        'unit' => 'Bộ',
        'description' => 'Nộm người tập võ Silicon đàn hồi cao cấp, thân hình người thực tế, đế hút chân không siêu bám sàn.',
        'content' => 'Trụ đấm nộm người BUDO giúp võ sinh luyện tập đòn đánh chính xác vào các huyệt đạo và cự ly đối kháng thực chiến. Đế chứa nước/cát tải trọng lên đến 150kg chống đổ ngã.',
        'status' => 'active',
        'featured' => true,
        'gallery' => [
            'assets/clients/img/karate/showroom-dung-cu-fujido.jpg'
        ]
    ],
    [
        'name' => 'Trang thiết bị phòng tập Dojo & Dụng cụ huấn luyện Karate Fujido',
        'slug' => 'trang-thiet-bi-phong-tap-dojo-dung-cu-huan-luyen-fujido',
        'category_id' => $catMap['binh-khi-dung-cu-tap-luyen'],
        'price' => 1800000,
        'quantity' => 20,
        'image' => 'assets/clients/img/karate/showroom-dung-cu-fujido.jpg',
        'unit' => 'Gói',
        'description' => 'Combo trang bị võ đường gồm đích đá cầm tay, giáp bảo hộ ngực, bia tập đấm phản xạ cao cấp.',
        'content' => 'Gói thiết bị võ đường chuyên nghiệp Fujido hỗ trợ tối đa cho huấn luyện viên trong các buổi lên lớp và rèn luyện thể lực, tốc độ cho môn sinh.',
        'status' => 'active',
        'featured' => false,
        'gallery' => [
            'assets/clients/img/karate/tru-nom-dam-da-budo.jpg'
        ]
    ]
];

// Delete old template products with invalid images
Product::whereNotIn('slug', array_column($productsData, 'slug'))->delete();

foreach ($productsData as $pData) {
    $gallery = $pData['gallery'];
    unset($pData['gallery']);

    $prod = Product::updateOrCreate(
        ['slug' => $pData['slug']],
        $pData
    );

    // Update gallery
    ProductImage::where('product_id', $prod->id)->delete();
    foreach ($gallery as $gImg) {
        ProductImage::create([
            'product_id' => $prod->id,
            'image' => $gImg
        ]);
    }

    echo "Saved Product: [{$prod->name}]\n";
}

echo "\n=== 4. Xóa triệt để các file ảnh rau củ / hoa quả / nông sản không liên quan ===\n";

$deleteDirs = [
    public_path('assets/clients/img/product'),
    public_path('assets/clients/img/gallery'),
    public_path('assets/clients/img/others'),
    public_path('assets/clients/img/testimonial'),
    public_path('assets/clients/img/img-slide'),
];

foreach ($deleteDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $f) {
            if (is_file($f)) {
                @unlink($f);
            }
        }
    }
}

// Clean vegetable icons in icons/icon-img/
$iconImgDir = public_path('assets/clients/img/icons/icon-img');
if (is_dir($iconImgDir)) {
    $vegetableIcons = ['category-1.png', 'category-2.png', 'category-3.png', 'category-4.png', 'category-5.png', '3.jpg', '4.jpg', '5.jpg'];
    foreach ($vegetableIcons as $vi) {
        $file = $iconImgDir . '/' . $vi;
        if (file_exists($file)) {
            @unlink($file);
        }
    }
}

// Clean vegetable sliders in slider/
$sliderDir = public_path('assets/clients/img/slider');
if (is_dir($sliderDir)) {
    $vegetableSliders = ['1.jpg', '2.jpg', '11.jpg', '12.jpg', '21.png', '22.png', '23.png', '41.jpg', '61.jpg', '62.jpg'];
    foreach ($vegetableSliders as $vs) {
        $file = $sliderDir . '/' . $vs;
        if (file_exists($file)) {
            @unlink($file);
        }
    }
}

// Clean banner template images
$bannerDir = public_path('assets/clients/img/banner');
if (is_dir($bannerDir)) {
    $templateBanners = ['1.jpg', '2.jpg', '3.jpg', '11.png', '13.png', '14.png', '15.png', 'menu-banner-1.png'];
    foreach ($templateBanners as $tb) {
        $file = $bannerDir . '/' . $tb;
        if (file_exists($file)) {
            @unlink($file);
        }
    }
}

echo "\n=== HOÀN TẤT 100%! TẤT CẢ DANH MỤC & SẢN PHẨM ĐỀU SỬ DỤNG ẢNH KARATE CHUẨN XỊN! ===\n";
