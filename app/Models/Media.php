<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model
{
    protected $table = 'media';
    protected $fillable = ['name', 'file_name', 'path', 'disk', 'mime_type', 'size', 'alt_text'];
    protected $appends = ['url'];

    protected static function booted(): void
    {
        static::saving(function (Media $media): void {
            if (blank($media->alt_text)) $media->alt_text = static::makeAltText($media->name ?: $media->file_name);
        });
    }

    public static function makeAltText(?string $name): string
    {
        $clean=Str::of(pathinfo((string)$name, PATHINFO_FILENAME))->replace(['-','_'], ' ')->squish()->limit(115, '')->toString();
        if ($clean==='' || preg_match('/^(img|image|photo|dsc|screenshot)[\s-]*\d*$/i',$clean)) return 'Hình ảnh sản phẩm nông sản Việt Nam chất lượng';
        return 'Hình ảnh '.$clean.' – Nông sản Việt Nam';
    }

    public function getUrlAttribute(): string
    {
        return $this->disk === 'public' ? asset('storage/'.$this->path) : asset($this->path);
    }
}
