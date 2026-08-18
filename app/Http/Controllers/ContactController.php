<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\ContactMessageNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'message.required' => 'Vui lòng nhập nội dung cần liên hệ.',
        ]);

        $contact = ContactMessage::create($data);
        $recipient = config('mail.from.address') ?: 'congtytdfoods@gmail.com';

        try {
            Mail::to($recipient)->send(new ContactMessageNotification($contact));
            $contact->update(['mail_sent_at' => now()]);
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('contact_success', 'Cảm ơn bạn! Thông tin đã được ghi nhận, chúng tôi sẽ liên hệ lại sớm nhất.');
    }
}
