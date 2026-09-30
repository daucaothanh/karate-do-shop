<?php

use Illuminate\Support\Facades\Route;

/*
| PHÂN BIỆT CÁC PHẦN TRONG ỨNG DỤNG
| - Frontend khách hàng: resources/views/clients và resources/views/layouts/khach_hang*.blade.php
| - Frontend quản trị: resources/views/admin và resources/views/layouts/quan_tri.blade.php
| - Backend: app/Http/Controllers, app/Models, app/Http/Middleware và database.
| File này là nơi nối URL với backend Controller; Controller sau đó trả về frontend View.
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ==================== FRONTEND KHÁCH HÀNG ====================
// Các URL dưới đây là trang khách hàng nhìn thấy trên website bán hàng.
Route::get('/', function () {
    $categories = App\Models\Category::withCount('products')->get();
    $allProducts = App\Models\Product::with(['category', 'images'])
        ->where('status', '!=', 'discontinued')
        ->latest()
        ->get();
    $newProducts = $allProducts->take(8);
    return view('clients.pages.trang_chu', compact('newProducts', 'categories', 'allProducts'));
})->name('home');

Route::post('/chatbot/message', [App\Http\Controllers\Clients\ChatbotController::class, 'sendMessage'])->name('chatbot.message');

Route::get('/about', function () {
    return view('clients.pages.ve_chung_toi');
});
Route::get('/service', function () {
    return view('clients.pages.dich_vu');
});
Route::get('/team', function () {
    return view('clients.pages.doi_ngu');
});
Route::get('/faq', function () {
    return view('clients.pages.cau_hoi_thuong_gap');
});
Route::get('/contact', [App\Http\Controllers\Clients\CustomerController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\Clients\CustomerController::class, 'sendContact'])->name('contact.send');
Route::get('/products', [App\Http\Controllers\Clients\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [App\Http\Controllers\Clients\ProductController::class, 'show'])->name('products.show');
Route::get('/register', [App\Http\Controllers\Clients\AuthController::class, 'ShowregisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Clients\AuthController::class, 'register'])-> name('post-register');

Route::get('/activate/{token}', function (string $token) {
    $user = App\Models\User::where('activation_token', $token)->first();

    if (!$user) {
        return redirect()->route('register')
            ->with('error', 'Liên kết kích hoạt không hợp lệ hoặc đã được sử dụng.');
    }

    $user->update([
        'status' => 'active',
        'activation_token' => null,
    ]);

    return redirect()->route('register')
        ->with('success', 'Kích hoạt tài khoản thành công. Bạn có thể đăng nhập.');
})->name('activate');

Route::get('/login', [App\Http\Controllers\Clients\AuthController::class, 'ShowloginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Clients\AuthController::class, 'login'])-> name('post-login');
Route::post('/logout', [App\Http\Controllers\Clients\AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [App\Http\Controllers\Clients\AuthController::class, 'logout'])->name('logout.get');

Route::get('/forgot-password', [App\Http\Controllers\Clients\ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Clients\ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Clients\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Clients\ResetPasswordController::class, 'resetPassword'])->name('password.update');

// Backend xử lý tài khoản khách hàng, giỏ hàng, thanh toán và đơn hàng.
// Giao diện trả về tương ứng nằm trong resources/views/clients.
Route::middleware('auth')->group(function () {
    Route::get('/account', [App\Http\Controllers\Clients\CustomerController::class, 'account'])->name('account');
    Route::put('/account', [App\Http\Controllers\Clients\CustomerController::class, 'updateAccount'])->name('account.update');
    Route::get('/wishlist', [App\Http\Controllers\Clients\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [App\Http\Controllers\Clients\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [App\Http\Controllers\Clients\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/cart', [App\Http\Controllers\Clients\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [App\Http\Controllers\Clients\CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [App\Http\Controllers\Clients\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [App\Http\Controllers\Clients\CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [App\Http\Controllers\Clients\CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [App\Http\Controllers\Clients\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [App\Http\Controllers\Clients\CustomerController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Clients\CheckoutController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/confirm-payment', [App\Http\Controllers\Clients\CheckoutController::class, 'confirmPayment'])->name('orders.confirm-payment');
});

/*
|--------------------------------------------------------------------------
| FRONTEND QUẢN TRỊ + BACKEND QUẢN LÝ
|--------------------------------------------------------------------------
| Các URL /admin là màn hình quản trị viên/nhân viên.
| View: resources/views/admin
| Xử lý dữ liệu: app/Http/Controllers/Admin
| Bảo vệ quyền: app/Http/Middleware/StaffMiddleware và AdminMiddleware
*/

Route::get('/admin/login', function () {
    return redirect()->route('admin.login', ['admin_session' => bin2hex(random_bytes(16))]);
})->name('admin.open');

Route::prefix('admin/session/{admin_session}')->where(['admin_session' => '[a-f0-9]{32}|new'])->name('admin.')->group(function () {
    // FRONTEND: màn hình đăng nhập quản trị; BACKEND: Admin\AuthController xử lý đăng nhập.
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    // BACKEND: StaffMiddleware kiểm tra phiên đăng nhập và quyền admin/staff.
    Route::middleware('staff')->group(function () {
        // FRONTEND ADMIN: bảng điều khiển; BACKEND: DashboardController.
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // FRONTEND ADMIN + BACKEND: quản lý sản phẩm.
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::delete('products/images/{image}', [App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('products.images.destroy');

        // FRONTEND ADMIN + BACKEND: quản lý danh mục.
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);

        // FRONTEND ADMIN + BACKEND: quản lý đơn hàng.
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/notifications', [App\Http\Controllers\Admin\OrderController::class, 'shiftNotifications'])->name('orders.notifications');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('/orders/{order}/payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePayment'])->name('orders.payment');
        Route::patch('/orders/{order}/payment-proof/approve', [App\Http\Controllers\Admin\OrderController::class, 'approvePaymentProof'])->name('orders.payment-proof.approve');
        Route::get('/orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');

        // FRONTEND ADMIN + BACKEND: quản lý ca làm việc và chấm công.
        Route::get('/shifts', [App\Http\Controllers\Admin\ShiftController::class, 'index'])->name('shifts.index');
        Route::post('/shifts/checkin', [App\Http\Controllers\Admin\ShiftController::class, 'checkIn'])->name('shifts.checkin');
        Route::post('/shifts/checkout', [App\Http\Controllers\Admin\ShiftController::class, 'checkOut'])->name('shifts.checkout');
        Route::post('/shifts/change-requests', [App\Http\Controllers\Admin\ShiftController::class, 'submitChangeRequest'])->name('shifts.change-requests.store');
        Route::patch('/shifts/change-requests/{changeRequest}/approve', [App\Http\Controllers\Admin\ShiftController::class, 'approveChangeRequest'])->middleware('admin')->name('shifts.change-requests.approve');
        Route::patch('/shifts/change-requests/{changeRequest}/reject', [App\Http\Controllers\Admin\ShiftController::class, 'rejectChangeRequest'])->middleware('admin')->name('shifts.change-requests.reject');
        Route::get('/shifts/export', [App\Http\Controllers\Admin\ShiftController::class, 'export'])->name('shifts.export');
        Route::get('/shifts/payroll', [App\Http\Controllers\Admin\PayrollController::class, 'index'])->middleware('admin')->name('shifts.payroll');
        Route::put('/shifts/payroll/rates', [App\Http\Controllers\Admin\PayrollController::class, 'updateRates'])->middleware('admin')->name('shifts.payroll.rates');

        // FRONTEND ADMIN + BACKEND: báo cáo và thống kê kinh doanh.
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-sales', [App\Http\Controllers\Admin\ReportController::class, 'exportSales'])->name('reports.export.sales');

        // FRONTEND ADMIN + BACKEND: quản lý đánh giá sản phẩm.
        Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

        // FRONTEND ADMIN + BACKEND: quản lý liên hệ và tư vấn.
        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::patch('/contacts/{contact}/toggle', [App\Http\Controllers\Admin\ContactController::class, 'toggleReplied'])->name('contacts.toggle');
        Route::delete('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        // FRONTEND ADMIN + BACKEND: hồ sơ cá nhân và đổi mật khẩu.
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.password');

        // BACKEND: AdminMiddleware giới hạn các chức năng chỉ dành cho quản trị viên.
        Route::middleware('admin')->group(function () {
            Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
            Route::patch('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
        });
    });
});

// Existing bookmarks open a fresh, independent admin/staff session.
Route::get('/admin/{path?}', function (\Illuminate\Http\Request $request, $path = '') {
    return redirect('/admin/session/'.bin2hex(random_bytes(16)).($path ? '/'.$path : '')
        .($request->getQueryString() ? '?'.$request->getQueryString() : ''));
})->where('path', '(?!session/).*');
