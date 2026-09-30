<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'images'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('q') . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($category) => $category->where('slug', $request->input('category')));
            })
            ->where('status', '!=', 'out_of_stock')
            ->latest()
            ->paginate(12)
            ->appends($request->query());

        return view('clients.pages.danh_sach_san_pham', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Product $product)
    {
        abort_if($product->status === 'out_of_stock', 404);

        return view('clients.pages.chi_tiet_san_pham', compact('product'));
    }
}
