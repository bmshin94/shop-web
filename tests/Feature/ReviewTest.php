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
    public function guest_cannot_access_review_list_page()
    {
        $response = $this->get(route('mypage.review'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function member_cannot_access_review_write_page_without_purchase()
    {
        // 1. 구매 내역이 없는 회원이 리뷰 작성 페이지에 접근할 때! ✨
        $this->actingAs($this->member);

        $response = $this->get(route('review.write', ['product_id' => $this->product->id]));

        // 상품 상세 페이지로 리다이렉트되고 에러 메시지가 있어야 함! 🔒
        $response->assertRedirect(route('product-detail', $this->product->slug));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function member_cannot_write_review_for_non_confirmed_order()
    {
        // 2. 주문은 했지만 '배송완료' 상태일 때 (구매확정 전!) ✨
        $this->actingAs($this->member);

        $order = Order::factory()->create([
            'member_id' => $this->member->id,
            'order_status' => '배송완료' // 구매확정이 아니야! 😊
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $this->product->id
        ]);

        $response = $this->get(route('review.write', ['product_id' => $this->product->id]));

        // 여전히 접근 불가! 🔒
        $response->assertRedirect(route('product-detail', $this->product->slug));
    }

    /** @test */
    public function member_can_write_review_after_order_confirmed()
    {
        // 3. 드디어 '구매확정'을 했을 때! 😍🎬🚀
        $this->actingAs($this->member);

        $order = Order::factory()->create([
            'member_id' => $this->member->id,
            'order_status' => '구매확정' // 찐 고객님 인증! ✨
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $this->product->id
        ]);

        $response = $this->get(route('review.write', ['product_id' => $this->product->id]));

        // 접근 성공! 200 OK! 💖🎬🚀
        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    /** @test */
    public function member_cannot_store_review_without_confirmed_order()
    {
        // 4. 실제로 저장(POST) 요청을 보낼 때도 구매확정 여부를 체크하는지! ✨🔒
        $this->actingAs($this->member);

        $response = $this->postJson(route('review.store'), [
            'product_id' => $this->product->id,
            'rating' => 5,
            'title' => '구매도 안 했는데 리뷰?',
            'content' => '이런 건 막아야지! 😤',
        ]);

        // 403 Forbidden 응답이 와야 함! 🚫
        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function member_can_store_review_with_confirmed_order()
    {
        // 5. '구매확정' 상태에서 실제로 리뷰 저장 성공! 😍🎬🚀 ✅
        $this->actingAs($this->member);

        $order = Order::factory()->create([
            'member_id' => $this->member->id,
            'order_status' => '구매확정'
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $this->product->id
        ]);

        $response = $this->postJson(route('review.store'), [
            'product_id' => $this->product->id,
            'rating' => 5,
            'title' => '정말 만족스러운 상품!',
            'content' => '에스파 카리나도 추천하는 찐 아이템입니다! 💖✨',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('reviews', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'title' => '정말 만족스러운 상품!',
        ]);
    }
}
