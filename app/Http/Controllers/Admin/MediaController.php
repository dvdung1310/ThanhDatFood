<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 20);
        if (! in_array($perPage, [12, 20, 40, 60], true)) $perPage = 20;
        return view('admin.media.index', [
            'media' => Media::latest()->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
        ]);
    }
    public function store(Request $r) { $r->validate(['files'=>'required','files.*'=>'image|max:5120','alt_text'=>'nullable|max:180']); foreach($r->file('files') as $file){$name=Str::uuid().'.'.$file->extension();$path=$file->storeAs('media/'.date('Y/m'),$name,'public');Media::create(['name'=>pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME),'file_name'=>$file->getClientOriginalName(),'path'=>$path,'disk'=>'public','mime_type'=>$file->getMimeType(),'size'=>$file->getSize(),'alt_text'=>$r->alt_text]);} return back()->with('success','Đã tải ảnh lên thư viện.'); }
    public function upload(Request $request)
    {
        $request->validate(['upload'=>'required|image|max:5120','alt_text'=>'nullable|max:180']);
        $file=$request->file('upload'); $name=Str::uuid().'.'.$file->extension();
        $path=$file->storeAs('media/'.date('Y/m'),$name,'public');
        $media=Media::create(['name'=>pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME),'file_name'=>$file->getClientOriginalName(),'path'=>$path,'disk'=>'public','mime_type'=>$file->getMimeType(),'size'=>$file->getSize(),'alt_text'=>$request->alt_text]);
        return response()->json(['url'=>$media->url,'default'=>$media->url,'media'=>['id'=>$media->id,'name'=>$media->name,'url'=>$media->url,'alt_text'=>$media->alt_text]]);
    }
    public function update(Request $r, Media $medium) { $medium->update($r->validate(['name'=>'required|max:150','alt_text'=>'nullable|max:180'])); return back()->with('success','Đã cập nhật ảnh.'); }
    public function destroy(Media $medium)
    {
        $usedBy = collect([
            DB::table('mediables')->where('media_id', $medium->id)->exists() ? 'sản phẩm' : null,
            DB::table('categories')->where('image_id', $medium->id)->exists() ? 'danh mục' : null,
            DB::table('posts')->where('featured_image_id', $medium->id)->exists() ? 'bài viết' : null,
            DB::table('banners')->where('image_id', $medium->id)->exists() ? 'banner' : null,
            DB::table('settings')->whereIn('key', ['site.favicon_image_id', 'seo.home.image_id', 'seo.products.image_id', 'seo.news.image_id', 'seo.contact.image_id'])
                ->where('value', (string) $medium->id)->exists() ? 'cấu hình website' : null,
        ])->filter()->values();

        if ($usedBy->isNotEmpty()) {
            return back()->with('error', 'Không thể xóa ảnh vì đang được sử dụng tại: '.$usedBy->join(', ').'. Hãy thay hoặc gỡ ảnh ở các mục này trước.');
        }

        $disk = $medium->disk;
        $path = $medium->path;
        $medium->delete();
        if ($disk === 'public') Storage::disk('public')->delete($path);
        return back()->with('success', 'Đã xóa ảnh.');
    }
}
