@extends('layouts.app')
@section('title','Thành Đạt Taabusico - Tinh hoa nông sản Việt')
@section('content')
<style>
    .home-news-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    @media (max-width: 560px) {
        .home-news-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .home-news-image { height: 135px; }
        .home-news-grid article > small,
        .home-news-grid article > h3,
        .home-news-grid article > p,
        .home-news-grid article > .read-more { margin-left: 10px; margin-right: 10px; }
        .home-news-grid h3 { font-size: 14px; }
    }
</style>
<section class="banner-slider" data-banner-slider>
    <div class="banner-track">
        @forelse($banners as $banner)
        <article class="banner-slide {{ $loop->first?'active':'' }} {{ $banner->text_color==='light'?'text-light':'' }} {{ $banner->display_mode==='image_only'?'banner-image-only':'' }}" style="background-image:url('{{ $banner->image->url }}')">
            @if($banner->display_mode!=='image_only')<div class="banner-overlay"></div><div class="container"><div class="banner-copy">@if($banner->eyebrow)<span><i class="fa-solid fa-leaf"></i> {{ $banner->eyebrow }}</span>@endif<h1>{{ $banner->title }}</h1>@if($banner->subtitle)<h2>{{ $banner->subtitle }}</h2>@endif @if($banner->description)<p>{{ $banner->description }}</p>@endif @if($banner->button_label)<a class="btn" href="{{ $banner->button_url?:route('products.index') }}">{{ $banner->button_label }} <i class="fa-solid fa-arrow-right"></i></a>@endif</div></div>@endif
        </article>
        @empty
        <article class="banner-slide active banner-empty"><div class="container"><div class="banner-copy"><span><i class="fa-solid fa-leaf"></i> Tinh hoa</span><h1>NÔNG SẢN VIỆT</h1><h2>SẠCH - NGON - CHẤT LƯỢNG</h2><p>Kết nối nông sản sạch từ mọi miền đất nước đến bữa ăn an lành cho mọi gia đình Việt.</p><a class="btn" href="{{ route('products.index') }}">Khám phá ngay <i class="fa-solid fa-arrow-right"></i></a></div></div></article>
        @endforelse
    </div>
    @if($banners->count()>1)<button class="banner-arrow prev" type="button" aria-label="Banner trước"><i class="fa-solid fa-chevron-left"></i></button><button class="banner-arrow next" type="button" aria-label="Banner tiếp theo"><i class="fa-solid fa-chevron-right"></i></button><div class="banner-dots">@foreach($banners as $banner)<button class="{{ $loop->first?'active':'' }}" type="button" data-banner-index="{{ $loop->index }}" aria-label="Banner {{ $loop->iteration }}"></button>@endforeach</div>@endif
</section>
<section class="market-hero legacy-hero">
    <div class="container market-hero-grid">
        <div class="market-copy"><span class="market-script"><i class="fa-solid fa-leaf"></i> Tinh hoa</span><h1>NÔNG SẢN VIỆT</h1><h2>SẠCH - NGON - CHẤT LƯỢNG</h2><p>Kết nối nông sản sạch từ mọi miền đất nước đến bữa ăn an lành cho mọi gia đình Việt.</p><a class="btn" href="{{ route('products.index') }}">KHÁM PHÁ NGAY <b>›</b></a><div class="hero-promises"><div><i class="fa-solid fa-seedling"></i><span><b>100%</b><small>Nông sản sạch</small></span></div><div><i class="fa-solid fa-location-dot"></i><span><b>Nguồn gốc</b><small>rõ ràng</small></span></div><div><i class="fa-solid fa-truck-fast"></i><span><b>Giao hàng</b><small>toàn quốc</small></span></div></div></div>
        <div class="market-basket" aria-hidden="true"><div class="market-sun"></div><span>🥬</span><span>🥦</span><span>🍅</span><span>🍊</span><span>🌽</span><span>🍇</span><div class="basket-base">🧺</div></div>
    </div>
</section>

<section class="market-categories container" aria-label="Danh mục sản phẩm">
    @forelse($categories as $category)
    <a href="{{ route('products.index',['category'=>$category->slug]) }}">@if($category->image)<img src="{{ $category->image->url }}" alt="{{ $category->name }}">@else<span>🌾</span>@endif<div><b>{{ $category->name }}</b><small>{{ $category->products_count }} sản phẩm</small><i>→</i></div></a>
    @empty
    @foreach([['Gạo & Ngũ cốc','gao.svg'],['Rau củ quả','rau-cu.svg'],['Trái cây','trai-cay.svg'],['Đặc sản vùng miền','dac-san.svg'],['Gia vị','gia-vi.svg'],['Các loại hạt','hat.svg']] as $item)<a href="{{ route('products.index') }}"><img src="{{ asset('images/products/'.$item[1]) }}" alt="{{ $item[0] }}"><div><b>{{ $item[0] }}</b><small>Khám phá ngay</small><i>→</i></div></a>@endforeach
    @endforelse
</section>

<section class="market-section container"><div class="market-heading"><div><span>ĐƯỢC KHÁCH HÀNG YÊU THÍCH</span><h2>SẢN PHẨM NỔI BẬT</h2></div><a href="{{ route('products.index') }}">Xem tất cả <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div><div class="product-grid market-products">@forelse($featured as $product)<x-product-card :product="$product" />@empty<div class="empty">Sản phẩm đang được cập nhật.</div>@endforelse</div></section>

<section class="home-about"><div class="container home-about-grid"><div class="home-about-art has-photo"><img src="{{ asset('images/home/gt_sp.png') }}" alt="Giới thiệu sản phẩm Thành Đạt"><b>Tử tế từ<br>nguồn nguyên liệu</b></div><div><span class="eyebrow">Câu chuyện Thành Đạt</span><h2>Từ vùng nguyên liệu tốt đến bữa cơm lành</h2><p>Mỗi sản phẩm là kết quả của đất đai màu mỡ, bàn tay cần mẫn và một hành trình minh bạch. Chúng tôi đồng hành cùng nhà nông để gìn giữ giống bản địa, phát triển canh tác bền vững và đưa nông sản Việt đến gần hơn với mọi gia đình.</p><div class="home-about-points"><div><b>100%</b><small>Nguồn gốc rõ ràng</small></div><div><b>30+</b><small>Tỉnh thành kết nối</small></div><div><b>48h</b><small>Từ vườn đến nhà</small></div></div><a class="text-link" href="{{ route('about') }}">Tìm hiểu về chúng tôi →</a></div></div></section>

<section class="market-section container"><div class="market-heading"><div><span>CHỈN CHU TRONG TỪNG KHÂU</span><h2>HÀNH TRÌNH NÔNG SẢN SẠCH</h2></div></div><div class="process-grid"><article><i>01</i><span><i class="fa-solid fa-seedling"></i></span><h3>Chọn vùng nguyên liệu</h3><p>Hợp tác cùng nhà vườn uy tín trên khắp các vùng miền.</p></article><article><i>02</i><span><i class="fa-solid fa-shield-halved"></i></span><h3>Kiểm soát chất lượng</h3><p>Sàng lọc cẩn thận theo tiêu chuẩn của từng dòng sản phẩm.</p></article><article><i>03</i><span><i class="fa-solid fa-box-open"></i></span><h3>Đóng gói chỉn chu</h3><p>Giữ sản phẩm an toàn, sạch sẽ và trọn vẹn hương vị.</p></article><article><i>04</i><span><i class="fa-solid fa-truck-fast"></i></span><h3>Giao tận tay bạn</h3><p>Vận chuyển nhanh chóng và hỗ trợ khách hàng tận tâm.</p></article></div></section>

<section class="home-news"><div class="container"><div class="market-heading"><div><span>GÓC THÀNH ĐẠT</span><h2>TIN TỨC & CÂU CHUYỆN</h2></div><a href="{{ route('news.index') }}">Xem tất cả <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div><div class="home-news-grid">@forelse($latestPosts as $post)<article><a class="home-news-image" href="{{ route('news.show',$post->slug) }}">@if($post->featuredImage)<img src="{{ $post->featuredImage->url }}" alt="{{ $post->title }}">@else<span>🌾</span>@endif</a><small>{{ $post->published_at->format('d.m.Y') }}</small><h3><a href="{{ route('news.show',$post->slug) }}">{{ $post->title }}</a></h3><p>{{ Str::limit($post->excerpt,110) }}</p><a class="read-more" href="{{ route('news.show',$post->slug) }}">Đọc thêm →</a></article>@empty<div class="empty">Tin tức đang được cập nhật.</div>@endforelse</div></div></section>

<section class="home-cta"><div class="container"><div><span>ĐỒNG HÀNH CÙNG NÔNG SẢN VIỆT</span><h2>Bạn cần tư vấn sản phẩm hoặc hợp tác phân phối?</h2><p>Đội ngũ Thành Đạt luôn sẵn sàng lắng nghe và hỗ trợ.</p></div><div><a class="btn" href="{{ route('contact') }}">LIÊN HỆ NGAY</a><a href="tel:0938905582">☎ 0938.905.582</a></div></div></section>
<section class="latest-products-section">
    <div class="container">
        <div class="market-heading latest-products-heading">
            <div><span>VỪA ĐƯỢC CẬP NHẬT</span><h2>SẢN PHẨM MỚI NHẤT</h2></div>
            <a href="{{ route('products.index') }}">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="latest-products-six">
            @forelse($latestProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="empty">Sản phẩm mới đang được cập nhật.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
