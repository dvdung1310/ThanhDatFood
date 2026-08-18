@extends('layouts.app')
@section('title', $product->name)
@section('content')
<style>.order-phone-link{display:flex;align-items:center;justify-content:center;width:100%;margin:24px 0 25px;padding:14px 22px;border-radius:4px;background:linear-gradient(135deg,#1762dd,#347cf0);color:#fff;font-size:16px;box-shadow:0 7px 18px #2366d826}.order-phone-link:hover{background:linear-gradient(135deg,#174fae,#286de2);color:#fff}</style>
<div class="container breadcrumb"><a href="{{ route('home') }}">Trang chủ</a> / <a href="{{ route('products.index') }}">Sản phẩm</a> / {{ $product->name }}</div>
<section class="container detail">
    <div class="gallery"><div class="main-photo">@if($product->primary_image)<img id="mainImage" src="{{ $product->primary_image->url }}" alt="{{ $product->name }}">@else<span><i class="fa-solid fa-leaf"></i></span>@endif</div><div class="thumbs">@foreach($product->media as $image)<button data-image="{{ $image->url }}"><img src="{{ $image->url }}" alt="{{ $image->alt_text }}"></button>@endforeach</div></div>
    <div class="product-info"><span class="eyebrow">{{ $product->category->name }}</span><h1>{{ $product->name }}</h1><p class="lead">{{ $product->short_description }}</p><div class="detail-price"><strong>{{ number_format($product->price,0,',','.') }}đ</strong>@if($product->original_price && $product->original_price > $product->price)<del>{{ number_format($product->original_price,0,',','.') }}đ</del><span>-{{ $product->discount_percentage }}%</span>@endif<small>/ {{ $product->unit }}</small></div><dl><div><dt>Xuất xứ</dt><dd>{{ $product->origin ?: 'Việt Nam' }}</dd></div><div><dt>Mã sản phẩm</dt><dd>{{ $product->sku }}</dd></div><div><dt>Tình trạng</dt><dd>{{ $product->stock > 0 ? 'Còn hàng' : 'Tạm hết hàng' }}</dd></div></dl>{{-- Tạm ẩn mua hàng online; giữ route để dễ bật lại. --}}<a class="order-phone-link" href="tel:0938905582"><i class="fa-solid fa-phone" aria-hidden="true"></i> Gọi đặt hàng: 0938.905.582</a></div>
</section>
@if(filled($product->description))
<section class="container product-description-section">
    <h2>Thông tin chi tiết sản phẩm</h2>
    <div class="description ck-content">@if($product->description !== strip_tags($product->description)){!! $product->description !!}@else{!! nl2br(e($product->description)) !!}@endif</div>
</section>
@endif
@if($related->count())<section class="section cream"><div class="container"><div class="section-head"><h2>Sản phẩm cùng loại</h2></div><div class="product-grid">@foreach($related as $item)<x-product-card :product="$item" />@endforeach</div></div></section>@endif
@endsection
