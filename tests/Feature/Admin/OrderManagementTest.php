<?php

namespace Tests\Feature\Admin;

use App\Models\Operator;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // 관리자 권한으로 로그인 상태 유지! 🔒✨
        $admin = Operator::factory()->create([
            'status' => '활성'
        ]);
        $this->actingAs($admin, 'admin');
    }

    /** @test */
    public function 관리자_주문목록은_검색어와_주문상태로_필터링된다(): void
    {
        $matchingOrder = $this->createOrderWithItems([
            'order_number' => 'HF-SEARCH-1001',
            'customer_name' => '김하늘',
            'order_status' => '배송중',
        ]);

        $hiddenOrder = $this->createOrderWithItems([
            'order_number' => 'HF-SEARCH-2002',
            'customer_name' => '박서연',
            'order_status' => '주문접수',
        ]);

        $response = $this->get(route('admin.orders.index', [
            'search' => '김하늘',
            'order_status' => '배송중',
        ]));

        $response->assertOk();
        $response->assertSee($matchingOrder->order_number);
        $response->assertDontSee($hiddenOrder->order_number);
    }

    /** @test */
    public function 배송이_시작된_주문은_택배사와_송장번호가_필수다(): void
    {
        $order = $this->createOrderWithItems();

        $response = $this->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.update', $order), [
                'order_status' => '배송중',
                'payment_status' => '결제완료',
                'courier' => '',
                'tracking_number' => '',
                'admin_memo' => '배송 준비 중',
            ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHasErrors(['courier', 'tracking_number']);
    }

    /** @test */
    public function 배송완료로_변경하면_처리시각이_자동_기록된다(): void
    {
        $order = $this->createOrderWithItems([
            'order_status' => '주문접수',
            'shipped_at' => null,
            'delivered_at' => null,
        ]);

        $response = $this->patch(route('admin.orders.update', $order), [
            'order_status' => '배송완료',
            'payment_status' => '결제완료',
            'courier' => 'CJ대한통운',
            'tracking_number' => '123456789012',
            'admin_memo' => '배송 완료 확인',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => '배송완료',
            'courier' => 'CJ대한통운',
            'tracking_number' => '123456789012',
        ]);

        $order->refresh();

        $this->assertNotNull($order->shipped_at);
        $this->assertNotNull($order->delivered_at);
    }

    /** @test */
    public function 취소완료로_변경하면_배송정보가_초기화되고_환불상태로_정리된다(): void
    {
        $order = $this->createOrderWithItems([
            'payment_status' => '결제완료',
            'order_status' => '배송중',
            'courier' => 'CJ대한통운',
            'tracking_number' => '123456789012',
            'shipped_at' => now()->subDay(),
        ]);

        $response = $this->patch(route('admin.orders.update', $order), [
            'order_status' => '취소완료',
            'payment_status' => '결제완료',
            'courier' => 'CJ대한통운',
            'tracking_number' => '123456789012',
            'admin_memo' => '고객 요청 취소',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => '취소완료',
            'payment_status' => '환불완료',
            'courier' => null,
            'tracking_number' => null,
        ]);

        $order->refresh();

        $this->assertNull($order->shipped_at);
        $this->assertNull($order->delivered_at);
    }

    /** @test */
    public function 관리자_주문삭제는_soft_delete로_처리되고_주문상품은_보존된다(): void
    {
        $order = $this->createOrderWithItems();
        $itemIds = $order->items()->pluck('id');

        $response = $this->delete(route('admin.orders.destroy', $order));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
        $this->assertNull(Order::query()->find($order->id));
        $this->assertNotNull(Order::query()->withTrashed()->find($order->id));

        foreach ($itemIds as $itemId) {
            $this->assertDatabaseHas('order_items', [
                'id' => $itemId,
            ]);
        }
    }

    /** @test */
    public function 주문_휴지통_목록은_soft_delete된_주문만_조회한다(): void
    {
        $activeOrder = $this->createOrderWithItems([
            'order_number' => 'HF-ACTIVE-1001',
        ]);

        $trashedOrder = $this->createOrderWithItems([
            'order_number' => 'HF-TRASH-2002',
        ]);
        $trashedOrder->delete();

        $response = $this->get(route('admin.orders.trash'));

        $response->assertOk();
        $response->assertSee($trashedOrder->order_number);
        $response->assertDontSee($activeOrder->order_number);
    }

    /** @test */
    public function 관리자_주문복구는_soft_delete된_주문을_원복한다(): void
    {
        $order = $this->createOrderWithItems();
        $order->delete();

        $response = $this->patch(route('admin.orders.restore', $order));

        $response->assertRedirect(route('admin.orders.trash'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'deleted_at' => null,
        ]);
        $this->assertNotNull(Order::query()->find($order->id));
    }

    /** @test */
    public function 관리자_주문영구삭제는_주문과_주문상품을_모두_제거한다(): void
    {
        $order = $this->createOrderWithItems();
        $itemIds = $order->items()->pluck('id');
        $order->delete();

        $response = $this->delete(route('admin.orders.force-destroy', $order));

        $response->assertRedirect(route('admin.orders.trash'));
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
        $this->assertNull(Order::query()->withTrashed()->find($order->id));

        foreach ($itemIds as $itemId) {
            $this->assertDatabaseMissing('order_items', [
                'id' => $itemId,
            ]);
        }
    }

    /**
     * 주문과 주문 상품을 함께 생성한다.
     *
     * @param  array<string, mixed>  $attributes
     */
    private function createOrderWithItems(array $attributes = []): Order
    {
        $order = Order::factory()->create($attributes);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
        ]);

        return $order->refresh();
    }
}
