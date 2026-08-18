@extends('layouts.admin')
@section('title', $product->exists ? 'Sửa sản phẩm' : 'Thêm sản phẩm')
@section('content')
@php
    $selectedIds = array_map('intval', old('media_ids', $product->media->pluck('id')->all()));
    $primaryId = (int) old('primary_media_id', $product->primary_image?->id);
    $selectedMedia = $media->whereIn('id', $selectedIds);
@endphp
<div class="admin-title"><div><h1>{{ $product->exists ? 'Sửa sản phẩm' : 'Thêm sản phẩm' }}</h1><p>Điền thông tin và quản lý ảnh sản phẩm từ thư viện dùng chung.</p></div><a class="admin-back-btn" href="{{ route('admin.products.index') }}"><i class="fa-solid fa-arrow-left"></i> Quay lại</a></div>
@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<form class="editor" method="post" action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}">
    @csrf @if($product->exists) @method('put') @endif
    <div class="product-editor-shell">
    <section class="panel form-grid product-editor-main">
        <label>Tên sản phẩm *<input name="name" value="{{ old('name',$product->name) }}" required></label>
        <label>Danh mục *<select name="category_id" required><option value="">Chọn danh mục</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$product->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select></label>
        <label>Mã SKU *<input name="sku" value="{{ old('sku',$product->sku) }}" required></label>
        <label>Đường dẫn<input name="slug" value="{{ old('slug',$product->slug) }}" placeholder="Tự tạo theo tên"></label>
        <label>Giá bán (đ) *<input class="@error('price') input-error @enderror" type="number" name="price" min="0" value="{{ old('price',$product->price) }}" required><small class="hint">Giá khách hàng thực trả.</small>@error('price')<small class="field-error">{{ $message }}</small>@enderror</label>
        <label>Giá gốc (đ)<input class="@error('original_price') input-error @enderror" type="number" name="original_price" min="0" value="{{ old('original_price',$product->original_price) }}" placeholder="Để trống nếu không giảm giá"><small class="hint">Chỉ nhập khi sản phẩm có giảm giá.</small>@error('original_price')<small class="field-error">{{ $message }}</small>@enderror</label>
        <label>Đơn vị *<input name="unit" value="{{ old('unit',$product->unit ?: 'kg') }}" required></label>
        <label>Xuất xứ<input name="origin" value="{{ old('origin',$product->origin) }}"></label>
        <label>Tồn kho<input type="number" name="stock" min="0" value="{{ old('stock',$product->stock ?? 0) }}" required></label>
        <label class="span-2">Mô tả ngắn<textarea name="short_description" rows="2">{{ old('short_description',$product->short_description) }}</textarea></label>
        <label class="span-2">Nội dung chi tiết</label><div class="span-2"><textarea id="product-description" name="description" data-upload-url="{{ route('admin.media.upload') }}">{{ old('description',$product->description) }}</textarea></div>

    </section>
    <aside class="product-editor-sidebar">
        <section class="panel product-media-field">
            <div class="picker-head"><div><b>Ảnh sản phẩm</b><p class="hint">Chọn nhiều ảnh và đặt một ảnh làm ảnh đại diện.</p></div><button class="btn secondary product-media-open" type="button">Chọn / chỉnh sửa ảnh</button></div>
            <div id="product-media-inputs">@foreach($selectedIds as $id)<input type="hidden" name="media_ids[]" value="{{ $id }}">@endforeach<input type="hidden" name="primary_media_id" value="{{ $primaryId }}"></div>
            <div id="product-media-preview" class="product-media-preview {{ $selectedMedia->isEmpty() ? 'empty' : '' }}">
                @forelse($selectedMedia as $image)<figure data-preview-id="{{ $image->id }}"><img src="{{ $image->url }}" alt="{{ $image->alt_text }}"><figcaption>{{ $image->name }}</figcaption>@if($primaryId === $image->id)<span>Ảnh đại diện</span>@endif</figure>@empty<div><b>Chưa có ảnh sản phẩm</b><small>Nhấn “Chọn / chỉnh sửa ảnh” để mở thư viện.</small></div>@endforelse
            </div>
        </section>
        <section class="panel product-publish-box"><h3>Hiển thị sản phẩm</h3><label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$product->is_featured))> Sản phẩm nổi bật</label><label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product->exists ? $product->is_active : true))> Đang hiển thị</label></section>
    </aside>
    </div>
    <div class="form-actions"><a href="{{ route('admin.products.index') }}">Hủy</a><button class="btn">Lưu sản phẩm</button></div>
</form>

<div id="product-media-modal" class="media-modal" data-upload-url="{{ route('admin.media.upload') }}" aria-hidden="true">
    <div class="media-modal-backdrop" data-close-product-media></div>
    <section class="media-modal-dialog" role="dialog" aria-modal="true" aria-label="Chọn ảnh sản phẩm">
        <header><div><h2>Ảnh sản phẩm</h2><p>Chọn nhiều ảnh từ thư viện hoặc tải ảnh mới lên.</p></div><button type="button" data-close-product-media aria-label="Đóng">×</button></header>
        <div class="media-tabs"><button class="active" type="button" data-product-media-tab="library">Thư viện ảnh</button><button type="button" data-product-media-tab="upload">Tải ảnh mới</button></div>
        <div class="media-tab-pane active" data-product-media-pane="library"><div class="product-modal-help"><span>Nhấn vào ảnh để chọn hoặc bỏ chọn.</span><span>Nhấn biểu tượng ★ để đặt ảnh đại diện.</span></div><div id="product-modal-media-grid" class="modal-media-grid">@foreach($media as $image)<button type="button" class="modal-media-item product-modal-media-item {{ in_array($image->id,$selectedIds) ? 'selected' : '' }} {{ $primaryId === $image->id ? 'primary' : '' }}" data-media-id="{{ $image->id }}" data-media-url="{{ $image->url }}" data-media-name="{{ $image->name }}"><img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy"><span>{{ $image->name }}</span><i class="product-primary-mark" title="Đặt làm ảnh đại diện">★</i></button>@endforeach</div></div>
        <div class="media-tab-pane" data-product-media-pane="upload"><div class="wp-upload-box"><span>▧</span><h3>Thả ảnh vào đây để tải lên</h3><p>hoặc</p><label class="btn">Chọn ảnh từ máy tính<input id="product-media-upload" type="file" accept="image/*" multiple hidden></label><small>JPG, PNG, WEBP · Tối đa 5MB mỗi ảnh</small><div id="product-upload-progress" class="upload-progress" hidden><i></i><span>Đang tải ảnh...</span></div></div></div>
        <footer><div id="product-selected-count">Đã chọn {{ count($selectedIds) }} ảnh</div><button class="btn" id="confirm-product-media" type="button">Dùng các ảnh đã chọn</button></footer>
    </section>
</div>
@endsection
