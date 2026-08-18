<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Post;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'banners' => Banner::with('image')->where('placement','home')->where('is_active',true)->orderBy('sort_order')->get(),
            'categories' => Category::with('image')->withCount(['products' => fn ($query) => $query->where('is_active', true)])->where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'featured' => Product::with(['category', 'media'])->where('is_active', true)->where('is_featured', true)->latest()->take(6)->get(),
            'latestProducts' => Product::with(['category', 'media'])->where('is_active', true)->latest()->take(6)->get(),
            'latestPosts' => Post::with('featuredImage')->published()->latest('published_at')->take(4)->get(),
        ]);
    }
    public function about()
    {
        return view('about', [
            'banners' => Banner::with('image')->where('placement', 'about')->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
    public function products(Request $request)
    {
        $products = Product::with(['category', 'media'])->where('is_active', true)
            ->when($request->category, fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->q, fn ($q, $term) => $q->where(fn ($x) => $x->where('name', 'like', "%$term%")->orWhere('origin', 'like', "%$term%")))
            ->latest()->paginate(12)->withQueryString();
        return view('products.index', ['products' => $products, 'categories' => Category::where('is_active', true)->orderBy('name')->get()]);
    }
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404); $product->load(['category', 'media']);
        $related = Product::with('media')->where('category_id', $product->category_id)->where('id', '!=', $product->id)->where('is_active', true)->take(4)->get();
        return view('products.show', compact('product', 'related'));
    }
    public function news()
    {
        $posts=Post::with(['featuredImage','author'])->published()->latest('published_at')->paginate(9);
        return view('news.index',compact('posts'));
    }
    public function post(Post $post)
    {
        abort_unless($post->status==='published' && $post->published_at?->isPast(),404);
        $post->load(['featuredImage','author']);
        $related=Post::with('featuredImage')->published()->whereKeyNot($post->id)->latest('published_at')->take(3)->get();
        return view('news.show',compact('post','related'));
    }
}
