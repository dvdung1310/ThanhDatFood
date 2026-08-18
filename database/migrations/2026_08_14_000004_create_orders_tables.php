<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('payment_order_code')->nullable()->unique();
            $table->string('customer_name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->text('address');
            $table->text('note')->nullable();
            $table->decimal('subtotal', 14, 0);
            $table->decimal('shipping_fee', 14, 0)->default(0);
            $table->decimal('total', 14, 0);
            $table->string('payment_method', 20);
            $table->string('payment_status', 30)->default('unpaid');
            $table->string('status', 30)->default('pending');
            $table->string('payos_payment_link_id')->nullable();
            $table->text('payos_checkout_url')->nullable();
            $table->string('payos_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->decimal('unit_price', 14, 0);
            $table->unsignedInteger('quantity');
            $table->decimal('line_total', 14, 0);
            $table->string('unit', 30)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); Schema::dropIfExists('orders'); }
};
