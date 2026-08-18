<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable=['code','payment_order_code','customer_name','phone','email','address','note','subtotal','shipping_fee','total','payment_method','payment_status','status','payos_payment_link_id','payos_checkout_url','payos_reference','paid_at'];
    protected function casts(): array { return ['subtotal'=>'decimal:0','shipping_fee'=>'decimal:0','total'=>'decimal:0','paid_at'=>'datetime']; }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
