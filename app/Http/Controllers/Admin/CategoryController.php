<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục Karate-Do
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $categories = $query->latest()->paginate(10);
        $categories->appends($request->query());

        return view('admin.categories.danh_sach', compact('categories'));
    }

    /**
     * Form thêm mới danh mục
     */
    public function create()
    {
        return view('admin.categories.tao_moi');
    }

    /**
     * Lưu danh mục mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục võ thuật.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'image.image' => 'File tải lên phải là hình ảnh hợp lệ.',
        ]);

        $slug = Str::slug($validated['name']);
        $baseSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $uploadPath = public_path('uploads/categories');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $imagePath = 'uploads/categories/' . $fileName;
            }
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? '',
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục võ thuật mới thành công.');
    }

    /**
     * Form chỉnh sửa danh mục
     */
    public function edit(Category $category)
    {
        return view('admin.categories.chinh_sua', compact('category'));
    }

    /**
     * Cập nhật thông tin danh mục
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        if ($category->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }
            $category->slug = $slug;
        }

        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? '';

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }

            $file = $request->file('image');
            if ($file->isValid()) {
                $uploadPath = public_path('uploads/categories');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $category->image = 'uploads/categories/' . $fileName;
            }
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    /**
     * Xóa danh mục
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì đang có sản phẩm liên kết. Vui lòng chuyển hoặc xóa sản phẩm trước.');
        }

        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục thành công.');
    }
}
