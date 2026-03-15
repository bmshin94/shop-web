<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartToCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 장바구니 ID를 파라미터로 전달했을 때 결제 페이지가 정상적으로 로드되는지 테스트한다.
     */
    public function test_checkout_page_loads_with_cart_ids(): void
    {
        // 1. 데이터 준비
        $member = Member::factory()->create();
        $product = Product::factory()->create(['name' => '장바구니상품', 'price' => 10000]);
        $cart = Cart::create([
            'member_id' => $member->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'color' => '블랙',
            'size' => 'M'
        ]);

        // 2. 장바구니 기반 결제 페이지 요청
        $response = $this->actingAs($member)
            ->get(route('checkout', ['cart_ids' => $cart->id]));

        // 3. 검증
        $response->assertStatus(200);
        $response->assertSee('장바구니상품');
        $response->assertSee('블랙');
        $response->assertSee('M');
        $response->assertSee(number_format(20000)); // 10000 * 2
    }
}
