<?php

namespace Tests\Feature\Admin;

use App\Models\Member;
use App\Models\Operator;
use App\Models\Order;
use App\Models\OrderClaim;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderClaimManagementTest extends TestCase
{
    use RefreshDatabase;

    private Operator $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // 테스트용 관리자 생성
        $this->admin = Operator::factory()->create();
    }

    /**
     * 관리자 교환/반품 목록 조회 테스트
     */
    public function test_admin_can_view_order_claims_list(): void
    {
        // 교환/반품 데이터 3개 생성
        OrderClaim::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.order-claims.index'));

        $response->assertStatus(200)
            ->assertViewIs('admin.order-claims.index')
            ->assertViewHas('claims');
        
        $this->assertCount(3, $response->viewData('claims'));
    }

    /**
     * 관리자 교환/반품 목록 필터링 테스트
     */
    public function test_admin_can_filter_order_claims_by_status(): void
    {
        // '접수' 2개, '완료' 1개 생성
        OrderClaim::factory()->create(['status' => OrderClaim::STATUS_RECEIVED]);
        OrderClaim::factory()->create(['status' => OrderClaim::STATUS_RECEIVED]);
        OrderClaim::factory()->create(['status' => OrderClaim::STATUS_COMPLETED]);

        // '접수' 상태만 필터링! 
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.order-claims.index', ['status' => OrderClaim::STATUS_RECEIVED]));

        $response->assertStatus(200);
        $this->assertCount(2, $response->viewData('claims'));
    }

    /**
     * 관리자 교환/반품 상세 조회 테스트
     */
    public function test_admin_can_view_order_claim_detail(): void
    {
        $claim = OrderClaim::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.order-claims.show', $claim));

        $response->assertStatus(200)
            ->assertViewIs('admin.order-claims.show')
            ->assertViewHas('claim');
        
        $this->assertEquals($claim->id, $response->viewData('claim')->id);
    }

    /**
     * 관리자 교환/반품 상태 및 메모 수정 테스트 ✅
     */
    public function test_admin_can_update_order_claim_status_and_memo(): void
    {
        $claim = OrderClaim::factory()->create(['status' => OrderClaim::STATUS_RECEIVED, 'type' => OrderClaim::TYPE_RETURN]);
        $orderItem = OrderItem::factory()->create(['order_id' => $claim->order_id, 'status' => '주문완료']);

        // 클레임 아이템 연결 
        \App\Models\OrderClaimItem::create([
            'order_claim_id' => $claim->id,
            'order_item_id' => $orderItem->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.order-claims.update', $claim), [
                'status' => OrderClaim::STATUS_COMPLETED,
                'admin_memo' => '반품 완료 처리합니다.'
            ]);

        $response->assertRedirect(route('admin.order-claims.show', $claim));

        // 1. 클레임 상태 확인 
        $this->assertDatabaseHas('order_claims', [
            'id' => $claim->id,
            'status' => OrderClaim::STATUS_COMPLETED,
            'admin_memo' => '반품 완료 처리합니다.'
        ]);

        // 2. 주문 상품 상태가 '반품완료'로 변했는지 확인 
        $orderItem->refresh();
        $this->assertEquals('반품완료', $orderItem->status);

        // 3. 전체 주문 상태가 '환불완료'로 변했는지 확인 (모든 상품이 반품되었으므로!) 
        $claim->order->refresh();
        $this->assertEquals('환불완료', $claim->order->order_status);
        $this->assertEquals('환불완료', $claim->order->payment_status);
    }

    /**
     * 완료 상태로 변경 시 처리 일시가 기록되는지 테스트 ⏰
     */
    public function test_processed_at_is_set_when_status_is_completed(): void
    {
        $claim = OrderClaim::factory()->create(['status' => OrderClaim::STATUS_RECEIVED]);

        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.order-claims.update', $claim), [
                'status' => OrderClaim::STATUS_COMPLETED
            ]);

        $claim->refresh();
        $this->assertNotNull($claim->processed_at);
    }

    /**
     * 관리자 교환/반품 신청 삭제 테스트 (Soft Delete)
     */
    public function test_admin_can_delete_order_claim(): void
    {
        $claim = OrderClaim::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.order-claims.destroy', $claim));

        $response->assertRedirect(route('admin.order-claims.index'))
            ->assertSessionHas('success');

        // DB에서 Soft Delete 되었는지 확인! 
        $this->assertSoftDeleted('order_claims', [
            'id' => $claim->id
        ]);
    }
    }
