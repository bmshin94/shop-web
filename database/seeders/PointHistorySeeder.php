<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\PointHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PointHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::all();

        foreach ($members as $member) {
            $currentBalance = 0;

            // 1. 신규 가입 축하금 적립 (오래전)
            $welcomeAmount = 10000;
            $currentBalance += $welcomeAmount;
            PointHistory::create([
                'member_id' => $member->id,
                'reason' => '신규 가입 축하 적립금',
                'amount' => $welcomeAmount,
                'balance_after' => $currentBalance,
                'expired_at' => now()->addYear(),
                'created_at' => now()->subMonths(3),
            ]);

            // 2. 상품 구매 확정 적립 (2개월 전)
            $purchaseAmount = 2500;
            $currentBalance += $purchaseAmount;
            PointHistory::create([
                'member_id' => $member->id,
                'reason' => '상품 구매 확정 적립 (주문번호: ORD-20260115)',
                'amount' => $purchaseAmount,
                'balance_after' => $currentBalance,
                'expired_at' => now()->addYear(),
                'created_at' => now()->subMonths(2),
            ]);

            // 3. 적립금 사용 (1개월 전)
            $useAmount = -3000;
            $currentBalance += $useAmount;
            PointHistory::create([
                'member_id' => $member->id,
                'reason' => '상품 구매 시 적립금 사용',
                'amount' => $useAmount,
                'balance_after' => $currentBalance,
                'created_at' => now()->subMonth(),
            ]);

            // 4. 리뷰 작성 적립 (최근)
            $reviewAmount = 500;
            $currentBalance += $reviewAmount;
            PointHistory::create([
                'member_id' => $member->id,
                'reason' => '포토 리뷰 작성 보너스',
                'amount' => $reviewAmount,
                'balance_after' => $currentBalance,
                'expired_at' => now()->addYear(),
                'created_at' => now()->subDays(5),
            ]);

            // 5. 소멸 예정 적립금 (UI 테스트용! 15일 뒤 만료)
            $expiringAmount = 1000;
            $currentBalance += $expiringAmount;
            PointHistory::create([
                'member_id' => $member->id,
                'reason' => '이벤트 참여 보너스 (기간 한정)',
                'amount' => $expiringAmount,
                'balance_after' => $currentBalance,
                'expired_at' => now()->addDays(15), // 30일 내 소멸 예정에 걸리도록 설정! 
                'created_at' => now()->subDays(350), // 거의 1년 다 되어감
            ]);

            // 최종 잔액을 회원 테이블에 업데이트! 
            $member->update(['points' => $currentBalance]);
        }
    }
}
