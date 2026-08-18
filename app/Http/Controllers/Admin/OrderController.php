<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\{RedirectResponse,Request};
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders=Order::with('items')->when($request->status,fn($q,$status)=>$q->where('status',$status))->when($request->q,fn($q,$term)=>$q->where(fn($x)=>$x->where('code','like',"%$term%")->orWhere('customer_name','like',"%$term%")->orWhere('phone','like',"%$term%")))->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index',compact('orders'));
    }
    public function show(Order $order): View { $order->load('items.product');return view('admin.orders.show',compact('order')); }
    public function update(Request $request,Order $order): RedirectResponse
    {
        $data=$request->validate(['status'=>'required|in:pending,confirmed,processing,shipping,completed,cancelled,payment_failed','payment_status'=>'required|in:unpaid,paid,refunded']);
        if($data['payment_status']==='paid'&&!$order->paid_at)$data['paid_at']=now();
        $order->update($data);
        return back()->with('success','Đã cập nhật đơn hàng.');
    }
}
