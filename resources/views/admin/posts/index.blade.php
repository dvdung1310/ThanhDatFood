@extends('layouts.admin')
@section('title','Kiến thức & Tin tức')
@section('content')
<style>
    .post-filters{display:grid;grid-template-columns:minmax(240px,1fr) 170px 170px 150px auto auto;gap:12px;align-items:end;margin-bottom:22px}
    .post-filters label{display:flex;flex-direction:column;gap:7px;color:var(--muted);font-size:10px;font-weight:700;text-transform:uppercase}
    .post-filters input,.post-filters select{height:42px;border:1px solid var(--line);padding:0 12px;background:#fff;font:12px inherit;outline:none}
    .post-filters input:focus,.post-filters select:focus{border-color:#2875f3;box-shadow:0 0 0 3px #2875f312}
    .post-filter-reset{height:42px;display:flex;align-items:center;padding:0 14px;color:var(--muted);font-size:12px}
    @media(max-width:1000px){.post-filters{grid-template-columns:1fr 1fr 1fr}.post-filters label:first-child{grid-column:span 2}}
    @media(max-width:600px){.post-filters{grid-template-columns:1fr 1fr}.post-filters label:first-child{grid-column:span 2}.post-filters .btn{width:100%}}
</style>
<div class="admin-title"><div><h1>Kiến thức &amp; Tin tức</h1><p>Quản lý nội dung hiển thị trên website.</p></div><a class="btn" href="{{ route('admin.posts.create') }}">＋ Viết bài mới</a></div>
<section class="panel">
    <form class="post-filters" method="get" action="{{ route('admin.posts.index') }}">
        <label>Tìm bài viết<input name="q" value="{{ request('q') }}" placeholder="Nhập tiêu đề bài viết..."></label>
        <label>Từ ngày<input type="date" name="date_from" value="{{ request('date_from') }}"></label>
        <label>Đến ngày<input type="date" name="date_to" value="{{ request('date_to') }}"></label>
        <label>Hiển thị mỗi trang<select name="per_page">@foreach([10,15,25,50] as $size)<option value="{{ $size }}" @selected((int)request('per_page',15)===$size)>{{ $size }} bài</option>@endforeach</select></label>
        <button class="btn" type="submit">Lọc dữ liệu</button>
        @if(request()->hasAny(['q','date_from','date_to','per_page']))<a class="post-filter-reset" href="{{ route('admin.posts.index') }}">Đặt lại</a>@endif
    </form>
    @error('date_to')<div class="alert error">Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.</div>@enderror
    <div class="table-wrap"><table><thead><tr><th>Bài viết</th><th>Tác giả</th><th>Ngày xuất bản</th><th>Trạng thái</th><th></th></tr></thead><tbody>
    @forelse($posts as $post)<tr><td><div class="table-product">@if($post->featuredImage)<img src="{{ $post->featuredImage->url }}" alt="">@endif<div><b>{{ $post->title }}</b><small>/{{ $post->slug }}</small></div></div></td><td>{{ $post->author?->name ?: '—' }}</td><td>{{ $post->published_at?->format('d/m/Y H:i') ?: '—' }}</td><td><span class="badge {{ $post->status==='published'?'on':'warn' }}">{{ $post->status==='published'?'Đã đăng':'Bản nháp' }}</span></td><td class="actions">@if($post->status==='published')<a href="{{ route('news.show',$post->slug) }}" target="_blank">Xem</a>@endif<a href="{{ route('admin.posts.edit',$post) }}">Sửa</a><form method="post" action="{{ route('admin.posts.destroy',$post) }}" onsubmit="return confirm('Xóa bài viết này?')">@csrf @method('delete')<button>Xóa</button></form></td></tr>
    @empty<tr><td colspan="5" class="empty">Không tìm thấy bài viết phù hợp.</td></tr>@endforelse
    </tbody></table></div>
    {{ $posts->links() }}
</section>
@endsection
