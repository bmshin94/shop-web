<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 기존 상품 데이터 정리 (깔끔하게 새출발! )
        Product::query()->delete();

        // 2. 카테고리 정보 가져오기 
        $tops = Category::where('slug', 'tops')->first();
        $bottoms = Category::where('slug', 'bottoms')->first();
        $outer = Category::where('slug', 'outerwear')->first();
        $mats = Category::where('slug', 'mats-props')->first();
        $bottles = Category::where('slug', 'bottles-food')->first();
        $skincare = Category::where('slug', 'skincare')->first();
        $sun = Category::where('slug', 'sun-cooling')->first();
        $supple = Category::where('slug', 'supplements')->first();

        // 3. 상품 데이터 20개 생성 
        $items = [
            // 스포츠웨어 - 상의
            ['name' => '에어리 메쉬 브라탑', 'category' => $tops, 'price' => 45000, 'sale' => 39000],
            ['name' => '심리스 크롭 탑', 'category' => $tops, 'price' => 38000, 'sale' => null],
            ['name' => '테크니컬 러닝 티셔츠', 'category' => $tops, 'price' => 52000, 'sale' => 45000],
            
            // 스포츠웨어 - 하의
            ['name' => '하이웨이스트 시그니처 레깅스', 'category' => $bottoms, 'price' => 59000, 'sale' => 49000],
            ['name' => '에센셜 바이커 쇼츠', 'category' => $bottoms, 'price' => 35000, 'sale' => null],
            ['name' => '조거 플렉스 팬츠', 'category' => $bottoms, 'price' => 68000, 'sale' => 58000],
            ['name' => '컴포트 와이드 슬랙스', 'category' => $bottoms, 'price' => 75000, 'sale' => null],

            // 스포츠웨어 - 아우터
            ['name' => '라이트 윈드브레이커', 'category' => $outer, 'price' => 128000, 'sale' => 98000],
            ['name' => '슬림핏 후드 집업', 'category' => $outer, 'price' => 89000, 'sale' => null],

            // 홈트 & 용품
            ['name' => '프리미엄 TPE 요가매트', 'category' => $mats, 'price' => 42000, 'sale' => 35000],
            ['name' => '논슬립 코르크 요가매트', 'category' => $mats, 'price' => 65000, 'sale' => null],
            ['name' => '마사지 폼롤러 45cm', 'category' => $mats, 'price' => 28000, 'sale' => 19000],
            ['name' => '트라이탄 대용량 물통 1L', 'category' => $bottles, 'price' => 18000, 'sale' => null],
            ['name' => '단백질 쉐이커 보틀', 'category' => $bottles, 'price' => 15000, 'sale' => 12000],

            // 뷰티 & 케어
            ['name' => '울트라 하이드레이팅 크림', 'category' => $skincare, 'price' => 32000, 'sale' => 28000],
            ['name' => '카밍 시카 토너', 'category' => $skincare, 'price' => 25000, 'sale' => null],
            ['name' => '워터프루프 선 스틱', 'category' => $sun, 'price' => 22000, 'sale' => 18000],
            ['name' => '쿨링 릴리프 바디 미스트', 'category' => $sun, 'price' => 19000, 'sale' => null],
            ['name' => '여성용 멀티 비타민 (30일분)', 'category' => $supple, 'price' => 45000, 'sale' => 39000],
            ['name' => '비건 프로틴 파우더 1kg', 'category' => $supple, 'price' => 58000, 'sale' => 49000],
        ];

        $colors = \App\Models\Color::all();

        foreach ($items as $index => $item) {
            $product = Product::create([
                'category_id' => $item['category']->id ?? 1,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']) ?: str_replace(' ', '-', $item['name']),
                'price' => $item['price'],
                'sale_price' => $item['sale'],
                'stock_quantity' => rand(0, 100),
                'status' => (rand(1, 10) > 2) ? '판매중' : (rand(1, 2) == 1 ? '품절' : '숨김'),
                'is_new' => (rand(1, 10) > 7),
                'is_best' => (rand(1, 10) > 7),
                'description' => '이 상품은 ' . $item['name'] . '의 상세 설명입니다. 최고의 품질과 디자인을 경험해보세요.',
                // 랜덤 이미지 (Unsplash 활용!) 
                'image_url' => 'https://images.unsplash.com/photo-' . (1500000000000 + rand(1000000, 9999999)) . '?w=400&h=533&fit=crop',
            ]);

            // 랜덤하게 1~3개의 색상 연결 
            if ($colors->count() > 0) {
                $product->colors()->attach(
                    $colors->random(rand(1, min(3, $colors->count())))->pluck('id')->toArray()
                );
            }
        }
    }
}
