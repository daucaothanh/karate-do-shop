<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm võ thuật Karate
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Tìm kiếm theo từ khóa (tên hoặc mô tả)
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái tồn kho
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10);
        $products->appends($request->query());
        $categories = Category::all();

        return view('admin.products.danh_sach', compact('products', 'categories'));
    }

    /**
     * Form thêm mới sản phẩm võ thuật
     */
    public function create()
    {
        $categories = Category::all();
        $units = ['Bộ', 'Cái', 'Đôi', 'Chiếc', 'Hộp', 'Cặp', 'Cuộn'];

        return view('admin.products.tao_moi', compact('categories', 'units'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'size_stocks' => ['nullable', 'array'],
            'size_stocks.S' => ['nullable', 'integer', 'min:0'],
            'size_stocks.M' => ['nullable', 'integer', 'min:0'],
            'size_stocks.L' => ['nullable', 'integer', 'min:0'],
            'size_stocks.XL' => ['nullable', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:in_stock,out_of_stock,discontinued'],
            'colors' => ['nullable', 'string', 'max:255'],
            'sizes' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm này đã tồn tại.',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá sản phẩm phải là một số hợp lệ.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'unit.required' => 'Vui lòng chọn hoặc nhập đơn vị tính.',
            'images.*.image' => 'File tải lên phải là hình ảnh hợp lệ.',
        ]);

        $slug = Str::slug($validated['name']);
        // Đảm bảo slug là duy nhất
        $baseSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $this->totalSizeStock($validated['size_stocks'] ?? null, $validated['stock']),
            'initial_stock' => $this->totalSizeStock($validated['size_stocks'] ?? null, $validated['stock']),
            'unit' => $validated['unit'],
            'status' => $validated['status'],
            'colors' => $validated['colors'] ?? null,
            'sizes' => $validated['sizes'] ?? null,
            'size_stocks' => $this->cleanSizeStocks($validated['size_stocks'] ?? null),
            'description' => $validated['description'] ?? '',
        ]);

        // Upload hình ảnh sản phẩm
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/products');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fileName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => 'uploads/products/' . $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm võ thuật Karate mới thành công.');
    }

    /**
     * Xem chi tiết sản phẩm
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user', 'orderItems.order']);
        return view('admin.products.chi_tiet', compact('product'));
    }

    /**
     * Form chỉnh sửa sản phẩm
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = ['Bộ', 'Cái', 'Đôi', 'Chiếc', 'Hộp', 'Cặp', 'Cuộn'];
        $product->load('images');

        return view('admin.products.chinh_sua', compact('product', 'categories', 'units'));
    }

    /**
     * Cập nhật thông tin sản phẩm
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'size_stocks' => ['nullable', 'array'],
            'size_stocks.S' => ['nullable', 'integer', 'min:0'],
            'size_stocks.M' => ['nullable', 'integer', 'min:0'],
            'size_stocks.L' => ['nullable', 'integer', 'min:0'],
            'size_stocks.XL' => ['nullable', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:in_stock,out_of_stock,discontinued'],
            'colors' => ['nullable', 'string', 'max:255'],
            'sizes' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm này đã tồn tại.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'stock.required' => 'Vui lòng nhập số lượng kho.',
            'unit.required' => 'Vui lòng chọn đơn vị tính.',
        ]);

        if ($product->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }
            $product->slug = $slug;
        }

        $product->name = $validated['name'];
        $product->category_id = $validated['category_id'];
        $product->price = $validated['price'];
        $product->stock = $this->totalSizeStock($validated['size_stocks'] ?? null, $validated['stock']);
        $product->unit = $validated['unit'];
        $product->status = $validated['status'];
        $product->colors = $validated['colors'] ?? null;
        $product->sizes = $validated['sizes'] ?? null;
        $product->size_stocks = $this->cleanSizeStocks($validated['size_stocks'] ?? null);
        $product->description = $validated['description'] ?? '';
        $product->save();

        // Thêm ảnh mới nếu có
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/products');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fileName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => 'uploads/products/' . $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm võ thuật thành công.');
    }

    private function cleanSizeStocks(?array $stocks): ?array
    {
        if (!$stocks || !collect($stocks)->filter(fn ($stock) => $stock !== null && $stock !== '')->count()) {
            return null;
        }

        return collect(['S', 'M', 'L', 'XL'])
            ->mapWithKeys(fn ($size) => [$size => (int) ($stocks[$size] ?? 0)])
            ->all();
    }

    private function totalSizeStock(?array $stocks, int $fallback): int
    {
        return $this->cleanSizeStocks($stocks)
            ? array_sum($this->cleanSizeStocks($stocks))
            : $fallback;
    }

    /**
     * Xóa một hình ảnh phụ của sản phẩm
     */
    public function destroyImage(ProductImage $image)
    {
        $filePath = public_path($image->image);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        $image->delete();

        return back()->with('success', 'Đã xóa hình ảnh sản phẩm.');
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy(Product $product)
    {
        // Xóa tất cả hình ảnh liên quan
        foreach ($product->images as $img) {
            $filePath = public_path($img->image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $img->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm thành công.');
    }
}
