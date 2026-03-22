<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prefixes = ['프리미엄', '에어리', '심리스', '테크니컬', '라이트', '컴포트', '시그니처', '에센셜', '데일리', '울트라'];
        $keywords = ['메쉬', '크롭', '러닝', '시카', '카밍', '하이웨이스트', '바이커', '플렉스', '윈드브레이커', '후드'];
        $suffixes = ['탑', '티셔츠', '레깅스', '쇼츠', '팬츠', '집업', '매트', '보틀', '크림', '파우더'];

        $name = $this->faker->randomElement($prefixes) . ' ' . 
                $this->faker->randomElement($keywords) . ' ' . 
                $this->faker->randomElement($suffixes);
        
        $price = $this->faker->numberBetween(1, 15) * 10000; // 10,000원 ~ 150,000원 단위
        $hasSale = $this->faker->boolean(40); // 40% 확률로 세일
        $salePrice = $hasSale ? $price * 0.8 : null; // 20% 할인

        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'name' => $name,
            'brief_description' => $this->faker->sentence(10),
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $price,
            'sale_price' => $salePrice,
            'stock_quantity' => $this->faker->numberBetween(0, 200),
            'status' => $this->faker->randomElement(['판매중', '판매중', '판매중', '품절', '숨김']),
            'image_url' => 'https://images.unsplash.com/photo-' . (1500000000000 + rand(1000000, 9999999)) . '?w=600&h=800&fit=crop',
            'is_new' => $this->faker->boolean(20),
            'is_best' => $this->faker->boolean(15),
            // is_recommend 제거!
        ];
    }
}
