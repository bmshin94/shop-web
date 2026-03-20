<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderManagementService
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {
    }
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
            ->with(['member', 'items.product'])
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
            ->with(['member', 'items.product'])
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
            'shipping_orders' => Order::where('order_status', '배송중')->count(),
            'completed_orders' => Order::where('order_status', '배송완료')->count(),
        ];
    }

    public function updateOrder(Order $order, array $payload): Order
    {
        // 취소 처리 여부 확인 🚩
        $isTransitionToCancelled = ($payload['order_status'] === '취소완료' || $payload['payment_status'] === '환불완료' || $payload['payment_status'] === '취소완료') 
                                    && ($order->order_status !== '취소완료');

        if ($isTransitionToCancelled) {
            // CheckoutService의 통합 취소 로직 호출! (환불, 적립금, 재고 등 한방에!)
            return $this->checkoutService->cancelOrder($order, $payload['admin_memo'] ?? '관리자에 의한 취소 처리');
        }

        // 배송 시작 알림 발송 체크 🚀
        $isTransitionToShipping = ($payload['order_status'] === '배송중' && $order->order_status !== '배송중');

        $normalizedPayload = $this->normalizeStatusPayload($order, $payload);

        $order->update($normalizedPayload);

        // 배송 시작 안내 발송 💌
        if ($isTransitionToShipping) {
            try {
                $smsService = app(\App\Services\SmsService::class);
                $template = \App\Models\NotificationTemplate::where('code', 'SHIPPING_STARTED')->where('is_active', true)->first();
                if ($template) {
                    $message = $template->parseContent([
                        'order_number' => $order->order_number,
                        'shipping_company' => $order->shipping_company ?? '택배',
                        'tracking_number' => $order->tracking_number ?? '준비중',
                    ]);
                    $smsService->sendSms($order->recipient_phone, $message);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('배송 시작 알림 발송 실패: ' . $e->getMessage());
            }
        }

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
        $orderStatus = (string) $payload['order_status'];
        $paymentStatus = (string) $payload['payment_status'];

        // 1. 배송 시작 시각 자동 기록 (배송중 이상일 때)
        if (in_array($orderStatus, ['배송중', '배송완료', '구매확정'], true) && $order->shipped_at === null) {
            $payload['shipped_at'] = now();
        }

        // 2. 배송완료 및 구매확정 시 완료 시각 자동 기록
        if (in_array($orderStatus, ['배송완료', '구매확정'], true)) {
            $payload['shipped_at'] = $order->shipped_at ?? ($payload['shipped_at'] ?? now());
            if ($order->delivered_at === null) {
                $payload['delivered_at'] = now();
            }
        }

        // 3. 취소 주문은 배송 정보와 결제상태를 안전하게 정리
        if ($orderStatus === '취소완료' || in_array($paymentStatus, Order::PAYMENT_CANCELLED_STATUSES, true)) {
            $payload['order_status'] = '취소완료';
            $payload['courier'] = null;
            $payload['tracking_number'] = null;
            $payload['shipped_at'] = null;
            $payload['delivered_at'] = null;

            if ($paymentStatus === '결제완료') {
                $payload['payment_status'] = '환불완료';
            }
        }

        // 4. 상태가 뒤로 돌아갈 때 시간 데이터 정리
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

        if (! empty($filters['date_from'])) {
            $query->whereDate('ordered_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('ordered_at', '<=', $filters['date_to']);
        }
    }
}
