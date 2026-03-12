<?php

namespace App\Services\Admin;

use App\Models\Member;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * 전체 적립금 변동 내역 조회 및 필터링
     * 
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getHistoryList(Request $request): LengthAwarePaginator
    {
        $query = PointHistory::with('member')->latest();

        // 1. 회원명/사유 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhereHas('member', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. 변동 유형 필터 (적립/차감)
        if ($request->filled('type')) {
            if ($request->type === 'plus') $query->where('amount', '>', 0);
            if ($request->type === 'minus') $query->where('amount', '<', 0);
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * 관리자에 의한 수동 적립금 지급/차감
     *
     * @param int $memberId
     * @param int $amount
     * @param string $reason
     * @return void
     */
    public function manualAdjust(int $memberId, int $amount, string $reason): void
    {
        DB::transaction(function () use ($memberId, $amount, $reason) {
            $member = Member::findOrFail($memberId);
            
            // 1. 회원 총 적립금 업데이트
            $newTotal = max(0, $member->points + $amount);
            $member->update(['points' => $newTotal]);

            // 2. 히스토리 기록
            PointHistory::create([
                'member_id' => $member->id,
                'reason' => '[관리자 지급] ' . $reason,
                'amount' => $amount,
                'remaining_amount' => $amount > 0 ? $amount : 0, // 적립 시에만 소멸 대상
                'balance_after' => $newTotal,
                'expired_at' => $amount > 0 ? now()->addYear() : null, // 적립 시 1년 유효기간 기본
            ]);
        });
    }
}
