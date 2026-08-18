<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Category, Media};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() { return view('admin.categories.index', ['categories' => Category::with(['image','parent'])->withCount('products')->orderBy('sort_order')->get()]); }
    public function create() { return view('admin.categories.form', ['category' => new Category, 'categories' => Category::all(), 'media' => Media::latest()->get()]); }
    public function store(Request $r) { $data=$this->data($r); Category::create($data); return redirect()->route('admin.categories.index')->with('success','Đã thêm danh mục.'); }
    public function edit(Category $category) { return view('admin.categories.form', ['category'=>$category, 'categories'=>Category::whereKeyNot($category->id)->get(), 'media'=>Media::latest()->get()]); }
    public function update(Request $r, Category $category) { $category->update($this->data($r,$category)); return redirect()->route('admin.categories.index')->with('success','Đã cập nhật danh mục.'); }
    public function destroy(Category $category)
    {
        abort_if($category->slug === 'chua-phan-loai', 422, 'Không thể xóa danh mục mặc định.');

        $movedProducts = DB::transaction(function () use ($category): int {
            $defaultCategory = Category::firstOrCreate(
                ['slug' => 'chua-phan-loai'],
                [
                    'name' => 'Chưa phân loại',
                    'description' => 'Danh mục mặc định dành cho các sản phẩm chưa được phân loại.',
                    'sort_order' => 9999,
                    'is_active' => true,
                ]
            );

            $movedProducts = $category->products()->update(['category_id' => $defaultCategory->id]);
            $category->delete();

            return $movedProducts;
        });

        $message = $movedProducts > 0
            ? "Đã xóa danh mục và chuyển {$movedProducts} sản phẩm sang Chưa phân loại."
            : 'Đã xóa danh mục.';

        return back()->with('success', $message);
    }
    private function data(Request $r, ?Category $category=null): array { $d=$r->validate(['name'=>'required|max:150','slug'=>'nullable|max:170','description'=>'nullable','parent_id'=>'nullable|exists:categories,id','image_id'=>'nullable|exists:media,id','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']); $d['slug']=Str::slug($d['slug'] ?: $d['name']); $d['is_active']=$r->boolean('is_active'); return $d; }
}
