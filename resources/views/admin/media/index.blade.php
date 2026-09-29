@extends('layouts.admin')
@section('title','Thư viện ảnh')
@section('content')
@if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
<div class="admin-title media-title">
    <div><h1>Thư viện ảnh</h1><p>Tải ảnh một lần, sử dụng lại ở nhiều sản phẩm và danh mục.</p></div>
    <form method="get" class="media-page-size"><label>Hiển thị <select name="per_page" onchange="this.form.submit()">@foreach([12,20,40,60] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }} ảnh</option>@endforeach</select></label></form>
</div>
<form class="panel upload" method="post" enctype="multipart/form-data" action="{{ route('admin.media.store') }}">@csrf<div><b>Tải ảnh mới</b><p>JPG, PNG, WEBP tối đa 5MB/ảnh. Có thể chọn nhiều ảnh.</p></div><input type="file" name="files[]" accept="image/*" multiple required><input name="alt_text" placeholder="Mô tả ảnh (hỗ trợ SEO)"><button class="btn">Tải lên</button></form>
<div class="media-result-bar"><span>Hiển thị {{ $media->firstItem() ?? 0 }}–{{ $media->lastItem() ?? 0 }} trong {{ $media->total() }} ảnh</span><span>Trang {{ $media->currentPage() }}/{{ $media->lastPage() }}</span></div>
<div class="media-library">
    @forelse($media as $m)
        <article><img src="{{ $m->url }}" alt="{{ $m->alt_text }}"><form method="post" action="{{ route('admin.media.update',$m) }}">@csrf @method('put')<input name="name" value="{{ $m->name }}"><input name="alt_text" value="{{ $m->alt_text }}" placeholder="Mô tả ảnh"><button>Lưu</button></form><small>{{ strtoupper(pathinfo($m->file_name,PATHINFO_EXTENSION)) }} · {{ number_format($m->size/1024) }} KB</small><form method="post" action="{{ route('admin.media.destroy',$m) }}" onsubmit="return confirm('Bạn chắc chắn muốn xóa ảnh này?')">@csrf @method('delete')<button class="danger">Xóa ảnh</button></form></article>
    @empty<div class="empty">Thư viện chưa có ảnh.</div>@endforelse
</div>
@if($media->hasPages())<div class="media-pagination">{{ $media->onEachSide(1)->links() }}</div>@endif
@endsection
