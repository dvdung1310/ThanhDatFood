<?php

namespace App\Providers;

use App\Models\{Media,Setting};
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Schema,View};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $settings=[];
        try {
            if(Schema::hasTable('settings')){
                $settings=Setting::all()->mapWithKeys(fn($item)=>[$item->key=>$item->readableValue()])->all();
                $imageIds=collect(['home','products','news','contact'])->map(fn($page)=>$settings["seo.$page.image_id"]??null)->push($settings['site.favicon_image_id']??null)->filter()->unique();
                $images=Media::whereIn('id',$imageIds)->get()->keyBy('id');
                foreach(['home','products','news','contact'] as $page){$id=$settings["seo.$page.image_id"]??null;if($id && isset($images[$id]))$settings["seo.$page.image_url"]=$images[$id]->url;}
                $faviconId=$settings['site.favicon_image_id']??null;if($faviconId && isset($images[$faviconId]))$settings['site.favicon_url']=$images[$faviconId]->url;
                if(filled($settings['smtp.host']??null)){
                    config([
                        'mail.default'=>'smtp','mail.mailers.smtp.host'=>$settings['smtp.host'],'mail.mailers.smtp.port'=>(int)($settings['smtp.port']??587),
                        'mail.mailers.smtp.username'=>$settings['smtp.username']??null,'mail.mailers.smtp.password'=>$settings['smtp.password']??null,
                        'mail.mailers.smtp.scheme'=>($settings['smtp.encryption']??'tls')==='ssl'?'smtps':'smtp',
                        'mail.from.address'=>$settings['smtp.from_address']??config('mail.from.address'),'mail.from.name'=>$settings['smtp.from_name']??config('mail.from.name'),
                    ]);
                }
            }
        } catch(\Throwable) {}
        View::share('siteSettings',$settings);
    }
}
