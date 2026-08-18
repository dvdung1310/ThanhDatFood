<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed();
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->get('/san-pham')->assertOk()->assertSee('Nông sản Việt Nam');
        $this->get('/san-pham/'.Product::first()->slug)->assertOk();
        $this->get('/tin-tuc-su-kien')->assertOk()->assertSee('Hành trình đưa nông sản Việt');
        $this->get('/tin-tuc-su-kien/hanh-trinh-nong-san-viet')->assertOk();
    }

    public function test_admin_can_access_management_pages(): void
    {
        $this->seed();
        $admin = User::where('is_admin', true)->firstOrFail();
        Storage::fake('public');

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/products')->assertOk();
        $this->actingAs($admin)->get('/admin/banners')->assertOk();
        $this->actingAs($admin)->get('/admin/products/'.Product::firstOrFail()->id.'/edit')->assertOk()->assertSee('product-media-modal');
        $this->actingAs($admin)->get('/admin/categories')->assertOk();
        $this->actingAs($admin)->get('/admin/media')->assertOk();
        $this->actingAs($admin)->get('/admin/posts')->assertOk();
        $this->actingAs($admin)->get('/admin/posts/create')->assertOk()->assertSee('post-content');
        $this->actingAs($admin)->post('/admin/posts', [
            'title'=>'Bài viết kiểm thử CKEditor','slug'=>'bai-viet-kiem-thu-ckeditor',
            'excerpt'=>'Nội dung mô tả kiểm thử','content'=>'<h2>Tiêu đề đẹp</h2><p>Nội dung từ CKEditor.</p>',
            'status'=>'published','published_at'=>'',
        ])->assertRedirect('/admin/posts');
        $this->assertDatabaseHas('posts',['slug'=>'bai-viet-kiem-thu-ckeditor','status'=>'published']);
        $this->get('/tin-tuc-su-kien/bai-viet-kiem-thu-ckeditor')->assertOk()->assertSee('Tiêu đề đẹp');
        $upload=$this->actingAs($admin)->postJson('/admin/media/upload',['upload'=>UploadedFile::fake()->image('anh-bai-viet.jpg',1200,800)]);
        $upload->assertOk()->assertJsonStructure(['url','default','media'=>['id','name','url']]);
        $this->assertDatabaseHas('media',['file_name'=>'anh-bai-viet.jpg','alt_text'=>'Hình ảnh anh bai viet – Nông sản Việt Nam']);
        $this->actingAs($admin)->get('/admin/settings')->assertOk()->assertSee('Cấu hình SMTP');
        $this->actingAs($admin)->put('/admin/settings',[
            'site_name'=>'Thành Đạt Việt Nam',
            'seo'=>['home'=>['title'=>'Nông sản Thành Đạt','description'=>'Nông sản Việt chất lượng'],'products'=>[],'news'=>[],'contact'=>[]],
            'smtp'=>['host'=>'smtp.example.com','port'=>587,'username'=>'mailer','password'=>'secret-test','encryption'=>'tls','from_address'=>'mail@example.com','from_name'=>'Thành Đạt'],
            'menu'=>['home'=>['label'=>'Trang chính','enabled'=>1],'products'=>['label'=>'Sản phẩm','enabled'=>1],'agriculture'=>['label'=>'Nông sản','enabled'=>1],'noodles'=>['label'=>'Bún miến','enabled'=>1],'spices'=>['label'=>'Gia vị','enabled'=>1],'news'=>['label'=>'Tin mới','enabled'=>1],'contact'=>['label'=>'Liên hệ','enabled'=>1]],
        ])->assertRedirect();
        $this->assertDatabaseHas('settings',['key'=>'seo.home.title','value'=>'Nông sản Thành Đạt']);
    }

    public function test_deleting_a_category_moves_its_products_to_the_default_category(): void
    {
        $this->seed();
        $admin = User::where('is_admin', true)->firstOrFail();
        $category = Category::whereHas('products')->firstOrFail();
        $productIds = $category->products()->pluck('id');

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect();

        $defaultCategory = Category::where('slug', 'chua-phan-loai')->firstOrFail();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertSame($productIds->count(), Product::whereIn('id', $productIds)->where('category_id', $defaultCategory->id)->count());
    }

    public function test_contact_form_is_saved_emailed_and_visible_to_admin(): void
    {
        $this->seed();
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn An',
            'phone' => '0912345678',
            'email' => 'an@example.com',
            'message' => 'Tôi cần tư vấn hợp tác cung ứng nông sản.',
        ]);

        $response->assertRedirect()->assertSessionHas('contact_success');
        $this->assertDatabaseHas('contact_messages', ['phone' => '0912345678', 'status' => 'new']);
        Mail::assertSent(\App\Mail\ContactMessageNotification::class);

        $admin = User::where('is_admin', true)->firstOrFail();
        $message = \App\Models\ContactMessage::firstOrFail();
        $this->actingAs($admin)->get(route('admin.contact-messages.index'))->assertOk()->assertSee('0912345678');
        $this->actingAs($admin)->get(route('admin.contact-messages.show', $message))->assertOk()->assertSee('hợp tác cung ứng');
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'status' => 'read']);
    }

    public function test_customer_can_place_a_cod_order_and_admin_can_view_it(): void
    {
        $this->seed();
        $product=Product::where('is_active',true)->where('stock','>',0)->firstOrFail();
        $this->get(route('orders.checkout',$product->slug))->assertOk()->assertSee('payment_method');
        $response=$this->post(route('orders.store',$product->slug),['quantity'=>2,'customer_name'=>'Nguyễn Văn An','phone'=>'0912345678','email'=>'an@example.com','address'=>'12 Nguyễn Huệ, Quận 1, TP.HCM','note'=>'Giao giờ hành chính','payment_method'=>'cod']);
        $order=Order::with('items')->firstOrFail();
        $response->assertRedirect(route('orders.success',$order));
        $this->assertSame('confirmed',$order->status);
        $this->assertSame((int)$product->price*2,(int)$order->total);
        $this->assertDatabaseHas('order_items',['order_id'=>$order->id,'product_id'=>$product->id,'quantity'=>2]);
        $admin=User::where('is_admin',true)->firstOrFail();
        $this->actingAs($admin)->get(route('admin.orders.index'))->assertOk()->assertSee($order->code);
        $this->actingAs($admin)->get(route('admin.orders.show',$order))->assertOk()->assertSee('0912345678');
    }
}
