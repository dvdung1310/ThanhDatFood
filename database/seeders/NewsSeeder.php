<?php

namespace Database\Seeders;

use App\Models\{Media, Post, User};
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $author=User::where('is_admin',true)->first(); $images=Media::pluck('id','path');
        $posts=[
            ['hanh-trinh-nong-san-viet','Hành trình đưa nông sản Việt từ ruộng vườn đến bàn ăn','Cùng gặp gỡ những người nông dân đang gìn giữ phương thức canh tác tử tế và tạo ra sản phẩm chất lượng.','images/products/rau-cu.svg'],
            ['cach-chon-gao-ngon','Cách chọn gạo ngon cho từng món ăn Việt','Mỗi giống gạo mang một độ dẻo, hương thơm riêng và phù hợp với những món ăn khác nhau.','images/products/gao.svg'],
            ['lich-nong-san-theo-mua','Lịch nông sản theo mùa bạn nên biết','Chọn đúng mùa để thưởng thức rau quả tươi ngon nhất, đồng thời ủng hộ nền nông nghiệp bền vững.','images/products/trai-cay.svg'],
        ];
        foreach($posts as $i=>$item) Post::updateOrCreate(['slug'=>$item[0]],['user_id'=>$author?->id,'featured_image_id'=>$images[$item[3]]??null,'title'=>$item[1],'excerpt'=>$item[2],'content'=>'<h2>Từ vùng nguyên liệu Việt Nam</h2><p>'.$item[2].'</p><p>Thành Đạt đồng hành cùng các nhà vườn và cơ sở sản xuất uy tín để lựa chọn nguồn nguyên liệu có xuất xứ rõ ràng, quy trình chăm sóc an toàn và chất lượng ổn định.</p><blockquote><p>Mỗi sản phẩm không chỉ mang hương vị quê hương mà còn chứa đựng tâm huyết của người làm nông.</p></blockquote><h2>Giá trị của sự tử tế</h2><ul><li>Nguồn gốc minh bạch và được tuyển chọn kỹ lưỡng.</li><li>Đóng gói cẩn thận, giữ trọn độ tươi ngon.</li><li>Đồng hành lâu dài và thu mua công bằng với nhà nông.</li></ul><p>Chúng tôi tin rằng khi người tiêu dùng lựa chọn nông sản Việt chất lượng, đó cũng là cách góp phần xây dựng một nền nông nghiệp bền vững hơn.</p>','status'=>'published','published_at'=>now()->subDays($i*3)]);
    }
}
