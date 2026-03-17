<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 기존 데이터 초기화 (다대다 관계 테이블도 함께!)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('color_product')->truncate();
        DB::table('product_size')->truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. 색상 및 사이즈 데이터 가져오기
        $colors = Color::all();
        $sizes = Size::all();

        if ($colors->isEmpty() || $sizes->isEmpty()) {
            return;
        }

        // 3. 상품 100개 생성 (Factory 활용)
        Product::factory()->count(100)->create()->each(function ($product) use ($colors, $sizes) {
            // 4. 각 상품에 랜덤한 색상 연결 (1~4개)
            $product->colors()->attach(
                $colors->random(rand(1, 4))->pluck('id')->toArray()
            );

            // 5. 각 상품에 랜덤한 사이즈 연결 (2~6개)
            $product->sizes()->attach(
                $sizes->random(rand(2, 6))->pluck('id')->toArray()
            );
        });
    }
}
