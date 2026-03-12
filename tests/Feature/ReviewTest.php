<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
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
    public function member_can_access_review_list_page()
    {
        $this->actingAs($this->member);

        // 작성 가능한 리뷰 데이터를 위해 주문 및 배송완료 처리 ✨
        $order = Order::factory()->create([
            'member_id' => $this->member->id,
            'order_status' => '배송완료'
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $this->product->id
        ]);

        $response = $this->get(route('mypage.review'));

        $response->assertStatus(200)
            ->assertSee('상품 리뷰 관리')
            ->assertViewHas('availableReviews')
            ->assertViewHas('writtenReviews');
    }

    /** @test */
    public function member_can_store_review()
    {
        $this->actingAs($this->member);

        $response = $this->postJson(route('review.store'), [
            'product_id' => $this->product->id,
            'rating' => 5,
            'title' => '좋은 상품입니다!',
            'content' => '정말 마음에 들어요. 추천합니다!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('reviews', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'title' => '좋은 상품입니다!',
        ]);
    }

    /** @test */
    public function guest_cannot_access_review_list_page()
    {
        $response = $this->get(route('mypage.review'));
        $response->assertRedirect(route('login'));
    }
}
