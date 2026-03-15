<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkCheckoutOptionValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 옵션이 필수인 상품을 포함하여 일괄 구매 시도 시, 서버 측에서 차단되는지 테스트한다.
     */
    public function test_bulk_checkout_fails_if_option_required_product_included(): void
    {
        // 1. 데이터 준비
        $member = Member::factory()->create();
        $productNoOption = Product::factory()->create(['name' => '옵션없는상품']);
        $productWithOption = Product::factory()->create(['name' => '옵션필수상품']);
        
        // 색상 옵션 추가
        $color = Color::create(['name' => '블랙', 'code' => '#000000']);
        $productWithOption->colors()->attach($color->id);

        // 2. 일괄 구매 시도 (옵션 선택 없이 ID만 전달)
        $response = $this->actingAs($member)
            ->from(route('exhibition.index')) // 이전 페이지 설정
            ->get(route('checkout', [
                'direct_product_ids' => "{$productNoOption->id},{$productWithOption->id}"
            ]));

        // 3. 검증: 뒤로 튕겨야 함 (리다이렉트)
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * 옵션이 없는 상품들만 일괄 구매 시에는 정상적으로 결제 페이지가 열리는지 테스트한다.
     */
    public function test_bulk_checkout_succeeds_for_products_without_options(): void
    {
        $member = Member::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $response = $this->actingAs($member)
            ->get(route('checkout', [
                'direct_product_ids' => "{$product1->id},{$product2->id}"
            ]));

        $response->assertStatus(200);
        $response->assertSee($product1->name);
        $response->assertSee($product2->name);
    }
}
