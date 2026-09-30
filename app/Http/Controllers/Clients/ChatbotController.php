<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Xử lý tin nhắn người dùng gửi tới AI Chatbot (Sensei AI)
     */
    public function sendMessage(Request $request)
    {
        $message = trim($request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'reply' => 'Oss! 🙏 Bạn chưa nhập câu hỏi. Vui lòng nhập nội dung để Sensei AI hỗ trợ bạn nhé!',
                'products' => [],
                'suggestions' => [
                    '🥋 Tư vấn size võ phục',
                    '✨ Sản phẩm mới nhất',
                    '🥊 Đồ bảo hộ thi đấu',
                    '🚚 Chính sách giao hàng',
                ],
            ]);
        }

        $normalized = mb_strtolower($message, 'UTF-8');
        $reply = '';
        $products = [];
        $suggestions = [];

        // 1. Sản phẩm mới / Hàng mới / New
        if (str_contains($normalized, 'mới') || str_contains($normalized, 'new') || str_contains($normalized, 'hàng về') || str_contains($normalized, 'bộ sưu tập')) {
            $latest = Product::with(['category', 'images'])
                ->where('status', '!=', 'discontinued')
                ->latest()
                ->take(4)
                ->get();

            $reply = "🥋 **Dưới đây là các sản phẩm võ thuật mới nhất vừa cập bến Karate-Do Shop!**\n\nTất cả đều được kiểm định chất lượng, đạt chuẩn tập luyện và thi đấu phong trào lẫn chuyên nghiệp. Bạn có thể bấm vào từng sản phẩm bên dưới để xem chi tiết:";
            $products = $this->formatProducts($latest);
            $suggestions = [
                '🥋 Tư vấn chọn size cho võ phục mới',
                '💰 Giá võ phục Kata / Kumite',
                '🚚 Phí giao hàng bao nhiêu?',
            ];
        }
        // 2. Tư vấn chọn size võ phục (chiều cao, cân nặng, size)
        elseif (
            str_contains($normalized, 'size') ||
            str_contains($normalized, 'kích cỡ') ||
            str_contains($normalized, 'chiều cao') ||
            str_contains($normalized, 'cân nặng') ||
            str_contains($normalized, 'nặng') ||
            str_contains($normalized, 'cao') ||
            preg_match('/(1m[0-9]{1,2}|[0-9]{2,3}\s*kg|[0-9]{2,3}\s*cm)/i', $normalized)
        ) {
            $reply = "🥋 **BẢNG HƯỚNG DẪN CHỌN SIZE VÕ PHỤC KARATE-DO CHUẨN:**\n\n" .
                "• **Size 00**: Chiều cao 100cm - 110cm (15kg - 22kg)\n" .
                "• **Size 0**: Chiều cao 110cm - 120cm (20kg - 28kg)\n" .
                "• **Size 1**: Chiều cao 120cm - 130cm (28kg - 35kg)\n" .
                "• **Size 2**: Chiều cao 130cm - 140cm (35kg - 45kg)\n" .
                "• **Size 3**: Chiều cao 140cm - 150cm (45kg - 55kg)\n" .
                "• **Size 4**: Chiều cao 150cm - 160cm (50kg - 62kg)\n" .
                "• **Size 5**: Chiều cao 160cm - 170cm (60kg - 75kg)\n" .
                "• **Size 6**: Chiều cao 170cm - 180cm (75kg - 88kg)\n" .
                "• **Size 7**: Chiều cao 180cm - 192cm (85kg - 100kg)\n\n" .
                "💡 **Lưu ý chuyên môn từ Sensei AI:**\n" .
                "- **Võ phục Kumite (Đối kháng)**: Chất vải mỏng nhẹ, dập vân thoáng khí, nên chọn vừa vặn hoặc lớn hơn 1/2 size để đá và di chuyển linh hoạt.\n" .
                "- **Võ phục Kata (Quyền)**: Vải canvas dày dặn, form cứng đứng dáng, tạo tiếng kêu đanh giòn khi ra đòn.\n" .
                "- Nếu bạn ở ngưỡng giữa 2 size hoặc người đậm mình, hãy **chọn tăng lên 1 size** nhé! Shop hỗ trợ đổi size miễn phí trong 7 ngày.";

            // Lấy 3 mẫu võ phục tiêu biểu
            $giproducts = Product::with(['category', 'images'])
                ->where('name', 'like', '%võ phục%')
                ->orWhere('name', 'like', '%rikaido%')
                ->orWhere('name', 'like', '%bushido%')
                ->where('status', '!=', 'discontinued')
                ->take(3)
                ->get();
            if ($giproducts->isNotEmpty()) {
                $products = $this->formatProducts($giproducts);
            }

            $suggestions = [
                '✨ Sản phẩm võ phục mới nhất',
                '🥊 Đồ bảo hộ thi đấu kèm theo',
                '🚚 Chính sách đổi size thế nào?',
            ];
        }
        // 3. Đồ bảo hộ, găng, giáp, bịt răng, đai
        elseif (
            str_contains($normalized, 'bảo hộ') ||
            str_contains($normalized, 'găng') ||
            str_contains($normalized, 'giáp') ||
            str_contains($normalized, 'bịt răng') ||
            str_contains($normalized, 'đai') ||
            str_contains($normalized, 'kuki') ||
            str_contains($normalized, 'mu bàn chân')
        ) {
            $protectProducts = Product::with(['category', 'images'])
                ->where(function ($q) {
                    $q->where('name', 'like', '%bảo hộ%')
                        ->orWhere('name', 'like', '%găng%')
                        ->orWhere('name', 'like', '%giáp%')
                        ->orWhere('name', 'like', '%đai%')
                        ->orWhere('name', 'like', '%bịt răng%');
                })
                ->where('status', '!=', 'discontinued')
                ->take(4)
                ->get();

            $reply = "🥊 **Trang thiết bị bảo hộ Karate chuẩn quy chuẩn WKF:**\n\n" .
                "Khi tham gia tập luyện đối kháng (Kumite) hoặc thi đấu, việc trang bị đầy đủ bảo hộ là bắt buộc để đảm bảo an toàn tối đa:\n" .
                "• **Găng thi đấu Kumite** (Đỏ/Xanh): Lớp mút đúc PU bảo vệ khớp bàn tay.\n" .
                "• **Bảo vệ ống chân & mu bàn chân**: Giảm chấn thương khi chặn đòn đá.\n" .
                "• **Giáp thân & bảo hộ hạ bộ (Kuki)**: Che chắn vùng ngực, bụng và hạ bộ.\n" .
                "• **Bảo hộ hàm (Bịt răng)**: Định hình ôm khít hàm trên.\n\n" .
                "Dưới đây là một số trang thiết bị bảo hộ chất lượng tại cửa hàng:";

            $products = $this->formatProducts($protectProducts);
            $suggestions = [
                '🥋 Tư vấn chọn màu đai Karate',
                '✨ Sản phẩm mới nhất',
                '📞 Gọi hotline đặt số lượng lớn',
            ];
        }
        // 4. Giá cả, chi phí, bao nhiêu tiền
        elseif (str_contains($normalized, 'giá') || str_contains($normalized, 'tiền') || str_contains($normalized, 'bao nhiêu') || str_contains($normalized, 'bảng giá')) {
            $reply = "💰 **Bảng giá tham khảo các dòng sản phẩm tại Karate-Do Shop:**\n\n" .
                "• **Võ phục tập luyện sơ cấp (Phong trào)**: từ 220.000₫ – 380.000₫/bộ (đã kèm đai trắng sơ cấp)\n" .
                "• **Võ phục trung cấp (Rikaido, Bushido)**: từ 450.000₫ – 750.000₫/bộ\n" .
                "• **Võ phục thi đấu cao cấp (Kata / Kumite chuẩn WKF)**: từ 950.000₫ – 1.850.000₫/bộ\n" .
                "• **Đai võ các cấp (Trắng, Vàng, Xanh, Nâu, Đen)**: từ 40.000₫ – 220.000₫/sợi (có nhận thêu tên theo yêu cầu)\n" .
                "• **Phụ kiện bảo hộ thi đấu**: từ 80.000₫ – 650.000₫/món\n\n" .
                "🎯 Đặc biệt: Đơn hàng từ **1.000.000₫** được **Miễn phí vận chuyển toàn quốc**!";

            $sampleProducts = Product::with(['category', 'images'])
                ->where('status', '!=', 'discontinued')
                ->latest()
                ->take(3)
                ->get();
            $products = $this->formatProducts($sampleProducts);

            $suggestions = [
                '✨ Xem các sản phẩm mới nhất',
                '🥋 Tư vấn chọn size võ phục',
                '🚚 Chính sách giao hàng & thanh toán',
            ];
        }
        // 5. Giao hàng, ship, vận chuyển, bao lâu
        elseif (str_contains($normalized, 'giao hàng') || str_contains($normalized, 'ship') || str_contains($normalized, 'vận chuyển') || str_contains($normalized, 'bao lâu')) {
            $reply = "🚚 **Chính sách giao hàng toàn quốc tại Karate-Do Shop:**\n\n" .
                "• **Thời gian giao hàng:**\n" .
                "  - Nội thành / Khu vực lân cận: 1 – 2 ngày làm việc.\n" .
                "  - Các tỉnh thành khác trên toàn quốc: 2 – 4 ngày làm việc.\n" .
                "• **Hình thức thanh toán:**\n" .
                "  - Nhận hàng kiểm tra rồi mới thanh toán (COD).\n" .
                "  - Chuyển khoản ngân hàng nhanh chóng, an toàn.\n" .
                "• **Phí vận chuyển:**\n" .
                "  - Đồng giá 30.000₫ toàn quốc.\n" .
                "  - **MIỄN PHÍ SHIP** cho mọi đơn hàng từ 1.000.000₫ trở lên!\n" .
                "• **Quyền lợi khách hàng:** Được bóc hộp đồng kiểm số lượng và mẫu mã trước khi nhận.";

            $suggestions = [
                '🥋 Chính sách đổi trả size',
                '✨ Xem sản phẩm mới nhất',
                '📞 Hotline hỗ trợ đặt đơn',
            ];
        }
        // 6. Đổi trả, bảo hành
        elseif (str_contains($normalized, 'đổi trả') || str_contains($normalized, 'đổi size') || str_contains($normalized, 'bảo hành') || str_contains($normalized, 'không vừa')) {
            $reply = "🔄 **Chính sách đổi trả & bảo hành linh hoạt:**\n\n" .
                "• **Đổi size miễn phí trong 7 ngày** kể từ khi nhận hàng nếu mặc không vừa hoặc không thoải mái.\n" .
                "• **Điều kiện đổi trả:** Sản phẩm còn nguyên tem mác, chưa qua giặt tẩy và không bị rách bẩn do lỗi người dùng.\n" .
                "• **Bảo hành chất lượng:** 1 đổi 1 ngay lập tức nếu phát hiện lỗi đường may, cúc áo hoặc vải bị lỗi từ nhà sản xuất.\n" .
                "• Quy trình đơn giản: Chỉ cần nhắn tin cho Sensei AI hoặc gọi **Hotline: 0967.137.200**, shipper sẽ giao size mới tận nhà và thu hồi size cũ!";

            $suggestions = [
                '🥋 Hướng dẫn chọn size chuẩn',
                '✨ Sản phẩm mới về',
                '📞 Gọi hotline: 0967.137.200',
            ];
        }
        // 7. Kata vs Kumite / Kiến thức võ thuật Karate
        elseif (str_contains($normalized, 'kata') || str_contains($normalized, 'kumite') || str_contains($normalized, 'karate là gì')) {
            $reply = "🥋 **Kiến thức võ thuật Karate-Do:**\n\n" .
                "• **Kata (Bài quyền):** Là chuỗi các động tác phòng thủ và tấn công liên hoàn mô phỏng chiến đấu với đối thủ ảo. Võ phục Kata cần vải dày dặn (thường 12oz – 14oz), form đứng áo rộng tay ngắn hơn giúp tiếng vung tay đá chân phát ra âm thanh dứt khoát, uy lực.\n\n" .
                "• **Kumite (Đối kháng):** Là hình thức thi đấu đối kháng trực tiếp giữa 2 võ sĩ. Võ phục Kumite ưu tiên vải mỏng nhẹ (khoảng 6oz – 8oz), có các lỗ thoát khí ở nách và lưng, form ôm co giãn tốt để võ sĩ di chuyển thần tốc.\n\n" .
                "Shop luôn có sẵn cả 2 dòng võ phục Kata và Kumite chuyên dụng cho bạn lựa chọn!";

            $kataKumite = Product::with(['category', 'images'])
                ->where(function ($q) {
                    $q->where('name', 'like', '%kata%')
                        ->orWhere('name', 'like', '%kumite%')
                        ->orWhere('name', 'like', '%võ phục%');
                })
                ->where('status', '!=', 'discontinued')
                ->take(3)
                ->get();
            $products = $this->formatProducts($kataKumite);

            $suggestions = [
                '🥋 Bảng chọn size võ phục',
                '✨ Xem sản phẩm mới nhất',
                '🥊 Trang thiết bị bảo hộ thi đấu',
            ];
        }
        // 8. Chào hỏi, cảm ơn, liên hệ
        elseif (str_contains($normalized, 'chào') || str_contains($normalized, 'hi') || str_contains($normalized, 'hello') || str_contains($normalized, 'hey')) {
            $reply = "Oss! 🙏 Chào bạn! Tôi là **Sensei AI** - Trợ lý võ thuật ảo của Karate-Do Shop.\n\nTôi có thể giúp bạn giải đáp mọi thông tin về:\n- 🥋 Hướng dẫn chọn size võ phục chính xác theo chiều cao & cân nặng\n- ✨ Khám phá các mẫu sản phẩm mới nhất vừa về\n- 🥊 Tư vấn đồ bảo hộ, đai, găng thi đấu WKF\n- 📦 Bảng giá, phí ship & chính sách đổi size 7 ngày\n\nBạn đang quan tâm đến sản phẩm nào hoặc cần Sensei hỗ trợ điều gì?";
            $suggestions = [
                '✨ Sản phẩm mới nhất',
                '🥋 Tư vấn chọn size võ phục',
                '🥊 Đồ bảo hộ thi đấu',
                '🚚 Chính sách giao hàng',
            ];
        } elseif (str_contains($normalized, 'cảm ơn') || str_contains($normalized, 'thanks') || str_contains($normalized, 'thank you')) {
            $reply = "Oss! 🙏 Rất vui được hỗ trợ bạn! Chúc bạn luôn rèn luyện tốt và giữ vững tinh thần Karate-Do kiên định. Nếu có bất kỳ câu hỏi nào khác, Sensei luôn sẵn sàng hỗ trợ 24/7!";
            $suggestions = [
                '✨ Khám phá sản phẩm mới',
                '🥋 Bảng size võ phục',
            ];
        }
        // 9. Tìm kiếm sản phẩm theo từ khóa bất kỳ trong cơ sở dữ liệu
        else {
            $searchProducts = Product::with(['category', 'images'])
                ->where(function ($q) use ($message) {
                    $q->where('name', 'like', "%{$message}%")
                        ->orWhere('description', 'like', "%{$message}%");
                })
                ->where('status', '!=', 'discontinued')
                ->take(4)
                ->get();

            if ($searchProducts->isNotEmpty()) {
                $reply = "🥋 Sensei tìm thấy một số sản phẩm phù hợp với yêu cầu '{$message}' của bạn đây:";
                $products = $this->formatProducts($searchProducts);
                $suggestions = [
                    '🥋 Tư vấn size cho sản phẩm này',
                    '✨ Xem thêm sản phẩm mới',
                    '🚚 Phí giao hàng thế nào?',
                ];
            } else {
                $reply = "Oss! 🙏 Cảm ơn bạn đã nhắn tin cho Sensei AI.\n\nĐể nhận được tư vấn chi tiết nhất về sản phẩm, size võ phục hoặc đặt hàng theo yêu cầu CLB, bạn có thể chọn các gợi ý bên dưới hoặc liên hệ trực tiếp với chúng tôi qua:\n\n📞 **Hotline / Zalo tư vấn:** `0967.137.200`\n🌐 Hỗ trợ 24/7 từ đội ngũ HLV & VĐV Karate chuyên nghiệp!";
                
                // Trả về vài sản phẩm mới nhất để khách tiện xem
                $fallbackProducts = Product::with(['category', 'images'])
                    ->where('status', '!=', 'discontinued')
                    ->latest()
                    ->take(3)
                    ->get();
                $products = $this->formatProducts($fallbackProducts);

                $suggestions = [
                    '✨ Sản phẩm mới nhất',
                    '🥋 Hướng dẫn chọn size võ phục',
                    '💰 Bảng giá võ phục & phụ kiện',
                    '🚚 Thời gian & phí giao hàng',
                ];
            }
        }

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'products' => $products,
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Định dạng dữ liệu sản phẩm để hiển thị trong khung chat
     */
    protected function formatProducts($products)
    {
        return $products->map(function ($p) {
            $img = $p->image ?: ($p->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => number_format($p->price, 0, ',', '.') . ' ₫',
                'unit' => $p->unit ?? 'Bộ',
                'image' => asset($img),
                'category' => $p->category->name ?? 'Karate-Do',
                'url' => route('products.show', $p->slug),
            ];
        })->toArray();
    }
}
