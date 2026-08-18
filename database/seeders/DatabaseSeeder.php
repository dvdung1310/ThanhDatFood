<?php

namespace Database\Seeders;

use App\Models\{Category, Media, Product, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email'=>'admin@nongsanviet.vn'],['name'=>'Quản trị viên','password'=>Hash::make('Admin@123'),'is_admin'=>true]);
        $images=[];
        foreach(['trai-cay'=>'Trái cây Việt','rau-cu'=>'Rau củ tươi','gao'=>'Gạo & ngũ cốc','dac-san'=>'Đặc sản vùng miền','hat'=>'Các loại hạt','gia-vi'=>'Gia vị Việt'] as $file=>$name){
            $images[$file]=Media::create(['name'=>$name,'file_name'=>$file.'.svg','path'=>'images/products/'.$file.'.svg','disk'=>'asset','mime_type'=>'image/svg+xml','size'=>0,'alt_text'=>$name]);
        }
        $categoryData=[
            ['Trái cây','trai-cay','Trái cây tươi theo mùa từ những vùng trồng nổi tiếng.'],['Rau củ','rau-cu','Rau củ sạch, thu hoạch trong ngày.'],['Gạo & ngũ cốc','gao','Hạt ngọc Việt từ những cánh đồng trù phú.'],['Đặc sản vùng miền','dac-san','Hương vị độc đáo từ khắp ba miền.'],['Các loại hạt','hat','Dinh dưỡng lành mạnh từ thiên nhiên.'],['Gia vị Việt','gia-vi','Gia vị nguyên bản cho bữa cơm đậm đà.']
        ];
        $categories=[]; foreach($categoryData as $i=>$c)$categories[$c[1]]=Category::create(['name'=>$c[0],'slug'=>$c[1],'description'=>$c[2],'image_id'=>$images[$c[1]]->id,'sort_order'=>$i]);
        $products=[
            ['trai-cay','Bưởi da xanh Bến Tre','NSV-BUOI-01',85000,'quả','Bến Tre',32,true,'Bưởi ruột hồng, tép mọng nước, vị ngọt thanh và rất ít hạt.'],
            ['trai-cay','Cam sành Hà Giang','NSV-CAM-01',49000,'kg','Hà Giang',45,true,'Cam chín tự nhiên, mọng nước, vị chua ngọt hài hòa.'],
            ['rau-cu','Rau cải ngọt Đà Lạt','NSV-RAU-01',28000,'bó','Đà Lạt',18,true,'Rau non tươi giòn, canh tác theo tiêu chuẩn an toàn.'],
            ['rao-cu','Cà rốt hữu cơ','NSV-CAROT-01',42000,'kg','Đà Lạt',22,false,'Cà rốt giòn ngọt, màu sắc tự nhiên, giàu dinh dưỡng.'],
            ['gao','Gạo ST25 Sóc Trăng','NSV-GAO-01',185000,'túi 5kg','Sóc Trăng',60,true,'Hạt gạo dài, cơm dẻo thơm và vị ngọt hậu đặc trưng.'],
            ['gao','Gạo lứt đỏ Điện Biên','NSV-GAO-02',72000,'kg','Điện Biên',28,false,'Gạo lứt dẻo bùi, giữ nguyên lớp cám giàu chất xơ.'],
            ['dac-san','Mật ong hoa cà phê','NSV-MAT-01',165000,'chai 500ml','Đắk Lắk',12,true,'Mật ong nguyên chất, thơm dịu hương hoa cà phê.'],
            ['hat','Hạt điều rang muối','NSV-DIEU-01',145000,'hộp 500g','Bình Phước',35,true,'Hạt điều loại 1, rang giòn cùng muối biển vừa vị.'],
            ['gia-vi','Tiêu đen Phú Quốc','NSV-TIEU-01',89000,'hũ 250g','Phú Quốc',25,true,'Hạt tiêu chắc, cay nồng và thơm lâu.'],
            ['dac-san','Trà sen Tây Hồ','NSV-TRA-01',220000,'hộp 200g','Hà Nội',8,false,'Trà xanh ướp gạo sen thủ công, hương thanh tao.'],
        ];
        foreach($products as $i=>$p){$key=$p[0]==='rao-cu'?'rau-cu':$p[0];$product=Product::create(['category_id'=>$categories[$key]->id,'name'=>$p[1],'slug'=>Str::slug($p[1]),'sku'=>$p[2],'price'=>$p[3],'unit'=>$p[4],'origin'=>$p[5],'stock'=>$p[6],'is_featured'=>$p[7],'short_description'=>$p[8],'description'=>$p[8]."\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng."]);$product->media()->attach($images[$key]->id,['mediable_type'=>Product::class,'sort_order'=>0,'is_primary'=>true]);}
        $this->call(NewsSeeder::class);
    }
}
