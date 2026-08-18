<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Category, Media, Post, Product};
class DashboardController extends Controller { public function __invoke() { return view('admin.dashboard', ['products' => Product::count(), 'categories' => Category::count(), 'posts' => Post::count(), 'media' => Media::count(), 'lowStock' => Product::with('category')->where('stock', '<', 10)->orderBy('stock')->take(6)->get()]); } }
