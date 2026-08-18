<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Media,Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SettingController extends Controller
{
    public function edit()
    {
        $settings=Setting::all()->mapWithKeys(fn($item)=>[$item->key=>$item->readableValue()])->all();
        return view('admin.settings.edit',['settings'=>$settings,'media'=>Media::latest()->get()]);
    }
    public function update(Request $request)
    {
        $data=$request->validate([
            'site_name'=>'required|max:150','favicon_image_id'=>'nullable|exists:media,id','seo.*.title'=>'nullable|max:180','seo.*.description'=>'nullable|max:320','seo.*.image_id'=>'nullable|exists:media,id',
            'smtp.host'=>'nullable|max:180','smtp.port'=>'nullable|integer|min:1|max:65535','smtp.username'=>'nullable|max:180','smtp.password'=>'nullable|max:500','smtp.encryption'=>'nullable|in:tls,ssl,none','smtp.from_address'=>'nullable|email|max:180','smtp.from_name'=>'nullable|max:180',
            'payos.client_id'=>'nullable|max:255','payos.api_key'=>'nullable|max:500','payos.checksum_key'=>'nullable|max:500',
            'menu.*.label'=>'nullable|max:80','menu.*.enabled'=>'nullable|boolean',
        ]);
        Setting::put('general','site.name',$data['site_name']);
        Setting::put('general','site.favicon_image_id',$data['favicon_image_id']??null);
        foreach(['home','products','news','contact'] as $page){foreach(['title','description','image_id'] as $field)Setting::put('seo',"seo.$page.$field",data_get($data,"seo.$page.$field"));}
        foreach(['host','port','username','encryption','from_address','from_name'] as $field)Setting::put('smtp',"smtp.$field",data_get($data,"smtp.$field"));
        if (filled(data_get($data, 'smtp.password'))) Setting::put('smtp','smtp.password',data_get($data,'smtp.password'),true);
        Setting::put('payos','payos.client_id',data_get($data,'payos.client_id'));
        foreach(['api_key','checksum_key'] as $field)if(filled(data_get($data,"payos.$field")))Setting::put('payos',"payos.$field",data_get($data,"payos.$field"),true);
        foreach(['home','products','agriculture','noodles','spices','news','contact'] as $item){Setting::put('menu',"menu.$item.label",data_get($data,"menu.$item.label"));Setting::put('menu',"menu.$item.enabled",$request->boolean("menu.$item.enabled"));}
        return back()->with('success','Đã lưu cấu hình website.');
    }
    public function testMail(Request $request)
    {
        $data=$request->validate(['test_email'=>'required|email']);
        try {
            Mail::raw('Email kiểm tra cấu hình SMTP từ website Thành Đạt.', fn ($mail) => $mail->to($data['test_email'])->subject('Kiểm tra SMTP Thành Đạt'));
            return back()->with('success', 'Đã gửi email kiểm tra. Vui lòng kiểm tra hộp thư.');
        } catch (TransportExceptionInterface $e) {
            report($e);
            $message = str_contains($e->getMessage(), '535')
                ? 'Gmail từ chối đăng nhập. Hãy dùng địa chỉ Gmail đầy đủ làm tài khoản và Mật khẩu ứng dụng 16 ký tự, không dùng mật khẩu đăng nhập Gmail.'
                : 'Không thể kết nối máy chủ SMTP. Vui lòng kiểm tra host, cổng và chế độ bảo mật.';
            return back()->withErrors(['test_email' => $message]);
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['test_email' => 'Không thể gửi email kiểm tra. Vui lòng kiểm tra lại cấu hình SMTP.']);
        }
    }
}
