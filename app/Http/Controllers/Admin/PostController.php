<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Media, Post};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:220'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'per_page' => ['nullable', Rule::in(['10', '15', '25', '50'])],
        ]);
        $perPage = (int) ($filters['per_page'] ?? 15);
        $posts = Post::with(['author','featuredImage'])
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('title', 'like', "%{$term}%"))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('published_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('published_at', '<=', $date))
            ->latest('published_at')->latest('id')->paginate($perPage)->withQueryString();
        return view('admin.posts.index', compact('posts'));
    }
    public function create() { return $this->form(new Post); }
    public function store(Request $request)
    {
        $data = $this->data($request); $data['user_id'] = auth()->id();
        Post::create($data); return redirect()->route('admin.posts.index')->with('success','Đã tạo bài viết.');
    }
    public function edit(Post $post) { return $this->form($post); }
    public function update(Request $request, Post $post)
    {
        $post->update($this->data($request,$post)); return redirect()->route('admin.posts.index')->with('success','Đã cập nhật bài viết.');
    }
    public function destroy(Post $post) { $post->delete(); return back()->with('success','Đã xóa bài viết.'); }
    private function form(Post $post) { return view('admin.posts.form',['post'=>$post,'media'=>Media::latest()->get()]); }
    private function data(Request $request, ?Post $post=null): array
    {
        $data=$request->validate([
            'title'=>'required|max:220','slug'=>['nullable','max:240',Rule::unique('posts','slug')->ignore($post?->id)],
            'excerpt'=>'nullable|max:700','content'=>'required','featured_image_id'=>'nullable|exists:media,id',
            'status'=>['required',Rule::in(['draft','published'])],'published_at'=>'nullable|date',
        ]);
        $data['slug']=Str::slug($data['slug'] ?: $data['title']);
        validator(['slug'=>$data['slug']], ['slug'=>Rule::unique('posts','slug')->ignore($post?->id)])->validate();
        if($data['status']==='published' && empty($data['published_at'])) $data['published_at']=now();
        if($data['status']==='draft') $data['published_at']=null;
        return $data;
    }
}
