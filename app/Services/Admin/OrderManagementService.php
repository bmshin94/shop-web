<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderManagementService
{
    /**
     * 관리자 주문 목록을 필터링하여 페이징한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateOrders(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Order::query()
            ->withCount('items')
            ->withSum('items as total_quantity', 'quantity')
            ->latest('ordered_at');

        $this->applyOrderFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 휴지통 주문 목록을 필터링하여 페이징한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateTrashedOrders(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Order::onlyTrashed()
            ->withCount('items')
            ->withSum('items as total_quantity', 'quantity')
            ->latest('deleted_at');

        $this->applyOrderFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 관리자 주문 대시보드 요약 통계를 계산한다.
     *
     * @return array<string, int>
     */
    public function getSummaryStats(): array
    {
        return [
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('ordered_at', now())->count(),
            'shipping_orders' => Order::where('shipping_status', '배송중')->count(),
            'completed_orders' => Order::where('shipping_status', '배송완료')->count(),
        ];
    }

    /**
     * 주문/배송 상태를 규칙에 맞게 보정 후 저장한다.
     *
     * @param  Order  $order
     * @param  array<string, mixed>  $payload
     * @return Order
     */
    public function updateOrder(Order $order, array $payload): Order
    {
        $normalizedPayload = $this->normalizeStatusPayload($order, $payload);

        $order->update($normalizedPayload);

        return $order->refresh();
    }

    /**
     * 주문을 soft delete 처리해 목록에서 숨긴다.
     *
     * @param  Order  $order
     * @return void
     */
    public function deleteOrder(Order $order): void
    {
        $order->delete();
    }

    /**
     * soft delete된 주문을 복구한다.
     *
     * @param  Order  $order
     * @return bool
     */
    public function restoreOrder(Order $order): bool
    {
        if (! $order->trashed()) {
            return false;
        }

        return (bool) $order->restore();
    }

    /**
     * soft delete된 주문을 영구 삭제한다.
     *
     * @param  Order  $order
     * @return bool
     */
    public function forceDeleteOrder(Order $order): bool
    {
        if (! $order->trashed()) {
            return false;
        }

        return (bool) $order->forceDelete();
    }

    /**
     * 주문 상태 전이 규칙에 맞게 저장 데이터를 정리한다.
     *
     * @param  Order  $order
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function normalizeStatusPayload(Order $order, array $payload): array
    {
        $shippingStatus = (string) $payload['shipping_status'];
        $orderStatus = (string) $payload['order_status'];
        $paymentStatus = (string) $payload['payment_status'];

        // 1. 배송 시작 시각 자동 기록 (배송상태 또는 주문상태가 배송중 이상일 때)
        if ((in_array($shippingStatus, Order::SHIPPING_STARTED_STATUSES, true) || in_array($orderStatus, ['배송중', '배송완료', '구매확정'], true)) 
            && $order->shipped_at === null) {
            $payload['shipped_at'] = now();
        }

        // 2. 배송 진행 상태는 주문상태를 배송중으로 동기화 (취소/완료 상태 아닐 때)
        if (in_array($shippingStatus, ['출고완료', '배송중'], true) && !in_array($orderStatus, ['취소완료', '배송완료', '구매확정'], true)) {
            $payload['order_status'] = '배송중';
        }

        // 3. 배송완료 및 구매확정 시 완료 시각 자동 기록
        if (in_array($orderStatus, ['배송완료', '구매확정'], true) || $shippingStatus === '배송완료') {
            if ($orderStatus !== '구매확정' && $shippingStatus === '배송완료') {
                $payload['order_status'] = '배송완료';
            }
            $payload['shipped_at'] = $order->shipped_at ?? ($payload['shipped_at'] ?? now());
            if ($order->delivered_at === null) {
                $payload['delivered_at'] = now();
            }
        }

        // 4. 취소 주문은 배송 정보와 결제상태를 안전하게 정리
        if ($orderStatus === '취소완료' || in_array($paymentStatus, Order::PAYMENT_CANCELLED_STATUSES, true)) {
            $payload['order_status'] = '취소완료';
            $payload['shipping_status'] = '배송대기';
            $payload['courier'] = null;
            $payload['tracking_number'] = null;
            $payload['shipped_at'] = null;
            $payload['delivered_at'] = null;

            if ($paymentStatus === '결제완료') {
                $payload['payment_status'] = '환불완료';
            }
        }

        // 5. 상태가 뒤로 돌아갈 때(배송완료 -> 배송중 등) 시간 데이터 정리
        if ($order->order_status === '배송완료' && in_array($orderStatus, ['주문접수', '상품준비중', '배송중'], true)) {
            $payload['delivered_at'] = null;
        }
        if ($order->order_status === '배송중' && in_array($orderStatus, ['주문접수', '상품준비중'], true)) {
            $payload['shipped_at'] = null;
        }

        return $payload;
    }

    /**
     * 주문 목록 필터를 쿼리에 공통 적용한다.
     *
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     * @return void
     */
    private function applyOrderFilters(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $search . '%')
                    ->orWhere('customer_email', 'like', '%' . $search . '%')
                    ->orWhere('recipient_name', 'like', '%' . $search . '%')
                    ->orWhere('tracking_number', 'like', '%' . $search . '%');
            });
        }

        if (! empty($filters['order_status'])) {
            $query->where('order_status', $filters['order_status']);
        }

        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (! empty($filters['shipping_status'])) {
            $query->where('shipping_status', $filters['shipping_status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('ordered_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('ordered_at', '<=', $filters['date_to']);
        }
    }
}
