<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập Admin & Nhân viên
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user->role && in_array($user->role->name, ['admin', 'staff'])) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.dang_nhap');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();

            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt. Vui lòng liên hệ quản trị viên.');
            }

            if ($user->role && in_array($user->role->name, ['admin', 'staff'])) {
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Chào mừng ' . $user->name . ' đã đăng nhập vào hệ thống Karate-Do Shop!');
            }

            Auth::guard('admin')->logout();
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Tài khoản này không có quyền truy cập vào phân hệ Quản lý & Nhân viên.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();

        // Tự động kết thúc ca làm việc đang mở nếu có
        if ($user) {
            try {
                $activeLog = \App\Models\StaffWorkLog::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                if ($activeLog) {
                    $checkOutTime = now();
                    $checkInTime = $activeLog->check_in_at ?: $activeLog->login_at;
                    $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);

                    $activeLog->update([
                        'check_out_at' => $checkOutTime,
                        'total_minutes' => $totalMinutes,
                        'status' => 'completed',
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback an toàn
            }
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Đã đăng xuất và lưu trữ nhật ký ca làm việc an toàn.');
    }
}
