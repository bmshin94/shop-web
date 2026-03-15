<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Exhibition;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExhibitionDetailFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 퀵 뷰 API가 상품 옵션 정보를 정확히 반환하는지 테스트한다.
     */
    public function test_quick_view_api_returns_product_options(): void
    {
        // 1. 테스트 데이터 생성 (상품, 색상, 사이즈)
        $product = Product::factory()->create([
            'name' => '테스트 상품',
            'price' => 10000,
            'sale_price' => 9000
        ]);

        $color = Color::create(['name' => '레드', 'code' => '#FF0000']);
        $size = Size::create(['name' => 'XL', 'sort_order' => 1]);

        $product->colors()->attach($color->id);
        $product->sizes()->attach($size->id);

        // 2. API 호출
        $response = $this->get(route('product-quick-view', $product->id));

        // 3. 검증
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $product->id,
            'name' => '테스트 상품',
            'colors' => [
                ['name' => '레드']
            ],
            'sizes' => [
                ['name' => 'XL']
            ]
        ]);
    }

    /**
     * 기획전 상세 페이지에서 멀티 선택 UI가 정상 노출되는지 테스트한다.
     */
    public function test_exhibition_detail_shows_selection_ui_for_active_exhibition(): void
    {
        $this->withoutExceptionHandling(); // 뷰 렌더링 에러를 찾아라! 

        // 1. 진행 중인 기획전 생성
        $exhibition = Exhibition::factory()->create(['status' => '진행중']);
        $product = Product::factory()->create();
        $exhibition->products()->attach($product->id);

        // 2. 페이지 접속
        $response = $this->get(route('exhibition.show', $exhibition->slug));

        // 3. 검증
        $response->assertStatus(200);
        $response->assertSee('전체 선택');
        $response->assertSee('product-checkbox');
        $response->assertSee('선택 상품 바로구매');
    }

    /**
     * 진행 예정인 기획전에서는 선택 UI가 나오지 않아야 한다.
     */
    public function test_exhibition_detail_hides_selection_ui_for_upcoming_exhibition(): void
    {
        // 1. 진행 예정 기획전 생성
        $exhibition = Exhibition::factory()->create(['status' => '진행예정']);
        
        // 2. 페이지 접속
        $response = $this->get(route('exhibition.show', $exhibition->slug));

        // 3. 검증
        $response->assertStatus(200);
        $response->assertDontSee('전체 선택');
        $response->assertSee('Coming Soon');
    }
}
