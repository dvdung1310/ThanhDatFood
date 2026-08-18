<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['user_id','featured_image_id','title','slug','excerpt','content','status','published_at'];
    protected function casts(): array { return ['published_at' => 'datetime']; }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function featuredImage(): BelongsTo { return $this->belongsTo(Media::class, 'featured_image_id'); }
    public function scopePublished(Builder $query): Builder { return $query->where('status','published')->whereNotNull('published_at')->where('published_at','<=',now()); }
}
