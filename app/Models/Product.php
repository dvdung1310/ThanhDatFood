<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'sku', 'short_description', 'description', 'price', 'original_price', 'unit', 'origin', 'is_featured', 'is_active', 'stock'];
    protected function casts(): array { return ['price' => 'decimal:0', 'original_price' => 'decimal:0', 'is_featured' => 'boolean', 'is_active' => 'boolean']; }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function media(): BelongsToMany { return $this->belongsToMany(Media::class, 'mediables', 'mediable_id', 'media_id')->wherePivot('mediable_type', self::class)->withPivot('sort_order', 'is_primary')->orderByPivot('sort_order'); }
    public function getPrimaryImageAttribute(): ?Media { return $this->media->firstWhere('pivot.is_primary', true) ?? $this->media->first(); }
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->original_price || $this->original_price <= $this->price) return null;
        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
}
