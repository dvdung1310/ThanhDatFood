@extends('layouts.app')
@section('title','Liên hệ')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">Kết nối cùng chúng tôi</span><h1>Liên hệ</h1><p>Nông Sản Việt luôn sẵn lòng lắng nghe và đồng hành cùng bạn.</p></div></section>
<section class="section container contact-grid">
    <div><span class="eyebrow">CÔNG TY TNHH THỰC PHẨM SẠCH THÀNH ĐẠT TD FOODS</span><h2>Gửi lời nhắn cho chúng tôi</h2><p>Liên hệ để được tư vấn sản phẩm, đặt hàng số lượng lớn hoặc hợp tác cung ứng nông sản.</p><div class="contact-items"><div><b>Địa chỉ</b><span>NS 30, Ngõ 50, Tổ 6 Mễ Trì Thượng, Quận Nam Từ Liêm, HN</span></div><div><b>Điện thoại</b><a href="tel:0938905582">0938.905.582</a></div><div><b>Email</b><a href="mailto:congtytdfoods@gmail.com">congtytdfoods@gmail.com</a></div><div><b>Facebook</b><span>nongsansach</span></div><div><b>Giờ làm việc</b><span>08:00 – 18:00, Thứ 2 – Chủ nhật</span></div></div></div>
    <form class="contact-form" id="contact-form" method="post" action="{{ route('contact.store') }}">
        @csrf
        @if(session('contact_success'))<div class="alert success">{{ session('contact_success') }}</div>@endif
        @if($errors->any())<div class="alert error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <label>Họ và tên<input name="name" value="{{ old('name') }}" required maxlength="150" placeholder="Nhập họ và tên"></label>
        <label>Số điện thoại<input name="phone" value="{{ old('phone') }}" type="tel" required maxlength="30" placeholder="Nhập số điện thoại"></label>
        <label>Email<input name="email" value="{{ old('email') }}" type="email" maxlength="255" placeholder="email@example.com"></label>
        <label>Nội dung<textarea name="message" rows="5" required maxlength="5000" placeholder="Bạn cần Nông Sản Việt hỗ trợ điều gì?">{{ old('message') }}</textarea></label>
        <button class="btn" id="contact-submit" type="submit"><span class="submit-label">Gửi liên hệ</span><span class="submit-loading" hidden><i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Đang gửi...</span></button>
    </form>
</section>
<script>
document.getElementById('contact-form')?.addEventListener('submit', function () {
    const button = document.getElementById('contact-submit');
    button.disabled = true;
    button.querySelector('.submit-label').hidden = true;
    button.querySelector('.submit-loading').hidden = false;
});
</script>
@endsection
