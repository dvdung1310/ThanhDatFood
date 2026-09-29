@extends('layouts.app')
@section('title',$post->title)
@section('content')
<style>
    .post-toc{margin:0 0 35px;padding:22px 25px;border:1px solid #d9e5f8;border-left:4px solid #1762dd;background:#f5f8ff}
    .post-toc h2{margin:0 0 12px;color:#1762dd;font:700 20px 'Be Vietnam Pro',sans-serif}
    .post-toc ol{margin:0;padding-left:22px}.post-toc li{margin:8px 0;color:#1762dd;font-size:13px;line-height:1.5}
    .post-toc li.toc-level-3{margin-left:20px;font-size:12px}.post-toc a:hover{text-decoration:underline}
    .post-content h2,.post-content h3{scroll-margin-top:110px}
    @media(max-width:560px){.post-toc{padding:18px 16px}.post-toc li.toc-level-3{margin-left:10px}}
</style>
<div class="container breadcrumb"><a href="{{ route('home') }}">Trang chủ</a> / <a href="{{ route('news.index') }}">Kiến thức &amp; Tin tức</a> / {{ Str::limit($post->title,55) }}</div>
<article class="container post-detail">
    <header><span class="eyebrow">Kiến thức &amp; Tin tức</span><h1>{{ $post->title }}</h1><p class="post-lead">{{ $post->excerpt }}</p><div class="post-meta"><span>{{ $post->published_at->format('d/m/Y') }}</span><span>Người đăng: {{ $post->author?->name ?: 'Thành Đạt' }}</span></div></header>
    @if($post->featuredImage)<figure><img src="{{ $post->featuredImage->url }}" alt="{{ $post->featuredImage->alt_text ?: $post->title }}"></figure>@endif
    <nav class="post-toc" id="post-toc" aria-label="Mục lục bài viết" hidden><h2>Mục lục bài viết</h2><ol></ol></nav>
    <div class="post-content ck-content" id="post-content-detail">{!! $post->content !!}</div>
</article>
@if($related->count())<section class="section cream"><div class="container"><div class="section-head"><div><span class="eyebrow">Có thể bạn quan tâm</span><h2>Bài viết mới</h2></div></div><div class="article-grid">@foreach($related as $item)<article class="article-card"><a class="article-image" href="{{ route('news.show',$item->slug) }}">@if($item->featuredImage)<img src="{{ $item->featuredImage->url }}">@else<span>🌱</span>@endif</a><div><small>{{ $item->published_at->format('d.m.Y') }}</small><h3><a href="{{ route('news.show',$item->slug) }}">{{ $item->title }}</a></h3><a class="read-more" href="{{ route('news.show',$item->slug) }}">Xem chi tiết →</a></div></article>@endforeach</div></div></section>@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const content = document.getElementById('post-content-detail');
    const toc = document.getElementById('post-toc');
    if (!content || !toc) return;
    const headings = [...content.querySelectorAll('h2, h3')];
    if (!headings.length) return;
    const usedIds = new Set();
    headings.forEach((heading, index) => {
        let id = heading.textContent.trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || `muc-${index + 1}`;
        const baseId = id;
        let suffix = 2;
        while (usedIds.has(id) || document.getElementById(id)) id = `${baseId}-${suffix++}`;
        usedIds.add(id);
        heading.id = id;
        const item = document.createElement('li');
        item.className = `toc-level-${heading.tagName.slice(1)}`;
        const link = document.createElement('a');
        link.href = `#${id}`;
        link.textContent = heading.textContent;
        item.appendChild(link);
        toc.querySelector('ol').appendChild(item);
    });
    toc.hidden = false;
});
</script>
@endsection
