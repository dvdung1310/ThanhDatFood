<?php
namespace App\Http\Controllers;

use App\Models\{Order,Product};
use App\Services\PayOSService;
use Illuminate\Http\{JsonResponse,RedirectResponse,Request};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function checkout(Product $product): View
    {
        abort_unless($product->is_active&&$product->stock>0,404);
        $product->load('media');
        return view('orders.checkout',compact('product'));
    }
    public function store(Request $request,Product $product,PayOSService $payOS): RedirectResponse
    {
        abort_unless($product->is_active&&$product->stock>0,404);
        $data=$request->validate([
            'quantity'=>'required|integer|min:1|max:'.$product->stock,
            'customer_name'=>'required|string|max:150','phone'=>['required','regex:/^(0|\+84)[0-9]{9,10}$/'],'email'=>'nullable|email|max:180',
            'address'=>'required|string|max:500','note'=>'nullable|string|max:1000','payment_method'=>'required|in:cod,payos',
        ],['phone.regex'=>'Số điện thoại không đúng định dạng.','quantity.max'=>'Số lượng đặt vượt quá tồn kho.']);
        $total=(int)$product->price*(int)$data['quantity'];
        $order=DB::transaction(function()use($data,$product,$total){
            $order=Order::create(['code'=>'TD-'.now()->format('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)),0,6)),'customer_name'=>$data['customer_name'],'phone'=>$data['phone'],'email'=>$data['email']??null,'address'=>$data['address'],'note'=>$data['note']??null,'subtotal'=>$total,'shipping_fee'=>0,'total'=>$total,'payment_method'=>$data['payment_method'],'payment_status'=>'unpaid','status'=>$data['payment_method']==='cod'?'confirmed':'pending']);
            $order->update(['payment_order_code'=>now()->timestamp*1000+$order->id%1000]);
            $order->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'sku'=>$product->sku,'unit_price'=>$product->price,'quantity'=>$data['quantity'],'line_total'=>$total,'unit'=>$product->unit]);
            return $order;
        });
        if($data['payment_method']==='cod') return redirect()->route('orders.success',$order)->with('success','Đặt hàng thành công. Chúng tôi sẽ liên hệ xác nhận sớm.');
        try{
            $payment=$payOS->createPaymentLink(['orderCode'=>$order->payment_order_code,'amount'=>(int)$order->total,'description'=>'DH'.$order->id,'items'=>[['name'=>mb_substr($product->name,0,100),'quantity'=>(int)$data['quantity'],'price'=>(int)$product->price]],'cancelUrl'=>route('orders.cancel',$order),'returnUrl'=>route('orders.return',$order)]);
            $order->update(['payos_payment_link_id'=>$payment['paymentLinkId']??$payment['id']??null,'payos_checkout_url'=>$payment['checkoutUrl']??null]);
            return redirect()->away($payment['checkoutUrl']);
        }catch(\Throwable $e){report($e);$order->update(['status'=>'payment_failed']);return redirect()->route('orders.success',$order)->withErrors(['payment'=>'Không thể mở PayOS: '.$e->getMessage().' Bạn có thể liên hệ cửa hàng và đọc mã đơn để được hỗ trợ.']);}
    }
    public function success(Order $order): View { return view('orders.success',compact('order')); }
    public function returned(Request $request,Order $order): RedirectResponse
    {
        return redirect()->route('orders.success',$order);
    }
    public function cancel(Order $order): RedirectResponse { if($order->payment_status!=='paid')$order->update(['status'=>'cancelled']);return redirect()->route('orders.success',$order)->withErrors(['payment'=>'Thanh toán đã bị hủy.']); }
    public function webhook(Request $request,PayOSService $payOS): JsonResponse
    {
        $payload=$request->all();
        if(!$payOS->verifyWebhook($payload))return response()->json(['message'=>'Invalid signature'],400);
        $data=$payload['data'];
        $order=Order::where('payment_order_code',$data['orderCode']??null)->first();
        if($order&&($data['code']??null)==='00')$order->update(['payment_status'=>'paid','status'=>'confirmed','paid_at'=>$order->paid_at??now(),'payos_payment_link_id'=>$data['paymentLinkId']??$order->payos_payment_link_id,'payos_reference'=>$data['reference']??null]);
        return response()->json(['success'=>true]);
    }
}
