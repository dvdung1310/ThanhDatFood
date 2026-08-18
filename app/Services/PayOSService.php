<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayOSService
{
    private array $credentials;
    public function __construct()
    {
        $saved=Setting::whereIn('key',['payos.client_id','payos.api_key','payos.checksum_key'])->get()->mapWithKeys(fn($item)=>[$item->key=>$item->readableValue()])->all();
        $this->credentials=['payos.client_id'=>$saved['payos.client_id']??config('services.payos.client_id'),'payos.api_key'=>$saved['payos.api_key']??config('services.payos.api_key'),'payos.checksum_key'=>$saved['payos.checksum_key']??config('services.payos.checksum_key')];
    }
    public function configured(): bool { return filled($this->credentials['payos.client_id']??null)&&filled($this->credentials['payos.api_key']??null)&&filled($this->credentials['payos.checksum_key']??null); }
    public function createPaymentLink(array $data): array
    {
        if(!$this->configured()) throw new RuntimeException('PayOS chưa được cấu hình.');
        $signatureData=['amount'=>$data['amount'],'cancelUrl'=>$data['cancelUrl'],'description'=>$data['description'],'orderCode'=>$data['orderCode'],'returnUrl'=>$data['returnUrl']];
        $data['signature']=$this->signature($signatureData);
        $response=Http::acceptJson()->withHeaders(['x-client-id'=>$this->credentials['payos.client_id'],'x-api-key'=>$this->credentials['payos.api_key']])->post('https://api-merchant.payos.vn/v2/payment-requests',$data);
        $json=$response->json();
        if(!$response->successful()||($json['code']??null)!=='00') throw new RuntimeException($json['desc']??'Không thể tạo liên kết thanh toán PayOS.');
        return $json['data'];
    }
    public function verifyWebhook(array $payload): bool
    {
        if(!$this->configured()||!isset($payload['data'],$payload['signature'])||!is_array($payload['data'])) return false;
        return hash_equals($this->signature($payload['data']),$payload['signature']);
    }
    private function signature(array $data): string
    {
        ksort($data);
        $text=collect($data)->map(fn($value,$key)=>$key.'='.(is_bool($value)?($value?'true':'false'):(is_array($value)?json_encode($value,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):(string)($value??''))))->implode('&');
        return hash_hmac('sha256',$text,$this->credentials['payos.checksum_key']);
    }
}
