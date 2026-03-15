<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Exhibition;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 메인 페이지가 정상적으로 로드되는지 확인합니다. 🏠🎬✨
     */
    public function test_home_page_loads_successfully()
    {
        // 1. 필요한 기본 데이터 생성 (카테고리, 상품, 기획전 등)
        $category = Category::factory()->create(['level' => 1, 'name' => '야구']);
        
        $products = Product::factory()->count(10)->create([
            'category_id' => $category->id,
            'is_best' => true,
            'status' => 'selling'
        ]);

        $exhibition = Exhibition::factory()->create([
            'status' => '진행중',
            'start_at' => now()->subDays(1),
            'end_at' => now()->addDays(10)
        ]);

        // 2. 홈 페이지 요청! 🚀
        $response = $this->get(route('home'));

        // 3. 응답 결과 확인 ✨
        $response->assertStatus(200);
        $response->assertViewHas('heroExhibitions');
        $response->assertViewHas('editorsPicks');
        $response->assertViewHas('trendingProducts');
        $response->assertViewHas('topCategories');

        // 4. 화면에 데이터가 노출되는지 확인 (기초적인 수준)
        $response->assertSee("Editor's Pick", false); // false를 주면 이스케이프 무시하고 찾아요! ✨
        $response->assertSee('실시간 인기 급상승');
    }
}
