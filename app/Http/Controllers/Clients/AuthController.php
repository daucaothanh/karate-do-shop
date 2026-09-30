<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ActivationMail;

class AuthController extends Controller
{
    public function ShowregisterForm()
    {
        return view('clients.pages.dang_ky');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'confirmpassword' => ['required', 'same:password'],
            'checkbox1' => ['accepted'],
            'checkbox2' => ['accepted'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.min' => 'Họ và tên phải có ít nhất 3 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'confirmpassword.same' => 'Mật khẩu nhập lại không khớp.',
            'checkbox1.accepted' => 'Bạn cần đồng ý với chính sách sử dụng thông tin.',
            'checkbox2.accepted' => 'Bạn cần đồng ý với chính sách bảo mật.',
        ]);

        $email = strtolower($validated['email']);
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            return back()
                ->withInput($request->except('password', 'confirmpassword'))
                ->with('error', 'Email này đã được đăng ký. Vui lòng sử dụng email khác.');
        }

        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        
        $token = Str::random(64);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
            'role_id' => $customerRole->id,
            'activation_token' => $token,
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

        return redirect()
            ->route('login')
            ->with('success', 'Đăng ký thành công. Tài khoản của bạn đang chờ được kích hoạt.');
    }

   public function activate($token)
{
    $user = User::where('activation_token', $token)->first();

    if (!$user) {
        return redirect()
            ->route('register')
            ->with('error', 'Liên kết kích hoạt không hợp lệ hoặc đã hết hạn.');
    }

    $user->status = 'active';
    $user->activation_token = null;
    $user->save();

    return redirect()
        ->route('login')
        ->with('success', 'Kích hoạt tài khoản thành công. Chúc mừng bạn!');
}

    public function ShowloginForm(){
        return view ('clients.pages.dang_nhap');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $credentials['status'] = 'active';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role?->name;

            if ($role === 'customer') {
                return redirect()
                    ->route('home')
                    ->with('success', 'Đăng nhập thành công.');
            }

            if (in_array($role, ['admin', 'staff'])) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Đăng nhập thành công vào hệ thống Quản trị & Nhân viên.');
            }

            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Bạn không có quyền truy cập vào tài khoản này.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email, mật khẩu không đúng hoặc tài khoản chưa được kích hoạt.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Đăng xuất thành công.');
    }
}
