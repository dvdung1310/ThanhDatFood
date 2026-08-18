<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Category, Media, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $r) { $products=Product::with(['category','media'])->when($r->q,fn($q,$v)=>$q->where('name','like',"%$v%"))->latest()->paginate(15); return view('admin.products.index',compact('products')); }
    public function create() { return $this->form(new Product); }
    public function store(Request $r) { $p=Product::create($this->data($r)); $this->syncMedia($p,$r); return redirect()->route('admin.products.index')->with('success','Đã thêm sản phẩm.'); }
    public function edit(Product $product) { $product->load('media'); return $this->form($product); }
    public function update(Request $r, Product $product) { $product->update($this->data($r,$product)); $this->syncMedia($product,$r); return redirect()->route('admin.products.index')->with('success','Đã cập nhật sản phẩm.'); }
    public function destroy(Product $product) { $product->delete(); return back()->with('success','Đã xóa sản phẩm.'); }
    private function form(Product $product) { return view('admin.products.form',['product'=>$product,'categories'=>Category::where('is_active',true)->get(),'media'=>Media::latest()->get()]); }
    private function data(Request $r,?Product $p=null): array { $d=$r->validate(['category_id'=>'required|exists:categories,id','name'=>'required|max:180','slug'=>'nullable|max:200','sku'=>'required|max:60|unique:products,sku,'.($p?->id??'NULL'),'short_description'=>'nullable|max:500','description'=>'nullable','price'=>'required|numeric|min:0','original_price'=>'nullable|numeric|min:0|gte:price','unit'=>'required|max:30','origin'=>'nullable|max:150','stock'=>'required|integer|min:0','is_featured'=>'nullable|boolean','is_active'=>'nullable|boolean'],['original_price.gte'=>'Giá gốc phải lớn hơn hoặc bằng giá bán.','original_price.numeric'=>'Giá gốc phải là một số hợp lệ.','price.required'=>'Vui lòng nhập giá bán.','price.numeric'=>'Giá bán phải là một số hợp lệ.']); $d['slug']=Str::slug($d['slug']?:$d['name']); $d['is_featured']=$r->boolean('is_featured'); $d['is_active']=$r->boolean('is_active'); return $d; }
    private function syncMedia(Product $p,Request $r): void { $ids=array_values(array_unique($r->input('media_ids',[]))); $primary=(int)$r->input('primary_media_id', $ids[0]??0); $sync=[]; foreach($ids as $i=>$id)$sync[$id]=['mediable_type'=>Product::class,'sort_order'=>$i,'is_primary'=>(int)$id===$primary]; $p->media()->sync($sync); }
}
