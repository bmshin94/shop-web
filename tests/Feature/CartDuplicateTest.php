<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartDuplicateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 장바구니 중복 상품 담기 로직 검증 (백엔드)
     */
    public function test_cart_duplicate_status_and_force_increment()
    {
        // 1. 회원 및 상품 생성 (팩토리가 있다고 가정)
        $member = Member::factory()->create();
        $product = Product::factory()->create(['name' => '테스트 상품', 'price' => 10000]);

        // 2. 첫 번째 상품 담기 (옵션 포함)
        $this->actingAs($member)->postJson('/cart', [
            'product_id' => $product->id,
            'color' => 'Red',
            'size' => 'M',
            'quantity' => 1
        ])->assertStatus(200)
          ->assertJson(['status' => 'success']);

        $this->assertEquals(1, Cart::where('member_id', $member->id)->count());
        $this->assertEquals(1, Cart::where('member_id', $member->id)->first()->quantity);

        // 3. 동일 상품 재담기 (중복 확인)
        $response = $this->actingAs($member)->postJson('/cart', [
            'product_id' => $product->id,
            'color' => 'Red',
            'size' => 'M',
            'quantity' => 1
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'duplicate',
                     'message' => '이미 장바구니에 동일한 상품이 있습니다. 수량을 추가하시겠습니까?'
                 ]);

        // 중복 시 줄이 새로 생기면 안 됨!
        $this->assertEquals(1, Cart::where('member_id', $member->id)->count());

        // 4. force 옵션과 함께 다시 담기 (수량 추가)
        $response = $this->actingAs($member)->postJson('/cart', [
            'product_id' => $product->id,
            'color' => 'Red',
            'size' => 'M',
            'quantity' => 1,
            'force' => true
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);

        // 수량이 1에서 2로 올라갔는지 확인! 
        $this->assertEquals(1, Cart::where('member_id', $member->id)->count());
        $this->assertEquals(2, Cart::where('member_id', $member->id)->first()->quantity);
    }
}
