@extends('layouts.app')
@section('title','Kiến thức & Tin tức')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">Góc chia sẻ</span><h1>Kiến thức &amp; Tin tức</h1><p>Câu chuyện mùa vụ, kiến thức nông sản và những hoạt động mới nhất.</p></div></section>
<section class="section container">
    @if($posts->count())
        @php($featured=$posts->first())
        <article class="news-hero-card">
            <a class="news-hero-image" href="{{ route('news.show',$featured->slug) }}">@if($featured->featuredImage)<img src="{{ $featured->featuredImage->url }}" alt="{{ $featured->featuredImage->alt_text ?: $featured->title }}">@else<span>🌾</span>@endif</a>
            <div><small>{{ $featured->published_at->format('d.m.Y') }} · TIN MỚI</small><h2><a href="{{ route('news.show',$featured->slug) }}">{{ $featured->title }}</a></h2><p>{{ $featured->excerpt }}</p><a class="read-more" href="{{ route('news.show',$featured->slug) }}">Đọc bài viết →</a></div>
        </article>
        <div class="article-grid">@foreach($posts->skip(1) as $post)<article class="article-card"><a class="article-image" href="{{ route('news.show',$post->slug) }}">@if($post->featuredImage)<img src="{{ $post->featuredImage->url }}" alt="{{ $post->title }}">@else<span>🌱</span>@endif</a><div><small>{{ $post->published_at->format('d.m.Y') }}</small><h3><a href="{{ route('news.show',$post->slug) }}">{{ $post->title }}</a></h3><p>{{ Str::limit($post->excerpt,120) }}</p><a class="read-more" href="{{ route('news.show',$post->slug) }}">Xem chi tiết →</a></div></article>@endforeach</div>
        {{ $posts->links() }}
    @else <div class="empty">Tin tức đang được cập nhật.</div> @endif
</section>
@endsection
