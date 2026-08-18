<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->boolean('is_admin')->default(false));
        Schema::create('media', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('file_name'); $table->string('path')->unique();
            $table->string('disk')->default('public'); $table->string('mime_type')->nullable(); $table->unsignedBigInteger('size')->default(0); $table->string('alt_text')->nullable(); $table->timestamps();
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete(); $table->foreignId('image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('name'); $table->string('slug')->unique(); $table->text('description')->nullable(); $table->boolean('is_active')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->foreignId('category_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->string('slug')->unique(); $table->string('sku')->unique();
            $table->text('short_description')->nullable(); $table->longText('description')->nullable(); $table->decimal('price', 14, 0); $table->string('unit')->default('kg'); $table->string('origin')->nullable(); $table->unsignedInteger('stock')->default(0); $table->boolean('is_featured')->default(false); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('mediables', function (Blueprint $table) {
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete(); $table->unsignedBigInteger('mediable_id'); $table->string('mediable_type'); $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_primary')->default(false);
            $table->primary(['media_id', 'mediable_id', 'mediable_type']); $table->index(['mediable_id', 'mediable_type']);
        });
    }
    public function down(): void { Schema::dropIfExists('mediables'); Schema::dropIfExists('products'); Schema::dropIfExists('categories'); Schema::dropIfExists('media'); Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin')); }
};
