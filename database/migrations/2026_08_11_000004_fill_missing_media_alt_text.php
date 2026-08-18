<?php

use App\Models\Media;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Media::query()->where(fn($q)=>$q->whereNull('alt_text')->orWhere('alt_text',''))->eachById(function(Media $media){$media->alt_text=Media::makeAltText($media->name ?: $media->file_name);$media->saveQuietly();});
    }
    public function down(): void {}
};
