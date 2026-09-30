<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Danh sách đánh giá sản phẩm Karate-Do
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('comment', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($subQ) use ($keyword) {
                      $subQ->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('product', function ($subQ) use ($keyword) {
                      $subQ->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(10);
        $reviews->appends($request->query());

        return view('admin.reviews.danh_gia', compact('reviews'));
    }

    /**
     * Xóa đánh giá
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Đã xóa đánh giá sản phẩm thành công.');
    }
}
