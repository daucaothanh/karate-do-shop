<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::guard('admin')->user();
        return view('admin.profile.ho_so', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
        ]);

        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->address = $validated['address'] ?? null;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $uploadPath = public_path('uploads/avatars');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $user->avatar = 'uploads/avatars/' . $fileName;
            }
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu tài khoản thành công.');
    }
}
