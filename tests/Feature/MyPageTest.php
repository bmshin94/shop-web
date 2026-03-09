<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MyPageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 로그인한 사용자가 마이페이지에 접근할 수 있는지 테스트합니다.
     */
    public function test_authenticated_member_can_access_mypage(): void
    {
        $member = Member::factory()->create([
            'name' => '카리나',
            'points' => 5000,
            'level' => 'VIP'
        ]);

        $response = $this->actingAs($member)->get(route('mypage'));

        $response->assertStatus(200);
        $response->assertSee('카리나');
        $response->assertSee('VIP 등급');
        $response->assertSee('5,000'); // 포인트 확인
    }

    /**
     * 로그인하지 않은 사용자가 마이페이지 접근 시 로그인 페이지로 리다이렉트 되는지 테스트합니다.
     */
    public function test_guest_cannot_access_mypage(): void
    {
        $response = $this->get(route('mypage'));

        $response->assertRedirect(route('login'));
    }

    /**
     * 마이페이지에서 주문 통계가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_mypage_shows_correct_order_stats(): void
    {
        $member = Member::factory()->create();
        
        // 결제대기 주문 2건 생성
        Order::factory()->count(2)->create([
            'member_id' => $member->id,
            'payment_status' => '결제대기'
        ]);

        // 배송완료 주문 3건 생성
        Order::factory()->count(3)->create([
            'member_id' => $member->id,
            'order_status' => '배송완료'
        ]);

        $response = $this->actingAs($member)->get(route('mypage'));

        $response->assertStatus(200);
        $response->assertSee('2'); // 입금대기 수
        $response->assertSee('3'); // 배송완료 수
    }

    /**
     * 마이페이지에서 최근 주문 내역이 표시되는지 테스트합니다.
     */
    public function test_mypage_shows_recent_orders(): void
    {
        $member = Member::factory()->create();
        $product = Product::factory()->create(['name' => '에스파 응원봉']);
        
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'ORD-20260309-TEST',
            'total_amount' => 55000
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 55000,
            'quantity' => 1,
            'line_total' => 55000
        ]);

        $response = $this->actingAs($member)->get(route('mypage'));

        $response->assertStatus(200);
        $response->assertSee('ORD-20260309-TEST');
        $response->assertSee('에스파 응원봉');
        $response->assertSee('55,000');
    }

    /**
     * 마이페이지 주문 목록 페이지가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_mypage_order_list_page(): void
    {
        $member = Member::factory()->create();
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'ORD-LIST-TEST',
            'order_status' => '배송완료'
        ]);
        $product = Product::factory()->create(['name' => '레깅스']);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 30000,
            'quantity' => 1,
            'line_total' => 30000
        ]);

        $response = $this->actingAs($member)->get(route('mypage.order-list'));

        $response->assertStatus(200);
        $response->assertSee('ORD-LIST-TEST');
        $response->assertSee('레깅스');
        $response->assertSee('배송완료');
    }

    /**
     * 마이페이지 주문 목록에서 상태 필터가 작동하는지 테스트합니다.
     */
    public function test_mypage_order_list_filter_by_status(): void
    {
        $member = Member::factory()->create();
        $product = Product::factory()->create();
        
        // 1. 배송중 주문
        $order1 = Order::factory()->create(['member_id' => $member->id, 'order_status' => '배송중', 'order_number' => 'ORD-SHIPPING']);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 10000,
            'quantity' => 1,
            'line_total' => 10000
        ]);
        
        // 2. 배송완료 주문
        $order2 = Order::factory()->create(['member_id' => $member->id, 'order_status' => '배송완료', 'order_number' => 'ORD-DELIVERED']);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 10000,
            'quantity' => 1,
            'line_total' => 10000
        ]);

        // 배송중만 필터링해서 조회
        $response = $this->actingAs($member)->get(route('mypage.order-list', ['status' => '배송중']));

        $response->assertStatus(200);
        $response->assertSee('ORD-SHIPPING');
        $response->assertDontSee('ORD-DELIVERED');
    }
}
