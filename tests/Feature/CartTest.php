<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $member;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::factory()->create();
        $this->product = Product::factory()->create();
    }

    /** @test */
    public function member_can_add_to_cart()
    {
        $this->actingAs($this->member);

        $response = $this->postJson(route('cart.store'), [
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 2
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'cart_count' => 1
            ]);

        $this->assertDatabaseHas('carts', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 2
        ]);
    }

    /** @test */
    public function adding_same_product_option_shows_duplicate_warning()
    {
        $this->actingAs($this->member);

        // 1차 담기
        $this->postJson(route('cart.store'), [
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 1
        ]);

        // 2차 담기 (동일 옵션, force 없이)
        $response = $this->postJson(route('cart.store'), [
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 2
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'duplicate',
                'message' => '이미 장바구니에 동일한 상품이 있습니다. 수량을 추가하시겠습니까?'
            ]);
    }

    /** @test */
    public function adding_same_product_option_with_force_increments_quantity()
    {
        $this->actingAs($this->member);

        // 1차 담기
        $this->postJson(route('cart.store'), [
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 1
        ]);

        // 2차 담기 (동일 옵션, force 포함)
        $response = $this->postJson(route('cart.store'), [
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 2,
            'force' => true
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'cart_count' => 1
            ]);

        $this->assertDatabaseHas('carts', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'quantity' => 3 // 1 + 2
        ]);
    }

    /** @test */
    public function guest_cannot_add_to_cart()
    {
        $response = $this->postJson(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);
        $response->assertStatus(401);
    }
}
