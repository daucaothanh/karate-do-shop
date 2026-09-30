<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Auth::shouldUse('admin');

        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        $user = Auth::guard('admin')->user();

        if ($user->status !== 'active') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.');
        }

        $role = $user->role ? $user->role->name : null;

        if (in_array($role, ['admin', 'staff'])) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
    }
}
