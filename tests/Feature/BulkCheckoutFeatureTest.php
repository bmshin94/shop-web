<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkCheckoutFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 복수 상품 ID를 파라미터로 전달했을 때 결제 페이지가 정상적으로 열리는지 테스트한다.
     */
    public function test_checkout_page_loads_with_multiple_product_ids(): void
    {
        // 1. 로그인할 회원 및 테스트 상품 생성
        $member = Member::factory()->create();
        $product1 = Product::factory()->create(['price' => 10000, 'sale_price' => 8000]);
        $product2 = Product::factory()->create(['price' => 20000, 'sale_price' => 15000]);

        // 2. 복수 상품 구매 시도 (직접 URL 파라미터 전달 방식)
        $response = $this->actingAs($member)
            ->get(route('checkout', [
                'direct_product_ids' => "{$product1->id},{$product2->id}"
            ]));

        // 3. 검증
        $response->assertStatus(200);
        $response->assertSee($product1->name);
        $response->assertSee($product2->name);
        
        // 총 상품 금액 검증 (8000 + 15000 = 23000)
        $response->assertSee(number_format(23000));
    }

    /**
     * 상품 정보 없이 결제 페이지 접근 시 홈으로 리다이렉트되는지 테스트한다.
     */
    public function test_checkout_redirects_to_home_when_no_items_provided(): void
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($member)
            ->get(route('checkout'));

        $response->assertRedirect(route('home'));
    }
}
