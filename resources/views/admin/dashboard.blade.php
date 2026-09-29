@extends('layouts.admin')
@section('title','Tổng quan')
@section('content')
<div class="admin-title"><div><h1>Tổng quan</h1><p>Tình hình cửa hàng hôm nay.</p></div><a class="btn" href="{{ route('admin.products.create') }}"><i class="fa-solid fa-plus"></i> Thêm sản phẩm</a></div>
<div class="stats dashboard-stats">
    <a href="{{ route('admin.products.index') }}"><article><span><i class="fa-solid fa-box"></i></span><div><b>{{ $products }}</b><small>Sản phẩm</small></div><i class="fa-solid fa-arrow-right stat-arrow"></i></article></a>
    <a href="{{ route('admin.categories.index') }}"><article><span><i class="fa-solid fa-layer-group"></i></span><div><b>{{ $categories }}</b><small>Danh mục</small></div><i class="fa-solid fa-arrow-right stat-arrow"></i></article></a>
    <a href="{{ route('admin.posts.index') }}"><article><span><i class="fa-regular fa-newspaper"></i></span><div><b>{{ $posts }}</b><small>Bài viết</small></div><i class="fa-solid fa-arrow-right stat-arrow"></i></article></a>
    <a href="{{ route('admin.media.index') }}"><article><span><i class="fa-regular fa-image"></i></span><div><b>{{ $media }}</b><small>Ảnh trong thư viện</small></div><i class="fa-solid fa-arrow-right stat-arrow"></i></article></a>
</div>
<section class="panel"><div class="panel-head"><h2>Sản phẩm sắp hết hàng</h2><a href="{{ route('admin.products.index') }}">Quản lý sản phẩm →</a></div><div class="table-wrap"><table><thead><tr><th>Sản phẩm</th><th>Danh mục</th><th>Kho</th><th>Trạng thái</th><th></th></tr></thead><tbody>@forelse($lowStock as $p)<tr><td><b>{{ $p->name }}</b><small>{{ $p->sku }}</small></td><td>{{ $p->category->name }}</td><td>{{ $p->stock }} {{ $p->unit }}</td><td><span class="badge {{ $p->stock?'warn':'off' }}">{{ $p->stock?'Sắp hết':'Hết hàng' }}</span></td><td class="actions"><a href="{{ route('admin.products.edit',$p) }}">Xem sản phẩm →</a></td></tr>@empty<tr><td colspan="5">Kho hàng đang ổn định.</td></tr>@endforelse</tbody></table></div></section>
@endsection
