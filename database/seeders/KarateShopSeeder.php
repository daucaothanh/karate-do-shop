<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\WorkShift;
use Illuminate\Support\Str;

class KarateShopSeeder extends Seeder
{
    /**
     * Seed danh mục và sản phẩm võ thuật Karate-Do phong phú
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Võ phục Karate',
                'slug' => 'vo-phuc-karate',
                'description' => 'Các dòng võ phục Karate cao cấp dành cho luyện tập phong trào và thi đấu Kata, Kumite chuẩn WKF.',
                'image' => 'assets/clients/img/product/1.png',
            ],
            [
                'name' => 'Đai võ thuật Karate',
                'slug' => 'dai-vo-thuat-karate',
                'description' => 'Đai Karate các cấp bậc từ đai trắng, vàng, xanh lá, xanh dương, đỏ, nâu đến đai đen lụa thêu tên cao cấp.',
                'image' => 'assets/clients/img/product/4.png',
            ],
            [
                'name' => 'Giáp & Găng bảo hộ',
                'slug' => 'giap-va-gang-bao-ho',
                'description' => 'Găng tay thi đấu WKF (Đỏ/Xanh), giáp bảo hộ ngực, bảo hộ ống chân, mu bàn chân và bảo hộ hàm.',
                'image' => 'assets/clients/img/product/7.png',
            ],
            [
                'name' => 'Binh khí & Dụng cụ tập',
                'slug' => 'binh-khi-va-dung-cu-tap',
                'description' => 'Bao cát, đích đấm đá, trụ đấm, Makiwara truyền thống, côn nhị khúc, đao kiếm gỗ Kobudo tập luyện.',
                'image' => 'assets/clients/img/product/10.png',
            ],
            [
                'name' => 'Phụ kiện & Túi thể thao',
                'slug' => 'phu-kien-va-tui-the-thao',
                'description' => 'Túi đựng võ phục, balo thể thao Karate-Do, móc khóa võ thuật, băng cuốn cổ tay, huy chương giải đấu.',
                'image' => 'assets/clients/img/product/13.png',
            ],
        ];

        $catMap = [];
        foreach ($categories as $cat) {
            $createdCat = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
            $catMap[$cat['slug']] = $createdCat->id;
        }

        $products = [
            // 1. Võ phục
            [
                'name' => 'Võ phục Karate Shureido New Wave 3 chuẩn thi đấu Kata WKF',
                'slug' => 'vo-phuc-karate-shureido-new-wave-3',
                'category_id' => $catMap['vo-phuc-karate'],
                'price' => 1250000,
                'stock' => 25,
                'unit' => 'Bộ',
                'status' => 'in_stock',
                'description' => "Võ phục Karate Shureido New Wave 3 cao cấp chính hãng.\n- Chất liệu: Vải Canvas Cotton 100% dày 14oz tạo tiếng nổ đanh giòn khi phát lực Kata.\n- Thiết kế: Form chuẩn WKF Approved thi đấu quốc tế.\n- Thấm hút mồ hôi cực tốt, độ bền cao, form đứng dáng mạnh mẽ.\n- Đủ size: 140cm, 150cm, 160cm, 170cm, 180cm.",
                'images' => ['assets/clients/img/product/1.png', 'assets/clients/img/product/2.png'],
            ],
            [
                'name' => 'Võ phục Karate Tokaido Kumite Master Siêu Nhẹ WKF',
                'slug' => 'vo-phuc-karate-tokaido-kumite-master',
                'category_id' => $catMap['vo-phuc-karate'],
                'price' => 950000,
                'stock' => 40,
                'unit' => 'Bộ',
                'status' => 'in_stock',
                'description' => "Võ phục thi đấu đối kháng Kumite siêu nhẹ Tokaido.\n- Chất liệu: Vải Polyester dệt vân tổ ong kim cương siêu nhẹ và thoáng khí.\n- Trọng lượng nhẹ chỉ khoảng 6oz giúp võ sinh di chuyển linh hoạt, ra đòn tốc độ.\n- Đạt chuẩn thi đấu Liên đoàn Karate Thế giới WKF.",
                'images' => ['assets/clients/img/product/2.png', 'assets/clients/img/product/3.png'],
            ],
            [
                'name' => 'Võ phục Karate Tân Sinh Viên & Trẻ Em Phong Trào',
                'slug' => 'vo-phuc-karate-tan-sinh-vien-tre-em',
                'category_id' => $catMap['vo-phuc-karate'],
                'price' => 280000,
                'stock' => 100,
                'unit' => 'Bộ',
                'status' => 'in_stock',
                'description' => "Võ phục Karate cơ bản thích hợp cho võ sinh mới tập luyện tại các CLB, võ đường.\n- Tặng kèm 01 đai trắng tiêu chuẩn.\n- Chất liệu vải Kaki Cotton thoáng mát, thấm hút mồ hôi, dễ giặt ủi.\n- Độ bền cao, đường may chắc chắn chịu lực quăng quật.",
                'images' => ['assets/clients/img/product/3.png'],
            ],

            // 2. Đai võ thuật
            [
                'name' => 'Đai Đen Karate Lụa Satin Cao Cấp Thêu Tên Vàng',
                'slug' => 'dai-den-karate-lua-satin-theu-ten',
                'category_id' => $catMap['dai-vo-thuat-karate'],
                'price' => 350000,
                'stock' => 50,
                'unit' => 'Chiếc',
                'status' => 'in_stock',
                'description' => "Đai đen Karate lụa Satin cao cấp độ dày 12 đường may chuẩn chỉ.\n- Nhận thêu tên tiếng Nhật (Katakana/Kanji) hoặc tiếng Việt chỉ thêu màu vàng kim sang trọng.\n- Chiều dài: 260cm (Số 4), 280cm (Số 5), 300cm (Số 6), 320cm (Số 7).",
                'images' => ['assets/clients/img/product/4.png', 'assets/clients/img/product/5.png'],
            ],
            [
                'name' => 'Đai Màu Karate Tiêu Chuẩn (Vàng, Xanh, Đỏ, Nâu)',
                'slug' => 'dai-mau-karate-tieu-chuan',
                'category_id' => $catMap['dai-vo-thuat-karate'],
                'price' => 60000,
                'stock' => 200,
                'unit' => 'Chiếc',
                'status' => 'in_stock',
                'description' => "Đai màu võ thuật Karate đủ các cấp bậc thi lên đai.\n- 8 đường chỉ may dọc chắc chắn, màu sắc tươi sáng không phai khi giặt.\n- Đủ các màu: Đai Vàng, Đai Xanh Lá, Đai Xanh Dương, Đai Đỏ, Đai Nâu.",
                'images' => ['assets/clients/img/product/5.png', 'assets/clients/img/product/6.png'],
            ],

            // 3. Găng & Giáp bảo hộ
            [
                'name' => 'Găng tay thi đấu Karate WKF Có Xỏ Ngón Cái (Đỏ/Xanh)',
                'slug' => 'gang-tay-thi-dau-karate-wkf-do-xanh',
                'category_id' => $catMap['giap-va-gang-bao-ho'],
                'price' => 380000,
                'stock' => 60,
                'unit' => 'Đôi',
                'status' => 'in_stock',
                'description' => "Găng tay thi đấu đối kháng Karate Kumite chuẩn WKF.\n- Thiết kế đệm mút đúc PU nguyên khối giảm chấn tối đa cho khớp ngón và cổ tay.\n- Có quai xỏ ngón cái chống trẹo ngón khi ra đòn.\n- Đủ hai màu Đỏ và Xanh, các size S, M, L, XL.",
                'images' => ['assets/clients/img/product/7.png', 'assets/clients/img/product/8.png'],
            ],
            [
                'name' => 'Bảo Hộ Ống Chân & Mu Bàn Chân Karate WKF Tháo Rời',
                'slug' => 'bao-ho-ong-chan-mu-ban-chan-karate-wkf',
                'category_id' => $catMap['giap-va-gang-bao-ho'],
                'price' => 450000,
                'stock' => 35,
                'unit' => 'Đôi',
                'status' => 'in_stock',
                'description' => "Bộ bảo hộ chân Karate Kumite gồm bảo hộ cẳng chân và bảo hộ mu bàn chân có thể tháo rời bằng miếng dán Velcro tiện lợi.\n- Đạt tiêu chuẩn an toàn thi đấu quốc tế WKF.",
                'images' => ['assets/clients/img/product/8.png', 'assets/clients/img/product/9.png'],
            ],
            [
                'name' => 'Giáp Bảo Hộ Ngực Thân Thể Karate WKF',
                'slug' => 'giap-bao-ho-nguc-than-the-karate-wkf',
                'category_id' => $catMap['giap-va-gang-bao-ho'],
                'price' => 520000,
                'stock' => 20,
                'unit' => 'Cái',
                'status' => 'in_stock',
                'description' => "Giáp ngực bảo vệ xương sườn, tim phổi và nội tạng khi tập luyện và thi đấu Kumite.\n- Trọng lượng nhẹ, ôm sát cơ thể, thoáng khí.",
                'images' => ['assets/clients/img/product/9.png', 'assets/clients/img/product/10.png'],
            ],

            // 4. Binh khí & Dụng cụ tập
            [
                'name' => 'Makiwara Gỗ Gắn Tường Truyền Thống Okinawa',
                'slug' => 'makiwara-go-gan-tuong-truyen-thong',
                'category_id' => $catMap['binh-khi-va-dung-cu-tap'],
                'price' => 680000,
                'stock' => 15,
                'unit' => 'Bộ',
                'status' => 'in_stock',
                'description' => "Makiwara truyền thống rèn luyện độ cứng nắm đấm Seiken và sức mạnh đòn đấm thẳng Tsuki.\n- Khung gỗ tự nhiên đàn hồi tốt bọc dây đay hoặc mút da PU cao cấp.",
                'images' => ['assets/clients/img/product/10.png', 'assets/clients/img/product/11.png'],
            ],
            [
                'name' => 'Đích Đấm Đá Cầm Tay Chữ Nhật Boxing/Karate',
                'slug' => 'dich-dam-da-cam-tay-chu-nhat',
                'category_id' => $catMap['binh-khi-va-dung-cu-tap'],
                'price' => 290000,
                'stock' => 45,
                'unit' => 'Cái',
                'status' => 'in_stock',
                'description' => "Đích đá chữ nhật cỡ lớn chịu lực đòn đá Mawashi Geri, đấm Gyaku Tsuki cực tốt.\n- Da PU dẻo dai, bên trong lõi EVA cao cấp giảm chấn cho người cầm đích.",
                'images' => ['assets/clients/img/product/11.png', 'assets/clients/img/product/12.png'],
            ],

            // 5. Phụ kiện
            [
                'name' => 'Balo Thể Thao Đa Năng Chống Nước Karate-Do',
                'slug' => 'balo-the-thao-da-nang-chong-nuoc-karate-do',
                'category_id' => $catMap['phu-kien-va-tui-the-thao'],
                'price' => 390000,
                'stock' => 50,
                'unit' => 'Chiếc',
                'status' => 'in_stock',
                'description' => "Balo chuyên dụng cho võ sinh Karate có ngăn riêng để võ phục, găng giáp bảo hộ và bình nước.\n- Vải chống thấm nước Oxford cao cấp, in logo Karate-Do phản quang sắc nét.",
                'images' => ['assets/clients/img/product/13.png', 'assets/clients/img/product/14.png'],
            ],
            [
                'name' => 'Móc Khóa Võ Phục Karate Đai Đen Kỷ Niệm',
                'slug' => 'moc-khoa-vo-phuc-karate-dai-den',
                'category_id' => $catMap['phu-kien-va-tui-the-thao'],
                'price' => 45000,
                'stock' => 150,
                'unit' => 'Cái',
                'status' => 'in_stock',
                'description' => "Móc khóa mô hình võ phục Karate mini đai đen độc đáo và ý nghĩa làm quà tặng võ sinh.",
                'images' => ['assets/clients/img/product/15.png'],
            ],
        ];

        foreach ($products as $prodData) {
            $images = $prodData['images'];
            unset($prodData['images']);

            $product = Product::updateOrCreate(['slug' => $prodData['slug']], $prodData);

            // Xóa ảnh cũ và thêm ảnh mới
            $product->images()->delete();
            foreach ($images as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $img,
                ]);
            }
        }
    }
}
