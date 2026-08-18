<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Banner,Media};
use Illuminate\Http\{RedirectResponse,Request};
use Illuminate\View\View;
class BannerController extends Controller
{
    public function index(): View { return view('admin.banners.index',['banners'=>Banner::with('image')->orderBy('sort_order')->get()]); }
    public function create(): View { return $this->form(new Banner); }
    public function store(Request $request): RedirectResponse { Banner::create($this->data($request));return redirect()->route('admin.banners.index')->with('success','Đã thêm banner.'); }
    public function edit(Banner $banner): View { return $this->form($banner); }
    public function update(Request $request,Banner $banner): RedirectResponse { $banner->update($this->data($request));return redirect()->route('admin.banners.index')->with('success','Đã cập nhật banner.'); }
    public function destroy(Banner $banner): RedirectResponse { $banner->delete();return back()->with('success','Đã xóa banner.'); }
    private function form(Banner $banner): View { return view('admin.banners.form',['banner'=>$banner,'media'=>Media::latest()->get()]); }
    private function data(Request $request): array { $data=$request->validate(['image_id'=>'required|exists:media,id','placement'=>'required|in:home,about','display_mode'=>'required|in:with_text,image_only','eyebrow'=>'nullable|max:100','title'=>'nullable|max:180','subtitle'=>'nullable|max:180','description'=>'nullable|max:500','button_label'=>'nullable|max:80','button_url'=>'nullable|max:500','text_color'=>'nullable|in:dark,light','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);$data['title']=$data['title']??'';$data['text_color']=$data['text_color']??'dark';$data['is_active']=$request->boolean('is_active');return $data; }
}
