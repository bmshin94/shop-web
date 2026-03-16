<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderClaim;
use App\Services\Admin\OrderClaimManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderClaimController extends Controller
{
    public function __construct(
        private readonly OrderClaimManagementService $orderClaimManagementService
    ) {
    }

    /**
     * 교환/반품 목록 조회
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'type',
            'status',
            'date_from',
            'date_to',
        ]);

        $claims = $this->orderClaimManagementService->paginateClaims($filters);
        $stats = $this->orderClaimManagementService->getSummaryStats();

        return view('admin.order-claims.index', [
            'claims' => $claims,
            'stats' => $stats,
            'trashedCount' => OrderClaim::onlyTrashed()->count(),
            'statusOptions' => OrderClaim::ALL_STATUSES,
            'typeOptions' => [
                OrderClaim::TYPE_EXCHANGE => '교환',
                OrderClaim::TYPE_RETURN => '반품',
            ],
        ]);
    }

    /**
     * 교환/반품 휴지통 목록 조회
     */
    public function trash(Request $request): View
    {
        $filters = $request->only(['search']);
        $claims = $this->orderClaimManagementService->paginateTrashedClaims($filters);

        return view('admin.order-claims.trash', [
            'claims' => $claims,
        ]);
    }

    /**
     * 교환/반품 상세 조회
     */
    public function show(OrderClaim $orderClaim): View
    {
        $orderClaim->load(['member', 'order.items', 'items.orderItem.product']);

        return view('admin.order-claims.show', [
            'claim' => $orderClaim,
            'statusOptions' => OrderClaim::ALL_STATUSES,
        ]);
    }

    /**
     * 교환/반품 상태 및 메모 수정
     */
    public function update(Request $request, OrderClaim $orderClaim): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'nullable|in:' . implode(',', OrderClaim::ALL_STATUSES),
            'admin_memo' => 'nullable|string',
        ]);

        $this->orderClaimManagementService->updateClaim($orderClaim, $validated);

        return redirect()
            ->route('admin.order-claims.show', $orderClaim)
            ->with('success', '교환/반품 상태가 업데이트되었습니다.');
    }

    /**
     * 교환/반품 신청 삭제 (Soft Delete)
     */
    public function destroy(OrderClaim $orderClaim): RedirectResponse
    {
        $claimNumber = $orderClaim->claim_number;
        $this->orderClaimManagementService->deleteClaim($orderClaim);

        return redirect()
            ->route('admin.order-claims.index')
            ->with('success', "교환/반품 신청 [{$claimNumber}] 이(가) 삭제되었습니다.");
    }

    /**
     * 삭제된 신청 내역 복구
     */
    public function restore(OrderClaim $orderClaim): RedirectResponse
    {
        $this->orderClaimManagementService->restoreClaim($orderClaim);

        return redirect()
            ->route('admin.order-claims.trash')
            ->with('success', "교환/반품 신청 [{$orderClaim->claim_number}] 이(가) 복구되었습니다.");
    }

    /**
     * 신청 내역 영구 삭제 (Hard Delete)
     */
    public function forceDestroy(OrderClaim $orderClaim): RedirectResponse
    {
        $claimNumber = $orderClaim->claim_number;
        $this->orderClaimManagementService->forceDeleteClaim($orderClaim);

        return redirect()
            ->route('admin.order-claims.trash')
            ->with('success', "교환/반품 신청 [{$claimNumber}] 이(가) 영구 삭제되었습니다.");
    }
}
