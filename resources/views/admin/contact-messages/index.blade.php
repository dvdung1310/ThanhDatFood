@extends('layouts.admin')
@section('title','Liên hệ khách hàng')
@section('content')
<div class="admin-title"><div><h1>Liên hệ khách hàng</h1><p>Lời nhắn gửi từ biểu mẫu liên hệ trên website.</p></div></div>
<section class="panel">
    <form class="order-filters"><input name="q" value="{{ request('q') }}" placeholder="Tên, số điện thoại hoặc email"><select name="status"><option value="">Tất cả trạng thái</option><option value="new" @selected(request('status')==='new')>Mới</option><option value="read" @selected(request('status')==='read')>Đã xem</option><option value="replied" @selected(request('status')==='replied')>Đã phản hồi</option></select><button class="btn">Lọc</button></form>
    <div class="table-wrap"><table><thead><tr><th>Khách hàng</th><th>Liên hệ</th><th>Nội dung</th><th>Email thông báo</th><th>Trạng thái</th><th></th></tr></thead><tbody>
    @forelse($messages as $message)<tr><td><b>{{ $message->name }}</b><small>{{ $message->created_at->format('d/m/Y H:i') }}</small></td><td><b>{{ $message->phone }}</b><small>{{ $message->email ?: 'Không có email' }}</small></td><td>{{ Str::limit($message->message, 80) }}</td><td><span class="badge {{ $message->mail_sent_at?'on':'warn' }}">{{ $message->mail_sent_at?'Đã gửi':'Chưa gửi' }}</span></td><td><span class="badge {{ $message->status==='new'?'warn':'on' }}">{{ ['new'=>'Mới','read'=>'Đã xem','replied'=>'Đã phản hồi'][$message->status] }}</span></td><td class="actions"><a href="{{ route('admin.contact-messages.show',$message) }}">Chi tiết</a></td></tr>
    @empty<tr><td colspan="6" class="empty">Chưa có lời nhắn liên hệ.</td></tr>@endforelse
    </tbody></table></div>{{ $messages->links() }}
</section>
@endsection
