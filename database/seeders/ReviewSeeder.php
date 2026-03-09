<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\Member;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 기존 리뷰 데이터 정리
        Review::query()->delete();

        // 2. 상품 데이터 가져오기
        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        // 3. 회원 데이터 확인 및 생성
        if (Member::count() === 0) {
            Member::factory()->count(10)->create();
        }
        $members = Member::all();

        // 4. 상품별로 랜덤하게 리뷰 생성하기
        foreach ($products as $product) {
            // 상품마다 5~20개의 리뷰를 랜덤으로 생성!
            $reviewCount = rand(5, 20);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                Review::create([
                    'product_id' => $product->id,
                    'member_id' => $members->random()->id,
                    'rating' => rand(3, 5),
                    'title' => '정말 마음에 들어요 ' . ($i + 1),
                    'content' => '이 상품(' . $product->name . ')은 정말 최고예요! 배송도 빠르고 품질도 너무 좋아요. 다음에도 꼭 여기서 구매할게요!',
                    'images' => (rand(1, 10) > 7) ? ['https://images.unsplash.com/photo-' . (1500000000000 + rand(1000000, 9999999)) . '?w=400&h=533&fit=crop'] : null,
                ]);
            }
        }
    }
}
