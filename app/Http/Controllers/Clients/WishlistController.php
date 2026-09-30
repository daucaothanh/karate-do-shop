<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $items = wishlist::where('user_id', $request->user()->id)->with('product')->latest()->get();
        return view('clients.pages.yeu_thich', compact('items'));
    }

    public function store(Request $request, Product $product)
    {
        wishlist::firstOrCreate(['user_id' => $request->user()->id, 'product_id' => $product->id]);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích.',
            ]);
        }

        return back()->with('success', 'Đã thêm vào danh sách yêu thích.');
    }

    public function destroy(Request $request, Product $product)
    {
        wishlist::where('user_id', $request->user()->id)->where('product_id', $product->id)->delete();
        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }
}
