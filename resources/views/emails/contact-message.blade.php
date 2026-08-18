<!doctype html>
<html lang="vi"><body style="font-family:Arial,sans-serif;color:#172b4d;line-height:1.6">
<h2 style="color:#1762dd">Có liên hệ mới từ website</h2>
<p><b>Khách hàng:</b> {{ $contactMessage->name }}</p>
<p><b>Số điện thoại:</b> <a href="tel:{{ $contactMessage->phone }}">{{ $contactMessage->phone }}</a></p>
<p><b>Email:</b> {{ $contactMessage->email ?: 'Không cung cấp' }}</p>
<p><b>Nội dung:</b></p>
<div style="padding:16px;background:#f3f7ff;border-left:4px solid #1762dd">{!! nl2br(e($contactMessage->message)) !!}</div>
</body></html>
