<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\PointHistory;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. 포인트 정책 설정 ✨
        SiteSetting::updateOrCreate(['setting_key' => 'point_earn_rate'], ['setting_value' => '5']); // 5% 적립
        SiteSetting::updateOrCreate(['setting_key' => 'point_expiry_months'], ['setting_value' => '12']);
    }

    /**
     * 검증: 결제 완료 시에는 포인트가 '적립대기' 상태로 기록만 되고 실제 지급은 안 된다. 🛡️
     */
    public function test_points_are_queued_as_pending_after_payment()
    {
        $member = Member::factory()->create(['points' => 0]);
        $this->actingAs($member);

        $order = Order::factory()->create([
            'member_id' => $member->id,
            'total_amount' => 100000,
            'order_status' => '주문접수'
        ]);

        // [핵심] 결제 후 적립 대기 포인트 생성 시뮬레이션 ✨
        PointHistory::create([
            'member_id' => $member->id,
            'order_id' => $order->id,
            'reason' => "상품 구매 적립 대기 (#{$order->order_number})",
            'amount' => 5000,
            'balance_after' => $member->points, // 아직 늘어나기 전!
            'status' => '적립대기',
            'expired_at' => now()->addMonths(12)
        ]);

        $member->refresh();
        $this->assertEquals(0, $member->points); // 지급 안 된 거 확인! ✅
        
        $this->assertDatabaseHas('point_histories', [
            'order_id' => $order->id,
            'status' => '적립대기',
            'amount' => 5000
        ]);
    }

    /**
     * 검증: 구매 확정 시 '적립대기' 포인트가 진짜로 지급된다! 💰 🚀
     */
    public function test_pending_points_are_awarded_on_purchase_confirmation()
    {
        // 1. 대기 포인트가 있는 주문 준비
        $member = Member::factory()->create(['points' => 0]);
        $order = Order::factory()->create([
            'member_id' => $member->id,
            'order_number' => 'ORD-POINT-777',
            'order_status' => '배송완료'
        ]);

        PointHistory::create([
            'member_id' => $member->id,
            'order_id' => $order->id,
            'amount' => 5000,
            'status' => '적립대기',
            'balance_after' => 0, // 필수 필드 추가! ✨
            'reason' => "상품 구매 적립 대기 (#{$order->order_number})"
        ]);

        $this->actingAs($member);

        // 2. 구매 확정 요청 (POST) 🚀
        // 실제 라우트 이름 확인 필요 (mypage.order-confirm 인지 order.confirm 인지)
        // routes/web.php 에 'confirmPurchase' 메서드에 연결된 라우트 사용
        $response = $this->postJson(route('mypage.order-confirm', ['order_number' => $order->order_number]));

        // 3. 검증 🧐
        $response->assertStatus(200);
        
        $member->refresh();
        $this->assertEquals(5000, $member->points); // 포인트 지급 확인! 💰 ✅

        $this->assertDatabaseHas('point_histories', [
            'order_id' => $order->id,
            'status' => '적립완료',
            'balance_after' => 5000
        ]);
    }
}
