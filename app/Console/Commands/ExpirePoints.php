<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\PointHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpirePoints extends Command
{
    /**
     * 콘솔 명령어 이름 및 설명
     *
     * @var string
     */
    protected $signature = 'points:expire';
    protected $description = '기간이 만료된 적립금을 자동으로 소멸 처리합니다.';

    /**
     * 명령어 실행 로직
     */
    public function handle(): void
    {
        $this->info('적립금 소멸 처리를 시작합니다...');

        // 1. 만료되었는데 아직 남은 금액이 있는 데이터 조회
        $expiredItems = PointHistory::where('remaining_amount', '>', 0)
            ->where('expired_at', '<=', now())
            ->get();

        if ($expiredItems->isEmpty()) {
            $this->info('오늘 만료된 적립금이 없습니다.');
            return;
        }

        foreach ($expiredItems as $item) {
            DB::transaction(function () use ($item) {
                $member = $item->member;
                $expireAmount = $item->remaining_amount;

                // 2. 회원 총 적립금 차감 (최소 0원 보장)
                $newTotalPoints = max(0, $member->points - $expireAmount);
                $member->update(['points' => $newTotalPoints]);

                // 3. 소멸 내역 기록
                PointHistory::create([
                    'member_id' => $member->id,
                    'reason' => '적립금 기간 만료 소멸 (원인: ' . $item->reason . ')',
                    'amount' => -$expireAmount,
                    'balance_after' => $newTotalPoints,
                    'created_at' => now(),
                ]);

                // 4. 해당 원본 데이터의 남은 금액을 0으로 처리
                $item->update(['remaining_amount' => 0]);
            });

            $this->line("회원 ID {$item->member_id}: {$item->remaining_amount}원 소멸 처리 완료");
        }

        $this->info('모든 소멸 처리가 완료되었습니다.');
    }
}
