<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
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
    public function member_can_toggle_wishlist()
    {
        $this->actingAs($this->member);

        // 1. 찜 추가
        $response = $this->postJson(route('wishlist.toggle', $this->product));
        
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'added',
                'wishlistCount' => 1 // wishlistCount 키 값 검증! 
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
                'wishlistCount' => 0
            ]);
        
        $this->assertDatabaseMissing('wishlists', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id
        ]);
    }

    /** @test */
    public function member_can_bulk_delete_wishlist_items()
    {
        $this->actingAs($this->member);

        // 상품 3개 찜하기
        $products = Product::factory()->count(3)->create();
        $wishlistIds = [];
        foreach ($products as $p) {
            $item = Wishlist::create([
                'member_id' => $this->member->id,
                'product_id' => $p->id
            ]);
            $wishlistIds[] = $item->id;
        }

        // 2개만 선택해서 삭제! ️
        $deleteIds = [$wishlistIds[0], $wishlistIds[1]];

        $response = $this->postJson(route('wishlist.delete-selected'), [
            'ids' => $deleteIds
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'wishlistCount' => 1 // 3개 중 2개 지웠으니까 1개 남아야 해! 
            ]);

        $this->assertDatabaseMissing('wishlists', ['id' => $wishlistIds[0]]);
        $this->assertDatabaseMissing('wishlists', ['id' => $wishlistIds[1]]);
        $this->assertDatabaseHas('wishlists', ['id' => $wishlistIds[2]]);
    }

    /** @test */
    public function wishlist_page_shows_paginated_products()
    {
        $this->actingAs($this->member);

        // 찜한 상품 15개 생성 (페이징 사이즈 12보다 많게!) 
        Product::factory()->count(15)->create()->each(function ($p) {
            Wishlist::create([
                'member_id' => $this->member->id,
                'product_id' => $p->id
            ]);
        });

        $response = $this->get(route('mypage.wishlist'));

        $response->assertStatus(200);
        
        // 페이징 처리 확인 (12개까지만 노출되는지!) 
        // wishlist-item 클래스가 12개 있는지 확인하는 로직은 복잡하니까,
        // 컨트롤러에서 넘어온 데이터의 페이징 정보를 체크할게!
        $this->assertEquals(12, $response->viewData('wishlist')->count());
        $this->assertEquals(15, $response->viewData('wishlist')->total());
        $this->assertTrue($response->viewData('wishlist')->hasMorePages());
    }

    /** @test */
    public function guest_cannot_toggle_wishlist()
    {
        $response = $this->postJson(route('wishlist.toggle', $this->product));
        $response->assertStatus(401);
    }
}
