<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 쿠폰 기초 데이터 생성
        $coupons = [
            [
                'name' => '무료배송 쿠폰',
                'type' => 'shipping',
                'discount_type' => 'fixed',
                'discount_value' => 3000,
                'min_order_amount' => 50000,
                'description' => '5만원 이상 결제 시 사용 가능',
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'name' => '웰컴 10% 할인 쿠폰',
                'type' => 'discount',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_order_amount' => 0,
                'max_discount_amount' => 30000,
                'description' => '전 상품 적용 가능 (최대 3만원)',
                'starts_at' => now(),
                'ends_at' => now()->addDays(60),
                'is_active' => true,
            ],
            [
                'name' => '재구매 감사 쿠폰',
                'type' => 'discount',
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'min_order_amount' => 30000,
                'description' => '3만원 이상 결제 시 5,000원 할인',
                'starts_at' => now(),
                'ends_at' => now()->addDays(15),
                'is_active' => true,
            ]
        ];

        foreach ($coupons as $couponData) {
            $coupon = Coupon::updateOrCreate(
                ['name' => $couponData['name']],
                $couponData
            );

            // 2. 모든 회원에게 테스트 쿠폰 발급 (기존 발급 내역이 없는 경우만)
            $members = Member::all();
            foreach ($members as $member) {
                if (!$member->coupons()->where('coupon_id', $coupon->id)->exists()) {
                    $member->coupons()->attach($coupon->id, [
                        'assigned_at' => now()
                    ]);
                }
            }
        }
    }
}
