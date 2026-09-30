<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Danh sách tin nhắn liên hệ & tư vấn
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('status')) {
            if ($request->status === 'replied') {
                $query->where('is_replied', true);
            } elseif ($request->status === 'unreplied') {
                $query->where('is_replied', false);
            }
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone_number', 'like', "%{$keyword}%")
                  ->orWhere('message', 'like', "%{$keyword}%");
            });
        }

        $contacts = $query->latest()->paginate(10);
        $contacts->appends($request->query());

        return view('admin.contacts.danh_sach', compact('contacts'));
    }

    /**
     * Đánh dấu đã phản hồi / chưa phản hồi
     */
    public function toggleReplied(Contact $contact)
    {
        $contact->is_replied = !$contact->is_replied;
        $contact->save();

        $statusText = $contact->is_replied ? 'Đã đánh dấu là ĐÃ PHẢN HỒI' : 'Đã đánh dấu là CHƯA PHẢN HỒI';

        return back()->with('success', $statusText . ' cho yêu cầu của ' . $contact->full_name);
    }

    /**
     * Xóa tin nhắn liên hệ
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Đã xóa tin nhắn liên hệ thành công.');
    }
}
