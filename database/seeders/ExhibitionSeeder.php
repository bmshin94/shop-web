<?php

namespace Database\Seeders;

use App\Models\Exhibition;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ExhibitionSeeder extends Seeder
{
    /**
     * 기획전 샘플 데이터를 20건 생성하고 상품을 연결한다.
     */
    public function run(): void
    {
        Exhibition::query()->delete();

        // 20개의 기획전 생성 
        $exhibitions = Exhibition::factory()
            ->count(20)
            ->create();

        $products = Product::all();

        if ($products->count() > 0) {
            foreach ($exhibitions as $exhibition) {
                // 각 기획전에 랜덤 상품 4~8개 연결 
                $randomProducts = $products->random(min($products->count(), rand(4, 8)));
                
                foreach ($randomProducts as $index => $product) {
                    $exhibition->products()->attach($product->id, [
                        'sort_order' => $index + 1
                    ]);
                }
            }
        }
    }
}
