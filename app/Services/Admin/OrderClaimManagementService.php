<?php

namespace App\Services\Admin;

use App\Models\OrderClaim;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderClaimManagementService
{
    /**
     * 필터링된 교환/반품 목록을 페이징하여 조회한다.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginateClaims(array $filters): LengthAwarePaginator
    {
        $query = OrderClaim::query()
            ->with(['member', 'order'])
            ->latest();

        // 1. 검색어 필터링 (신청번호, 주문번호, 회원명)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($sub) use ($search) {
                        $sub->where('order_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('member', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // 2. 유형 필터링 (exchange / return)
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // 3. 상태 필터링 
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 4. 날짜 필터링
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate(20)->withQueryString();
    }

    /**
     * 삭제된(휴지통) 교환/반품 목록을 페이징하여 조회한다.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginateTrashedClaims(array $filters): LengthAwarePaginator
    {
        $query = OrderClaim::onlyTrashed()
            ->with(['member', 'order'])
            ->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($sub) use ($search) {
                        $sub->where('order_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('member', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->paginate(20)->withQueryString();
    }

    /**
     * 교환/반품 상태 및 관리자 메모를 수정한다.
     *
     * @param OrderClaim $claim
     * @param array $data
     * @return OrderClaim
     */
    public function updateClaim(OrderClaim $claim, array $data): OrderClaim
    {
        return DB::transaction(function () use ($claim, $data) {
            $updateData = [];

            if (isset($data['status'])) {
                $oldStatus = $claim->status;
                $newStatus = $data['status'];
                $updateData['status'] = $newStatus;
                
                // 완료 상태로 변경될 때 비즈니스 로직 처리 
                if ($newStatus === OrderClaim::STATUS_COMPLETED && $oldStatus !== OrderClaim::STATUS_COMPLETED) {
                    $updateData['processed_at'] = now();
                    
                    // 1. 연관된 주문 상품(OrderItem) 상태 업데이트 
                    $itemStatus = $claim->type === OrderClaim::TYPE_EXCHANGE ? '교환완료' : '반품완료';
                    foreach ($claim->items as $claimItem) {
                        $claimItem->orderItem->update(['status' => $itemStatus]);
                    }

                    // 2. 전체 주문 상품이 반품/취소 상태인지 확인하여 주문 상태 업데이트 
                    $order = $claim->order;
                    $allRefunded = ! $order->items()
                        ->whereNotIn('status', ['반품완료', '취소완료'])
                        ->exists();

                    if ($allRefunded && $claim->type === OrderClaim::TYPE_RETURN) {
                        $order->update([
                            'order_status' => '환불완료',
                            'payment_status' => '환불완료'
                        ]);
                    }
                }
            }

            if (isset($data['admin_memo'])) {
                $updateData['admin_memo'] = $data['admin_memo'];
            }

            $claim->update($updateData);

            return $claim;
        });
    }
/**
 * 교환/반품 신청을 삭제한다.
 *
 * @param OrderClaim $claim
 * @return bool|null
 */
public function deleteClaim(OrderClaim $claim): ?bool
{
    return $claim->delete();
}

/**
 * 삭제된 교환/반품 신청을 복구한다.
 *
 * @param OrderClaim $claim
 * @return bool
 */
public function restoreClaim(OrderClaim $claim): bool
{
    return $claim->restore();
}

/**
 * 교환/반품 신청을 영구 삭제한다. (Hard Delete)
 *
 * @param OrderClaim $claim
 * @return bool|null
 */
public function forceDeleteClaim(OrderClaim $claim): ?bool
{
    return $claim->forceDelete();
}

/**
 * 전체적인 요약 통계를 조회한다.
...
     *
     * @return array
     */
    public function getSummaryStats(): array
    {
        return [
            'total' => OrderClaim::count(),
            'received' => OrderClaim::where('status', OrderClaim::STATUS_RECEIVED)->count(),
            'processing' => OrderClaim::where('status', OrderClaim::STATUS_PROCESSING)->count(),
            'completed' => OrderClaim::where('status', OrderClaim::STATUS_COMPLETED)->count(),
            'rejected' => OrderClaim::where('status', OrderClaim::STATUS_REJECTED)->count(),
        ];
    }
}
