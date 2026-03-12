<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use DatabaseTransactions;

    protected $member;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::factory()->create();
        $this->product = Product::factory()->create();
    }

    /** @test */
    public function member_can_toggle_wishlist()
    {
        $this->actingAs($this->member);

        // 1. 찜 추가
        $response = $this->postJson(route('wishlist.toggle', $this->product));
        
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'added',
                'count' => 1
            ]);
        
        $this->assertDatabaseHas('wishlists', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id
        ]);

        // 2. 찜 해제
        $response = $this->postJson(route('wishlist.toggle', $this->product));
        
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'removed',
                'count' => 0
            ]);
        
        $this->assertDatabaseMissing('wishlists', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id
        ]);
    }

    /** @test */
    public function guest_cannot_toggle_wishlist()
    {
        $response = $this->postJson(route('wishlist.toggle', $this->product));
        $response->assertStatus(401);
    }
}
