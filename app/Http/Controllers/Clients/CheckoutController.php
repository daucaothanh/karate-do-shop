<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $items = $request->user()->cartItems()->with('product')->get();
        if ($items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        $total = $items->sum(fn ($item) => $item->quantity * $item->product->price);
        return view('clients.pages.thanh_toan', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|in:COD,Banking',
            'payment_proof' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'fullname.required' => 'Vui lòng nhập họ và tên người nhận.',
            'fullname.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 30 ký tự.',
            'address.required' => 'Vui lòng nhập địa chỉ nhận hàng.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'city.required' => 'Vui lòng nhập tỉnh hoặc thành phố.',
            'city.max' => 'Tên tỉnh hoặc thành phố không được vượt quá 100 ký tự.',
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $address = $request->user()->shippingAddresses()->create($data);
            $items = $request->user()->cartItems()->with('product')->lockForUpdate()->get();
            abort_if($items->isEmpty(), 422, 'Giỏ hàng đang trống.');
            $total = 0;

            foreach ($items as $item) {
                $product = $item->product()->lockForUpdate()->first();
                abort_if(!$product || $product->stockForSize($item->size) < $item->quantity, 422, 'Size ' . ($item->size ?: '') . ' không đủ tồn kho.');
                $total += $product->price * $item->quantity;
            }

            $currentShift = \App\Models\WorkShift::getCurrentShift();
            $shiftId = $currentShift ? $currentShift->id : null;
            $shiftName = $currentShift ? $currentShift->name : \App\Models\WorkShift::getCurrentShiftName();

            $order = Order::create([
                'user_id' => $request->user()->id,
                'shipping_address_id' => $address->id,
                'total_price' => $total,
                'status' => 'pending',
                'shift_id' => $shiftId,
                'shift_name' => $shiftName,
            ]);

            \App\Models\OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'note' => 'Đơn hàng mới tạo - Chờ nhân viên trực ca kiểm tra và duyệt đơn.',
            ]);

            foreach ($items as $item) {
                $product = $item->product()->lockForUpdate()->first();
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'color' => $item->color,
                    'size' => $item->size,
                ]);
                if ($item->size && is_array($product->size_stocks) && array_key_exists($item->size, $product->size_stocks)) {
                    $stocks = $product->size_stocks;
                    $stocks[$item->size] -= $item->quantity;
                    $product->size_stocks = $stocks;
                    $product->stock = array_sum($stocks);
                    $product->save();
                } else {
                    $product->decrement('stock', $item->quantity);
                }
            }

            $paymentProof = $request->hasFile('payment_proof')
                ? $request->file('payment_proof')->store('payment-proofs', 'public')
                : null;

            $order->payment()->create([
                'payment_method' => $data['payment_method'] === 'Banking'
                    ? 'Chuyển khoản VietQR (MBBank)'
                    : 'COD (Thanh toán khi nhận hàng)',
                'status' => $paymentProof ? 'completed' : 'pending',
                'amount' => $total,
                'transaction_id' => null,
                'payment_proof' => $paymentProof,
                'paid_at' => $paymentProof ? now() : null,
            ]);

            if ($paymentProof) {
                \App\Models\Notification::create([
                    'user_id' => $request->user()->id,
                    'type' => 'payment_confirmed',
                    'message' => "Thanh toán đơn hàng #{$order->id} đã thành công. Đơn hàng đang chờ nhân viên duyệt để gửi hàng.",
                    'link' => route('orders.show', $order->id),
                    'is_read' => 0,
                ]);
            }

            $request->user()->cartItems()->delete();
            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Đặt hàng thành công.');
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        \App\Models\Notification::where('user_id', $request->user()->id)
            ->where('is_read', 0)
            ->where(function ($query) use ($order) {
                $orderLink = route('orders.show', $order->id);
                $query->where('link', $orderLink)
                    ->orWhere('link', 'like', '%/orders/' . $order->id . '%');
            })
            ->update(['is_read' => 1]);

        $order->load(['orderItems.product', 'shippingAddress', 'payment']);
        return view('clients.pages.chi_tiet_don_hang', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'transaction_id' => 'nullable|required_without:payment_proof|string|max:100',
            'payment_proof' => 'nullable|required_without:transaction_id|image|mimes:jpg,jpeg,png,webp|max:5120',
            'note' => 'nullable|string|max:255',
        ], [
            'transaction_id.required_without' => 'Vui lòng nhập mã giao dịch hoặc tải ảnh thanh toán.',
            'payment_proof.required_without' => 'Vui lòng tải ảnh thanh toán hoặc nhập mã giao dịch.',
        ]);

        if ($order->payment) {
            $paymentProofUploaded = $request->hasFile('payment_proof');
            $wasPaid = $order->payment->status === 'completed';
            $updates = ['transaction_id' => $validated['transaction_id'] ?? $order->payment->transaction_id];

            if ($paymentProofUploaded) {
                if ($order->payment->payment_proof) {
                    Storage::disk('public')->delete($order->payment->payment_proof);
                }
                $updates['payment_proof'] = $request->file('payment_proof')->store('payment-proofs', 'public');
                $updates['status'] = 'completed';
                $updates['paid_at'] = $order->payment->paid_at ?: now();
            }

            $order->payment->update($updates);

            if ($paymentProofUploaded && !$wasPaid) {
                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'type' => 'payment_confirmed',
                    'message' => "Thanh toán đơn hàng #{$order->id} đã thành công. Đơn hàng đang chờ nhân viên duyệt để gửi hàng.",
                    'link' => route('orders.show', $order->id),
                    'is_read' => 0,
                ]);
            }
        }

        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => 'Khách hàng gửi thông tin thanh toán'
                . (!empty($validated['transaction_id']) ? ': ' . $validated['transaction_id'] : '')
                . ($request->hasFile('payment_proof') ? ' (có ảnh xác nhận)' : '')
                . (!empty($validated['note']) ? (' - ' . $validated['note']) : ''),
        ]);

        return back()->with('success', $request->hasFile('payment_proof')
            ? 'Thanh toán đã thành công. Đơn hàng đang chờ nhân viên duyệt để gửi hàng.'
            : 'Đã gửi thông tin thanh toán. Shop sẽ kiểm tra và xác nhận sớm nhất.');
    }
}
