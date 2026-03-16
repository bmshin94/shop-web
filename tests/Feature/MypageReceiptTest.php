<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MypageReceiptTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 영수증 조회 페이지 접근 및 데이터 노출 테스트
     */
    public function test_member_can_view_receipt_list(): void
    {
        // 1. 회원 생성 및 로그인
        $member = Member::factory()->create();
        
        // 2. 테스트용 상품 생성
        $product = Product::factory()->create(['name' => '레깅스']);

        // 3. 주문 데이터 생성 (결제완료 건)
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'payment_status' => '결제완료',
            'order_number' => 'ORD-20260316-001',
            'total_amount' => 59000,
            'ordered_at' => now(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => '레깅스',
        ]);

        // 4. 주문 데이터 생성 (결제대기 건 - 영수증 목록에 나오면 안 됨!)
        $pendingOrder = Order::factory()->create([
            'member_id' => $member->id,
            'payment_status' => '결제대기',
            'order_number' => 'ORD-20260316-002',
        ]);

        // 5. 페이지 접속
        $response = $this->actingAs($member)
            ->get(route('mypage.receipt'));

        // 6. 검증
        $response->assertStatus(200);
        $response->assertViewHas('receipts');
        
        // 결제완료된 주문은 보여야 함
        $response->assertSee('ORD-20260316-001');
        $response->assertSee('59,000');
        $response->assertSee('레깅스');

        // 결제대기 중인 주문은 보이면 안 됨! 🙅‍♀️
        $response->assertDontSee('ORD-20260316-002');
    }

    /**
     * 영수증 검색 필터 테스트 (상품명 검색)
     */
    public function test_receipt_list_can_be_filtered_by_product_name(): void
    {
        $member = Member::factory()->create();
        
        // '요가복' 주문
        $order1 = Order::factory()->create(['member_id' => $member->id, 'payment_status' => '결제완료']);
        OrderItem::factory()->create(['order_id' => $order1->id, 'product_name' => '블랙 요가복']);

        // '스트랩' 주문
        $order2 = Order::factory()->create(['member_id' => $member->id, 'payment_status' => '결제완료']);
        OrderItem::factory()->create(['order_id' => $order2->id, 'product_name' => '핑크 스트랩']);

        // '요가복'으로 검색
        $response = $this->actingAs($member)
            ->get(route('mypage.receipt', ['search' => '요가복']));

        $response->assertStatus(200);
        $response->assertSee('블랙 요가복');
        $response->assertDontSee('핑크 스트랩'); // 스트랩은 나오면 안 됨! ✨
    }

    /**
     * 비로그인 사용자 접근 제한 테스트
     */
    public function test_guest_cannot_access_receipt_list(): void
    {
        $response = $this->get(route('mypage.receipt'));
        $response->assertRedirect(route('login'));
    }
}
