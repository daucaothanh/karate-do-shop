<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function account(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()->with(['orderItems.product', 'payment'])->latest()->paginate(10);
        return view('clients.pages.tai_khoan', compact('user', 'orders'));
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()->with(['orderItems.product', 'payment'])->latest()->paginate(10);
        return view('clients.pages.danh_sach_don_hang', compact('user', 'orders'));
    }

    public function updateAccount(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
        ]);
        $request->user()->update($data);
        return back()->with('success', 'Thông tin tài khoản đã được cập nhật.');
    }

    public function contact()
    {
        return view('clients.pages.lien_he');
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:2000',
        ]);
        Contact::create($data);
        return back()->with('success', 'Tin nhắn đã được gửi đến shop.');
    }
}
