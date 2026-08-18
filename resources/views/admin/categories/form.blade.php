@extends('layouts.admin')

@section('title', $category->exists ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
@php($selectedImage = $media->firstWhere('id', old('image_id', $category->image_id)))
<div class="admin-title"><div><h1>{{ $category->exists ? 'Sửa danh mục' : 'Thêm danh mục' }}</h1><p>Thông tin nhóm sản phẩm hiển thị trên website.</p></div><a class="admin-back-btn" href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-arrow-left"></i> Quay lại</a></div>
<form class="editor" method="post" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf @if($category->exists) @method('put') @endif
    <section class="panel form-grid">
        <label>Tên danh mục *<input name="name" value="{{ old('name', $category->name) }}" required></label>
        <label>Đường dẫn (để trống sẽ tự tạo)<input name="slug" value="{{ old('slug', $category->slug) }}"></label>
        <label>Danh mục cha<select name="parent_id"><option value="">Không có</option>@foreach($categories as $item)<option value="{{ $item->id }}" @selected(old('parent_id', $category->parent_id) == $item->id)>{{ $item->name }}</option>@endforeach</select></label>
        <label>Thứ tự<input type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></label>
        <label class="span-2">Mô tả<textarea name="description" rows="4">{{ old('description', $category->description) }}</textarea></label>
        <section class="category-image-field span-2">
            <b>Ảnh đại diện</b>
            <input id="featured-image-id" type="hidden" name="image_id" value="{{ old('image_id', $category->image_id) }}">
            <div class="category-image-picker">
                <div id="featured-preview" class="featured-preview {{ $selectedImage ? 'has-image' : '' }}">@if($selectedImage)<img src="{{ $selectedImage->url }}" alt="{{ $selectedImage->alt_text }}">@else<span>Chưa có ảnh đại diện</span>@endif</div>
                <div class="category-image-actions"><button class="media-modal-open" type="button">Chọn ảnh từ thư viện</button><small>Chọn ảnh có sẵn hoặc tải ảnh mới lên.</small><button id="remove-featured" class="link-button" type="button" @if(!$selectedImage) hidden @endif>Không dùng ảnh</button></div>
            </div>
        </section>
        <label class="check span-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))> Hiển thị danh mục</label>
    </section>
    <div class="form-actions"><a href="{{ route('admin.categories.index') }}">Hủy</a><button class="btn">Lưu danh mục</button></div>
</form>
<div id="media-modal" class="media-modal" data-upload-url="{{ route('admin.media.upload') }}" aria-hidden="true">
    <div class="media-modal-backdrop" data-close-media></div><section class="media-modal-dialog" role="dialog" aria-modal="true" aria-label="Chọn ảnh đại diện">
        <header><div><h2>Chọn ảnh đại diện</h2><p>Tải ảnh mới hoặc chọn ảnh đã có trong thư viện.</p></div><button type="button" data-close-media aria-label="Đóng">×</button></header>
        <div class="media-tabs"><button class="active" type="button" data-media-tab="library">Thư viện ảnh</button><button type="button" data-media-tab="upload">Tải ảnh mới</button></div>
        <div class="media-tab-pane active" data-media-pane="library"><div id="modal-media-grid" class="modal-media-grid">@foreach($media as $image)<button type="button" class="modal-media-item {{ old('image_id', $category->image_id) == $image->id ? 'selected' : '' }}" data-media-id="{{ $image->id }}" data-media-url="{{ $image->url }}" data-media-name="{{ $image->name }}"><img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy"><span>{{ $image->name }}</span></button>@endforeach</div></div>
        <div class="media-tab-pane" data-media-pane="upload"><div class="wp-upload-box"><span>▧</span><h3>Thả ảnh vào đây để tải lên</h3><p>hoặc</p><label class="btn">Chọn ảnh từ máy tính<input id="featured-upload" type="file" accept="image/*" hidden></label><small>JPG, PNG, WEBP · Tối đa 5MB</small><div id="upload-progress" class="upload-progress" hidden><i></i><span>Đang tải ảnh...</span></div></div></div>
        <footer><div id="selected-media-name">{{ $selectedImage?->name ?: 'Chưa chọn ảnh' }}</div><button class="btn" id="confirm-featured" type="button" @if(!$selectedImage) disabled @endif>Đặt làm ảnh đại diện</button></footer>
    </section>
</div>
@endsection
