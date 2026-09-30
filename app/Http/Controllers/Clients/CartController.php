<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->cartItems()->with('product')->get();
        $total = $items->sum(fn ($item) => $item->quantity * $item->product->price);

        return view('clients.pages.gio_hang', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm đã chọn không tồn tại.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'color.max' => 'Màu sắc không được vượt quá 100 ký tự.',
            'size.max' => 'Kích thước không được vượt quá 100 ký tự.',
        ]);
        $product = Product::findOrFail($data['product_id']);

        if ($product->stockForSize($data['size'] ?? null) < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Số lượng sản phẩm trong kho không đủ.']);
        }

        $item = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'color' => $data['color'] ?? null,
            'size' => $data['size'] ?? null,
        ]);
        $item->quantity = ($item->quantity ?? 0) + $data['quantity'];
        $item->save();

        if ($request->filled('buy_now')) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(array_merge([
                    'success' => true,
                    'message' => 'Đã thêm ' . $product->name . ' vào giỏ hàng.',
                    'redirect' => route('checkout.create'),
                ], $this->cartSummary($request->user())));
            }

            return redirect()->route('checkout.create');
        }

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Đã thêm ' . $product->name . ' vào giỏ hàng.',
            ], $this->cartSummary($request->user())));
        }

        return back()->with('success', 'Đã thêm ' . $product->name . ' vào giỏ hàng.');
    }

    private function cartSummary($user): array
    {
        $items = $user->cartItems()->with(['product.images'])->get();

        return [
            'cart_count' => $items->sum('quantity'),
            'cart_subtotal' => $items->sum(fn ($item) => $item->quantity * ($item->product->price ?? 0)),
            'cart_items' => $items->map(function ($item) {
                $image = $item->product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png';

                return [
                    'name' => $item->product->name,
                    'slug' => $item->product->slug,
                    'url' => route('products.show', $item->product->slug),
                    'image' => asset($image),
                    'color' => $item->color,
                    'size' => $item->size,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ];
            })->values(),
        ];
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);
        $data = $request->validate(['quantity' => 'required|integer|min:1'], [
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);

        if ($cartItem->product->stockForSize($cartItem->size) < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Số lượng sản phẩm trong kho không đủ.']);
        }

        $cartItem->update(['quantity' => $data['quantity']]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng.',
                'refresh' => true,
            ]);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);
        $cartItem->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                'refresh' => true,
            ]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
