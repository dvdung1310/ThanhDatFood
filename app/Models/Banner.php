<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Banner extends Model
{
    protected $fillable=['image_id','placement','display_mode','eyebrow','title','subtitle','description','button_label','button_url','text_color','sort_order','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function image(): BelongsTo { return $this->belongsTo(Media::class); }
}
