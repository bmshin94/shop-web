<?php

namespace Database\Seeders;

use App\Models\Size;
use App\Models\SizeGroup;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 사이즈 그룹 생성
        $clothingGroup = SizeGroup::updateOrCreate(['name' => '의류']);
        $shoeGroup = SizeGroup::updateOrCreate(['name' => '신발']);

        // 2. 의류 사이즈 데이터
        $clothingSizes = [
            ['name' => 'S', 'sort_order' => 1, 'size_group_id' => $clothingGroup->id],
            ['name' => 'M', 'sort_order' => 2, 'size_group_id' => $clothingGroup->id],
            ['name' => 'L', 'sort_order' => 3, 'size_group_id' => $clothingGroup->id],
            ['name' => 'XL', 'sort_order' => 4, 'size_group_id' => $clothingGroup->id],
            ['name' => 'Free', 'sort_order' => 5, 'size_group_id' => $clothingGroup->id],
        ];

        // 3. 신발 사이즈 데이터 (230 ~ 280, 5단위)
        $shoeSizes = [];
        $order = 10;
        for ($size = 230; $size <= 280; $size += 5) {
            $shoeSizes[] = [
                'name' => (string)$size, 
                'sort_order' => $order++,
                'size_group_id' => $shoeGroup->id
            ];
        }

        foreach (array_merge($clothingSizes, $shoeSizes) as $data) {
            Size::updateOrCreate(['name' => $data['name']], $data);
        }

        // 4. 상품별 카테고리에 맞는 사이즈 연결 (랜덤)
        $products = Product::with('category')->get();
        
        foreach ($products as $product) {
            // 카테고리 이름이나 부모 카테고리 이름을 확인해서 그룹 결정 (간단한 매칭)
            $categoryName = $product->category->name ?? '';
            $parentName = $product->category->parent->name ?? '';
            
            if (str_contains($categoryName, '신발') || str_contains($parentName, '신발') || str_contains($categoryName, '운동화')) {
                $targetGroup = $shoeGroup;
            } else {
                $targetGroup = $clothingGroup;
            }

            $product->sizes()->sync(
                $targetGroup->sizes->random(rand(2, 4))->pluck('id')->toArray()
            );
        }
    }
}
