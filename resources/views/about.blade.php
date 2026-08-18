@extends('layouts.app')

@section('title', 'Giới thiệu - Thành Đạt Taabusico')

@section('content')
@if($banners->isNotEmpty())
<section class="banner-slider" data-banner-slider>
    <div class="banner-track">
        @foreach($banners as $banner)
        <article class="banner-slide {{ $loop->first?'active':'' }} {{ $banner->text_color==='light'?'text-light':'' }} {{ $banner->display_mode==='image_only'?'banner-image-only':'' }}" style="background-image:url('{{ $banner->image->url }}')">
            @if($banner->display_mode!=='image_only')<div class="banner-overlay"></div><div class="container"><div class="banner-copy">@if($banner->eyebrow)<span><i class="fa-solid fa-leaf"></i> {{ $banner->eyebrow }}</span>@endif<h1>{{ $banner->title }}</h1>@if($banner->subtitle)<h2>{{ $banner->subtitle }}</h2>@endif @if($banner->description)<p>{{ $banner->description }}</p>@endif @if($banner->button_label)<a class="btn" href="{{ $banner->button_url?:route('products.index') }}">{{ $banner->button_label }} <i class="fa-solid fa-arrow-right"></i></a>@endif</div></div>@endif
        </article>
        @endforeach
    </div>
    @if($banners->count()>1)<button class="banner-arrow prev" type="button" aria-label="Banner trước"><i class="fa-solid fa-chevron-left"></i></button><button class="banner-arrow next" type="button" aria-label="Banner tiếp theo"><i class="fa-solid fa-chevron-right"></i></button><div class="banner-dots">@foreach($banners as $banner)<button class="{{ $loop->first?'active':'' }}" type="button" data-banner-index="{{ $loop->index }}" aria-label="Banner {{ $loop->iteration }}"></button>@endforeach</div>@endif
</section>
@else
<section class="about-new-hero">
    <div class="container about-new-hero-grid">
        <div class="about-new-copy">
            <span class="about-kicker"><i class="fa-solid fa-leaf"></i> Câu chuyện Thành Đạt Taabusico</span>
            <h1>Gìn giữ tinh hoa<br><strong>nông sản Việt</strong></h1>
            <p>Chúng tôi kết nối những vùng nguyên liệu tử tế với người tiêu dùng, để mỗi sản phẩm đến tay bạn đều rõ nguồn gốc, trọn vị tự nhiên và chứa đựng niềm tự hào quê hương.</p>
            <div class="about-actions">
                <a class="about-primary" href="{{ route('products.index') }}">Khám phá sản phẩm <i class="fa-solid fa-arrow-right"></i></a>
                <a class="about-secondary" href="{{ route('contact') }}"><i class="fa-solid fa-phone"></i> Liên hệ chúng tôi</a>
            </div>
        </div>
        <div class="about-new-visual" aria-hidden="true">
            <div class="about-visual-main"><i class="fa-solid fa-seedling"></i></div>
            <div class="about-floating-card card-origin"><i class="fa-solid fa-location-dot"></i><span><b>Nguồn gốc rõ ràng</b><small>Từ vùng trồng uy tín</small></span></div>
            <div class="about-floating-card card-quality"><i class="fa-solid fa-shield-heart"></i><span><b>Chất lượng chọn lọc</b><small>An tâm mỗi ngày</small></span></div>
        </div>
    </div>
</section>
@endif

<section class="about-stats">
    <div class="container">
        <article><i class="fa-solid fa-map-location-dot"></i><div><strong>30+</strong><span>Tỉnh thành đồng hành</span></div></article>
        <article><i class="fa-solid fa-box-open"></i><div><strong>100+</strong><span>Sản phẩm chọn lọc</span></div></article>
        <article><i class="fa-solid fa-users"></i><div><strong>5.000+</strong><span>Khách hàng tin chọn</span></div></article>
        <article><i class="fa-solid fa-truck-fast"></i><div><strong>Toàn quốc</strong><span>Giao hàng tận nơi</span></div></article>
    </div>
</section>

<section class="about-new-story container">
    <div class="about-story-visual">
        <img src="{{ asset('images/home/gt_sp.png') }}" alt="Nông sản Việt từ nguồn nguyên liệu tử tế">
        <span><i class="fa-solid fa-heart"></i> Tử tế từ nguồn nguyên liệu</span>
    </div>
    <div class="about-story-content">
        <span class="about-kicker"><i class="fa-solid fa-book-open"></i> Khởi nguồn từ sự tử tế</span>
        <h2>Mỗi sản phẩm là một câu chuyện về đất và người</h2>
        <p>Nông sản ngon không chỉ đến từ giống cây tốt, mà còn được tạo nên bởi thổ nhưỡng, bàn tay cần mẫn và sự tôn trọng tự nhiên. Vì vậy, chúng tôi dành thời gian tìm hiểu từng vùng trồng, lựa chọn những đối tác có cùng cam kết về chất lượng.</p>
        <p>Từ thu hoạch, sơ chế đến đóng gói và vận chuyển, mỗi công đoạn đều được chăm chút để sản phẩm giữ được hương vị đặc trưng khi đến với gia đình Việt.</p>
        <div class="about-checks">
            <span><i class="fa-solid fa-circle-check"></i> Chọn lọc kỹ lưỡng</span>
            <span><i class="fa-solid fa-circle-check"></i> Minh bạch nguồn gốc</span>
            <span><i class="fa-solid fa-circle-check"></i> Đóng gói chỉn chu</span>
        </div>
    </div>
</section>

<section class="about-mission">
    <div class="container about-mission-grid">
        <article><div class="about-icon"><i class="fa-solid fa-bullseye"></i></div><span>Sứ mệnh</span><h3>Đưa nông sản Việt đến gần hơn với mọi gia đình</h3><p>Tạo một cầu nối đáng tin cậy, nơi khách hàng dễ dàng tìm thấy sản phẩm ngon, an toàn và mang đậm bản sắc từng vùng miền.</p></article>
        <article><div class="about-icon"><i class="fa-solid fa-eye"></i></div><span>Tầm nhìn</span><h3>Trở thành địa chỉ nông sản Việt được tin yêu</h3><p>Xây dựng hệ sinh thái bền vững, cùng nhà sản xuất địa phương nâng cao giá trị và lan tỏa niềm tự hào hàng Việt.</p></article>
    </div>
</section>

<section class="about-values-new container">
    <div class="about-center-head"><span class="about-kicker"><i class="fa-solid fa-gem"></i> Giá trị chúng tôi theo đuổi</span><h2>Tử tế trong từng lựa chọn</h2><p>Không chỉ bán sản phẩm, chúng tôi muốn kiến tạo một chuỗi giá trị có trách nhiệm với khách hàng, nhà nông và cộng đồng.</p></div>
    <div class="about-value-new-grid">
        <article><div class="about-icon"><i class="fa-solid fa-medal"></i></div><h3>Chất lượng thật</h3><p>Ưu tiên chất lượng thực tế và hương vị tự nhiên thay vì những lời quảng cáo hào nhoáng.</p></article>
        <article><div class="about-icon"><i class="fa-solid fa-magnifying-glass-location"></i></div><h3>Minh bạch</h3><p>Cung cấp thông tin rõ ràng để khách hàng an tâm trong từng quyết định mua sắm.</p></article>
        <article><div class="about-icon"><i class="fa-solid fa-handshake-angle"></i></div><h3>Đồng hành</h3><p>Hợp tác công bằng, lâu dài và cùng người sản xuất địa phương phát triển bền vững.</p></article>
        <article><div class="about-icon"><i class="fa-solid fa-heart-circle-check"></i></div><h3>Tận tâm</h3><p>Lắng nghe, hỗ trợ nhanh chóng và trân trọng mọi trải nghiệm của khách hàng.</p></article>
    </div>
</section>

<section class="about-process-new">
    <div class="container">
        <div class="about-center-head"><span class="about-kicker"><i class="fa-solid fa-route"></i> Hành trình nông sản sạch</span><h2>Từ vùng nguyên liệu đến bàn ăn</h2></div>
        <div class="about-process-grid">
            <article><b>01</b><span><i class="fa-solid fa-plant-wilt"></i></span><h3>Chọn vùng nguyên liệu</h3><p>Hợp tác cùng nhà vườn và đơn vị sản xuất uy tín.</p></article>
            <article><b>02</b><span><i class="fa-solid fa-clipboard-check"></i></span><h3>Kiểm soát chất lượng</h3><p>Sàng lọc cẩn thận theo đặc tính từng sản phẩm.</p></article>
            <article><b>03</b><span><i class="fa-solid fa-box"></i></span><h3>Đóng gói chỉn chu</h3><p>Giữ sản phẩm sạch, an toàn và trọn vẹn hương vị.</p></article>
            <article><b>04</b><span><i class="fa-solid fa-truck-fast"></i></span><h3>Giao tận tay bạn</h3><p>Vận chuyển nhanh chóng và hỗ trợ khách hàng tận tâm.</p></article>
        </div>
    </div>
</section>

<section class="about-new-cta">
    <div class="container"><div><span>CÙNG LAN TỎA GIÁ TRỊ VIỆT</span><h2>Sẵn sàng khám phá những sản vật tử tế?</h2><p>Mỗi lựa chọn của bạn là một sự đồng hành cùng nông sản và người nông dân Việt Nam.</p></div><div><a href="{{ route('products.index') }}">Mua sắm ngay <i class="fa-solid fa-arrow-right"></i></a><a href="{{ route('contact') }}">Liên hệ hợp tác</a></div></div>
</section>
@endsection
