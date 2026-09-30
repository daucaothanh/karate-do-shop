<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Danh sách tài khoản người dùng & nhân viên
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'defaultShift']);

        if ($request->filled('role')) {
            $roleName = $request->role;
            $query->whereHas('role', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone_number', 'like', "%{$keyword}%");
            });
        }

        $users = $query->latest()->paginate(10);
        $users->appends($request->query());
        $roles = Role::all();

        return view('admin.users.danh_sach', compact('users', 'roles'));
    }

    /**
     * Form thêm mới nhân viên / người dùng
     */
    public function create()
    {
        $roles = Role::all();
        $shifts = WorkShift::all();
        return view('admin.users.tao_moi', compact('roles', 'shifts'));
    }

    /**
     * Lưu tài khoản mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'default_shift_id' => ['nullable', 'exists:work_shifts,id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,pending,banned'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'role_id.required' => 'Vui lòng chọn vai trò.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $uploadPath = public_path('uploads/avatars');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $avatarPath = 'uploads/avatars/' . $fileName;
            }
        }

        User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'default_shift_id' => $validated['default_shift_id'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
            'avatar' => $avatarPath,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo tài khoản người dùng/nhân viên mới thành công.');
    }

    /**
     * Form chỉnh sửa thông tin tài khoản
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $shifts = WorkShift::all();
        return view('admin.users.chinh_sua', compact('user', 'roles', 'shifts'));
    }

    /**
     * Cập nhật tài khoản
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'default_shift_id' => ['nullable', 'exists:work_shifts,id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,pending,banned'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
        ]);

        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);
        $user->role_id = $validated['role_id'];
        $user->default_shift_id = $validated['default_shift_id'] ?? null;
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

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

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }

    /**
     * Khóa / Mở khóa nhanh tài khoản
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
        }

        $user->status = ($user->status === 'active') ? 'banned' : 'active';
        $user->save();

        $statusText = ($user->status === 'active') ? 'Mở khóa hoạt động' : 'Khóa';

        return back()->with('success', "Đã {$statusText} tài khoản {$user->name}.");
    }

    /**
     * Xóa tài khoản
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình.');
        }

        if ($user->avatar && File::exists(public_path($user->avatar))) {
            File::delete(public_path($user->avatar));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã xóa tài khoản thành công.');
    }
}
