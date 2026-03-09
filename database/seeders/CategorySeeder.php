<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        // 1. 기존 데이터 정리 (중복 방지를 위해 깨끗하게! )
        Category::query()->delete();

        // 2. 대분류: 스포츠웨어 (ID: 1) 
        $sportswear = Category::create([
            'name' => '스포츠웨어',
            'slug' => 'activewear',
            'level' => 1,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 2-1. 하위: 상의 (Tops)
        Category::create([
            'name' => '상의 (Tops)',
            'slug' => 'tops',
            'parent_id' => $sportswear->id,
            'level' => 2,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 2-2. 하위: 하의 (Bottoms)
        Category::create([
            'name' => '하의 (Bottoms)',
            'slug' => 'bottoms',
            'parent_id' => $sportswear->id,
            'level' => 2,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 2-3. 하위: 아우터 (Outerwear)
        Category::create([
            'name' => '아우터 (Outerwear)',
            'slug' => 'outerwear',
            'parent_id' => $sportswear->id,
            'level' => 2,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 3. 대분류: 홈트 & 용품 (ID: 2) 
        $hometrain = Category::create([
            'name' => '홈트 & 용품',
            'slug' => 'home-training',
            'level' => 1,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 3-1. 하위: 요가매트/소도구
        Category::create([
            'name' => '요가매트/소도구',
            'slug' => 'mats-props',
            'parent_id' => $hometrain->id,
            'level' => 2,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 3-2. 하위: 물통/식단용품
        Category::create([
            'name' => '물통/식단용품',
            'slug' => 'bottles-food',
            'parent_id' => $hometrain->id,
            'level' => 2,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. 대분류: 뷰티 & 케어 (ID: 3) 
        $beauty = Category::create([
            'name' => '뷰티 & 케어',
            'slug' => 'beauty-care',
            'level' => 1,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 4-1. 하위: 스킨케어
        Category::create([
            'name' => '스킨케어',
            'slug' => 'skincare',
            'parent_id' => $beauty->id,
            'level' => 2,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4-2. 하위: 선케어/쿨링
        Category::create([
            'name' => '선케어/쿨링',
            'slug' => 'sun-cooling',
            'parent_id' => $beauty->id,
            'level' => 2,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4-3. 하위: 영양제/보조제
        Category::create([
            'name' => '영양제/보조제',
            'slug' => 'supplements',
            'parent_id' => $beauty->id,
            'level' => 2,
            'sort_order' => 3,
            'is_active' => true,
        ]);
    }

    /**
     * Run the database seeds. (Standard Laravel method name)
     */
    public function run(): void
    {
        $this->up();
    }
}
