<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable=['group','key','value','is_secret'];
    protected function casts(): array { return ['is_secret'=>'boolean']; }
    public function readableValue(): ?string { if(!$this->is_secret || blank($this->value))return $this->value; try{return Crypt::decryptString($this->value);}catch(\Throwable){return null;} }
    public static function put(string $group,string $key,mixed $value,bool $secret=false): void
    {
        $setting=static::firstOrNew(['key'=>$key]); $setting->group=$group; $setting->is_secret=$secret;
        if($secret && blank($value) && $setting->exists)return;
        $setting->value=$secret && filled($value)?Crypt::encryptString((string)$value):(is_bool($value)?($value?'1':'0'):(string)($value??'')); $setting->save();
    }
}
