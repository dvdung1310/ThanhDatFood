<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\{RedirectResponse,Request};
use Illuminate\View\View;
class PayOSSettingController extends Controller
{
    public function edit(): View { $settings=Setting::where('group','payos')->get()->mapWithKeys(fn($item)=>[$item->key=>$item->readableValue()])->all();return view('admin.settings.payos',compact('settings')); }
    public function update(Request $request): RedirectResponse { $data=$request->validate(['client_id'=>'required|max:255','api_key'=>'nullable|max:500','checksum_key'=>'nullable|max:500']);Setting::put('payos','payos.client_id',$data['client_id']);foreach(['api_key','checksum_key'] as $field)if(filled($data[$field]??null))Setting::put('payos','payos.'.$field,$data[$field],true);return back()->with('success','Đã lưu cấu hình PayOS.'); }
}
