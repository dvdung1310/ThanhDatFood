<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index() { return view('admin.media.index',['media'=>Media::latest()->paginate(24)]); }
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
    public function destroy(Media $medium) { abort_if(\DB::table('mediables')->where('media_id',$medium->id)->exists() || \DB::table('categories')->where('image_id',$medium->id)->exists() || \DB::table('posts')->where('featured_image_id',$medium->id)->exists(),422,'Ảnh đang được sử dụng.'); if($medium->disk==='public')Storage::disk('public')->delete($medium->path); $medium->delete(); return back()->with('success','Đã xóa ảnh.'); }
}
