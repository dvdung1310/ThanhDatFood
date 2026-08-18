@extends('layouts.admin') @section('title',$post->exists?'Sửa bài viết':'Viết bài mới') @section('content')
<div class="admin-title"><div><h1>{{ $post->exists?'Sửa bài viết':'Viết bài mới' }}</h1><p>Soạn nội dung đẹp và tối ưu cho người đọc.</p></div><a class="admin-back-btn" href="{{ route('admin.posts.index') }}"><i class="fa-solid fa-arrow-left"></i> Quay lại</a></div>
@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<form class="editor post-editor-form" method="post" action="{{ $post->exists?route('admin.posts.update',$post):route('admin.posts.store') }}">
    @csrf @if($post->exists)@method('put')@endif
    <div class="post-editor-layout">
        <section class="panel form-grid">
            <label class="span-2">Tiêu đề bài viết *<input name="title" value="{{ old('title',$post->title) }}" data-slug-source required></label>
            <label class="span-2">Đường dẫn SEO<input name="slug" value="{{ old('slug',$post->slug) }}" data-slug-target data-has-saved-slug="{{ $post->slug ? 'true' : 'false' }}" placeholder="Tự động tạo theo tiêu đề"></label>
            <label class="span-2">Mô tả ngắn<textarea name="excerpt" rows="3" maxlength="700" placeholder="Đoạn giới thiệu hiển thị ở danh sách tin">{{ old('excerpt',$post->excerpt) }}</textarea></label>
            <label class="span-2">Nội dung bài viết *</label><div class="span-2"><textarea id="post-content" name="content" data-upload-url="{{ route('admin.media.upload') }}">{{ old('content',$post->content) }}</textarea></div>
        </section>
        <aside>
            <section class="panel"><h3>Xuất bản</h3><label>Trạng thái<select name="status"><option value="draft" @selected(old('status',$post->status?:'draft')==='draft')>Bản nháp</option><option value="published" @selected(old('status',$post->status)==='published')>Đăng bài</option></select></label><label>Thời gian xuất bản<input type="datetime-local" name="published_at" value="{{ old('published_at',$post->published_at?->format('Y-m-d\TH:i')) }}"></label><p class="hint">Để trống sẽ đăng ngay khi chọn “Đăng bài”.</p></section>
            <section class="panel featured-panel"><h3>Ảnh đại diện</h3><input id="featured-image-id" type="hidden" name="featured_image_id" value="{{ old('featured_image_id',$post->featured_image_id) }}"><div id="featured-preview" class="featured-preview {{ $post->featuredImage?'has-image':'' }}">@if($post->featuredImage)<img src="{{ $post->featuredImage->url }}" alt="{{ $post->featuredImage->alt_text }}">@else<span>Chưa có ảnh đại diện</span>@endif</div><button class="media-modal-open" type="button">Chọn ảnh đại diện</button><button id="remove-featured" class="link-button" type="button" @if(!$post->featuredImage) hidden @endif>Xóa ảnh đại diện</button></section>
        </aside>
    </div>
    <div class="form-actions"><a href="{{ route('admin.posts.index') }}">Hủy</a><button class="btn">Lưu bài viết</button></div>
</form>

<div id="media-modal" class="media-modal" data-upload-url="{{ route('admin.media.upload') }}" aria-hidden="true">
    <div class="media-modal-backdrop" data-close-media></div><section class="media-modal-dialog" role="dialog" aria-modal="true" aria-label="Chọn ảnh đại diện">
        <header><div><h2>Ảnh đại diện</h2><p>Tải ảnh mới hoặc chọn ảnh đã có trong thư viện.</p></div><button type="button" data-close-media aria-label="Đóng">×</button></header>
        <div class="media-tabs"><button class="active" type="button" data-media-tab="library">Thư viện ảnh</button><button type="button" data-media-tab="upload">Tải ảnh mới</button></div>
        <div class="media-tab-pane active" data-media-pane="library"><div id="modal-media-grid" class="modal-media-grid">@foreach($media as $image)<button type="button" class="modal-media-item {{ old('featured_image_id',$post->featured_image_id)==$image->id?'selected':'' }}" data-media-id="{{ $image->id }}" data-media-url="{{ $image->url }}" data-media-name="{{ $image->name }}"><img src="{{ $image->url }}" alt="{{ $image->alt_text }}"><span>{{ $image->name }}</span></button>@endforeach</div></div>
        <div class="media-tab-pane" data-media-pane="upload"><div class="wp-upload-box"><span>▧</span><h3>Thả ảnh vào đây để tải lên</h3><p>hoặc</p><label class="btn">Chọn ảnh từ máy tính<input id="featured-upload" type="file" accept="image/*" hidden></label><small>JPG, PNG, WEBP · Tối đa 5MB</small><div id="upload-progress" class="upload-progress" hidden><i></i><span>Đang tải ảnh...</span></div></div></div>
        <footer><div id="selected-media-name">{{ $post->featuredImage?->name ?: 'Chưa chọn ảnh' }}</div><button class="btn" id="confirm-featured" type="button" @if(!$post->featuredImage) disabled @endif>Đặt làm ảnh đại diện</button></footer>
    </section>
</div>
@endsection
