<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MypageExchangeReturnTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 교환/반품 신청 폼 접근 테스트 (배송완료 상태)
     */
    public function test_member_can_access_exchange_return_form_when_delivered(): void
    {
        // 1. 회원 생성 및 로그인
        $member = Member::factory()->create();
        
        // 2. 상품 생성
        $product = Product::factory()->create([
            'image_url' => 'https://example.com/test.jpg'
        ]);

        // 3. 주문 생성 (배송완료 상태)
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'MONO-TEST-001',
            'order_status' => '배송완료',
        ]);

        // 4. 주문 상품 생성 (상품과 명시적 연결!)
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
        ]);

        // 5. 페이지 접속
        $response = $this->actingAs($member)
            ->get(route('mypage.exchange-return', $order->order_number));

        // 6. 검증
        $response->assertStatus(200);
        $response->assertViewIs('pages.mypage-exchange-return');
        $response->assertSee('교환/반품 신청');
        $response->assertSee('MONO-TEST-001');
    }

    /**
     * 배송완료가 아닌 상태에서 접근 시 차단 테스트
     */
    public function test_member_cannot_access_exchange_return_form_when_not_delivered(): void
    {
        $member = Member::factory()->create();
        $product = Product::factory()->create();
        
        // 주문접수 상태의 주문 생성 (ordered_at 필수!)
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'MONO-TEST-002',
            'order_status' => '주문접수',
            'ordered_at' => now(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($member)
            ->get(route('mypage.exchange-return', $order->order_number));

        // 주문 상세로 리다이렉트 및 에러 메시지 확인
        $response->assertRedirect(route('mypage.order-detail', $order->order_number));
        $response->assertSessionHas('error', '교환/반품 신청은 배송완료 상태에서만 가능합니다.');
    }

    /**
     * 다른 회원의 주문으로 접근 시 404 에러 테스트
     */
    public function test_member_cannot_access_others_exchange_return_form(): void
    {
        $member1 = Member::factory()->create();
        $member2 = Member::factory()->create();
        
        $orderOfMember2 = Order::factory()->create([
            'member_id' => $member2->id,
            'order_number' => 'MONO-TEST-003',
            'order_status' => '배송완료',
        ]);

        // member1이 member2의 주문으로 신청 폼 접근 시도
        $response = $this->actingAs($member1)
            ->get(route('mypage.exchange-return', $orderOfMember2->order_number));

        // 자신의 주문이 아니므로 찾을 수 없음 처리!
        $response->assertStatus(404);
    }

    /**
     * 비로그인 사용자 접근 제한 테스트
     */
    public function test_guest_cannot_access_exchange_return_form(): void
    {
        $response = $this->get(route('mypage.exchange-return', 'SOME-ORDER'));
        $response->assertRedirect(route('login'));
    }
}
