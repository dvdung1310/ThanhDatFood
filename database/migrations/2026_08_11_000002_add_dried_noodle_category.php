<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('categories')->updateOrInsert(['slug' => 'pho-kho-bun-mien-mi'], [
            'name' => 'Phở khô, Bún, Miến, Mì', 'description' => 'Các loại phở khô, bún, miến và mì truyền thống Việt Nam.',
            'is_active' => true, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }
    public function down(): void
    {
        $category = DB::table('categories')->where('slug', 'pho-kho-bun-mien-mi')->first();
        if ($category && !DB::table('products')->where('category_id', $category->id)->exists()) DB::table('categories')->where('id', $category->id)->delete();
    }
};
