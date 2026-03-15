<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 장바구니 목록 조회 및 금액 계산이 정확한지 테스트한다.
     */
    public function test_cart_index_calculates_totals_correctly(): void
    {
        // 1. 데이터 준비
        $member = Member::factory()->create();
        $product = Product::factory()->create(['price' => 20000, 'sale_price' => 15000]);
        
        Cart::create([
            'member_id' => $member->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // 2. 장바구니 페이지 접속
        $response = $this->actingAs($member)->get(route('cart.index'));

        // 3. 검증 (총 상품금액: 40,000 / 할인가: 30,000 / 배송비: 3,000 / 최종: 33,000)
        $response->assertStatus(200);
        $response->assertSee(number_format(40000));
        $response->assertSee(number_format(30000));
        $response->assertSee(number_format(3000));
    }

    /**
     * 장바구니 수량 업데이트가 정상 작동하는지 테스트한다.
     */
    public function test_cart_update_changes_quantity(): void
    {
        $member = Member::factory()->create();
        $cart = Cart::create([
            'member_id' => $member->id,
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($member)->put(route('cart.update', $cart), [
            'quantity' => 5
        ]);

        $response->assertStatus(200);
        $this->assertEquals(5, $cart->fresh()->quantity);
    }

    /**
     * 장바구니 삭제 기능이 정상 작동하는지 테스트한다.
     */
    public function test_cart_destroy_removes_item(): void
    {
        $member = Member::factory()->create();
        $cart = Cart::create([
            'member_id' => $member->id,
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($member)->delete(route('cart.destroy', $cart));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }
}
