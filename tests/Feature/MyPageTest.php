<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 로그인한 사용자가 마이페이지에 접근할 수 있는지 테스트합니다.
     */
    public function test_authenticated_member_can_access_mypage(): void
    {
        $member = Member::factory()->create([
            'name' => '관리자',
            'points' => 5000,
            'level' => 'VIP'
        ]);

        $response = $this->actingAs($member)->get(route('mypage'));

        $response->assertStatus(200);
        $response->assertSee('관리자');
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
        $product = Product::factory()->create(['name' => 'Active Women 응원봉']);
        
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
        $response->assertSee('Active Women 응원봉');
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
     * 마이페이지 취소/반품/교환 내역 페이지가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_mypage_cancel_list_page_shows_merged_data(): void
    {
        $member = Member::factory()->create();
        $product1 = Product::factory()->create(['name' => '취소상품']);
        $product2 = Product::factory()->create(['name' => '반품상품']);
        
        // 1. 주문 취소 건 생성 (CheckoutService를 통해 클레임 자동 생성 확인) 🔄
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'ORD-CANCEL-123',
            'order_status' => '주문접수'
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'product_name' => $product1->name,
            'unit_price' => 10000,
            'quantity' => 1,
            'line_total' => 10000
        ]);

        // CheckoutService를 사용하여 취소 처리 실행! 
        app(\App\Services\CheckoutService::class)->cancelOrder($order, '테스트 취소');

        // 2. 교환/반품 신청 건 생성 (OrderClaim)
        $order2 = Order::factory()->create(['member_id' => $member->id, 'order_number' => 'ORD-CLAIM-456']);
        $orderItem2 = OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product2->id,
            'product_name' => $product2->name,
            'unit_price' => 20000,
            'quantity' => 1,
            'line_total' => 20000
        ]);

        $claim = \App\Models\OrderClaim::factory()->create([
            'member_id' => $member->id,
            'order_id' => $order2->id,
            'claim_number' => 'CLM-TEST-789',
            'type' => 'return',
            'status' => '접수'
        ]);
        \App\Models\OrderClaimItem::create([
            'order_claim_id' => $claim->id,
            'order_item_id' => $orderItem2->id,
            'quantity' => 1
        ]);

        // 취소/반품 내역 조회
        $response = $this->actingAs($member)->get(route('mypage.cancel-list'));

        $response->assertStatus(200);
        
        // 주문 취소 건 확인 (이제 CAN- 번호로 나옴)
        $response->assertSee('CAN-ORD-CANCEL-123');
        $response->assertSee('취소상품');
        $response->assertSee('취소'); // 유형 뱃지 텍스트 확인
        
        // 교환/반품 신청 건 확인
        $response->assertSee('CLM-TEST-789');
        $response->assertSee('반품상품');
        $response->assertSee('반품신청'); // 유형 뱃지 텍스트 확인
        $response->assertSee('접수'); // 상태 확인
    }

    /**
     * 마이페이지 취소/교환/반품 내역 필터링 테스트 (유형 및 상태)
     */
    public function test_mypage_cancel_list_filters(): void
    {
        $member = Member::factory()->create();
        
        // 공통 상품 생성
        $product = Product::factory()->create();

        // 1. 주문 취소 건 (Type: cancel, Status: 완료)
        $order1 = Order::factory()->create(['member_id' => $member->id]);
        $item1 = OrderItem::create(['order_id' => $order1->id, 'product_id' => $product->id, 'product_name' => '상품1', 'unit_price' => 1000, 'quantity' => 1, 'line_total' => 1000]);
        $claim1 = \App\Models\OrderClaim::factory()->create([
            'member_id' => $member->id,
            'order_id' => $order1->id,
            'claim_number' => 'CAN-101',
            'type' => 'cancel',
            'status' => '완료'
        ]);
        \App\Models\OrderClaimItem::create(['order_claim_id' => $claim1->id, 'order_item_id' => $item1->id, 'quantity' => 1]);

        // 2. 반품 신청 건 (Type: return, Status: 거부)
        $order2 = Order::factory()->create(['member_id' => $member->id]);
        $item2 = OrderItem::create(['order_id' => $order2->id, 'product_id' => $product->id, 'product_name' => '상품2', 'unit_price' => 2000, 'quantity' => 1, 'line_total' => 2000]);
        $claim2 = \App\Models\OrderClaim::factory()->create([
            'member_id' => $member->id,
            'order_id' => $order2->id,
            'claim_number' => 'RET-202',
            'type' => 'return',
            'status' => '거부'
        ]);
        \App\Models\OrderClaimItem::create(['order_claim_id' => $claim2->id, 'order_item_id' => $item2->id, 'quantity' => 1]);

        // 3. 교환 신청 건 (Type: exchange, Status: 접수)
        $order3 = Order::factory()->create(['member_id' => $member->id]);
        $item3 = OrderItem::create(['order_id' => $order3->id, 'product_id' => $product->id, 'product_name' => '상품3', 'unit_price' => 3000, 'quantity' => 1, 'line_total' => 3000]);
        $claim3 = \App\Models\OrderClaim::factory()->create([
            'member_id' => $member->id,
            'order_id' => $order3->id,
            'claim_number' => 'EXC-303',
            'type' => 'exchange',
            'status' => '접수'
        ]);
        \App\Models\OrderClaimItem::create(['order_claim_id' => $claim3->id, 'order_item_id' => $item3->id, 'quantity' => 1]);

        // --- 테스트 실행 ---

        // A. 유형 필터 (반품만 조회)
        $response = $this->actingAs($member)->get(route('mypage.cancel-list', ['type' => 'return']));
        $response->assertSee('RET-202');
        $response->assertDontSee('CAN-101');
        $response->assertDontSee('EXC-303');

        // B. 상태 필터 (거부된 건만 조회)
        $response = $this->actingAs($member)->get(route('mypage.cancel-list', ['status' => '거부']));
        $response->assertSee('RET-202');
        $response->assertDontSee('CAN-101');
        $response->assertDontSee('EXC-303');

        // C. 복합 필터 (교환이면서 접수인 건 조회)
        $response = $this->actingAs($member)->get(route('mypage.cancel-list', ['type' => 'exchange', 'status' => '접수']));
        $response->assertSee('EXC-303');
        $response->assertDontSee('RET-202');
        $response->assertDontSee('CAN-101');

        // D. 검색어 필터 (신청번호 검색)
        $response = $this->actingAs($member)->get(route('mypage.cancel-list', ['search' => 'CAN-101']));
        $response->assertSee('CAN-101');
        $response->assertDontSee('RET-202');
        $response->assertDontSee('EXC-303');
    }

    /**
     * 구매확정 처리가 정상적으로 이루어지는지 테스트합니다.
     */
    public function test_member_can_confirm_purchase(): void
    {
        $member = Member::factory()->create();
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'ORD-CONFIRM-TEST',
            'order_status' => '배송완료'
        ]);

        $response = $this->actingAs($member)->post(route('mypage.order-confirm', ['order_number' => $order->order_number]));

        $response->assertStatus(200);
        $response->assertJson(['message' => '구매확정이 완료되었습니다! 적립금이 지급되었어요. 💖']);
        
        $this->assertEquals('구매확정', $order->fresh()->order_status);
    }

    /**
     * 배송완료 상태가 아닐 때 구매확정 시 실패하는지 테스트합니다.
     */
    public function test_member_cannot_confirm_purchase_if_not_delivered(): void
    {
        $member = Member::factory()->create();
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'ORD-CONFIRM-FAIL',
            'order_status' => '배송중'
        ]);

        $response = $this->actingAs($member)->post(route('mypage.order-confirm', ['order_number' => $order->order_number]));

        $response->assertStatus(422);
        $response->assertJson(['message' => '배송완료 상태에서만 구매확정이 가능합니다.']);
        
        $this->assertEquals('배송중', $order->fresh()->order_status);
    }
}
