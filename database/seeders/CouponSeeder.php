<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 쿠폰 기초 데이터 정의 (다양한 시나리오)
        $coupons = [
            [
                'name' => '무료배송 웰컴 쿠폰',
                'code' => 'WELCOMEFREE',
                'type' => 'shipping',
                'discount_type' => 'fixed',
                'discount_value' => 3000,
                'min_order_amount' => 50000,
                'description' => '5만원 이상 결제 시 사용 가능 (신규 회원 전용)',
                'starts_at' => now(),
                'ends_at' => now()->addDays(365),
                'is_active' => true,
            ],
            [
                'name' => '[시즌오프] 전품목 20% 할인',
                'code' => 'SEASONOFF20',
                'type' => 'discount',
                'discount_type' => 'percent',
                'discount_value' => 20,
                'min_order_amount' => 100000,
                'max_discount_amount' => 50000,
                'description' => '10만원 이상 구매 시 최대 5만원 할인',
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->addDays(5),
                'is_active' => true,
            ],
            [
                'name' => '첫 구매 감사 1만원 할인',
                'code' => 'THANKYOU10',
                'type' => 'discount',
                'discount_type' => 'fixed',
                'discount_value' => 10000,
                'min_order_amount' => 50000,
                'description' => '첫 주문 완료 후 다음 주문 시 1만원 즉시 할인',
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'name' => '관리자 팬클럽 전용 시크릿 쿠폰',
                'code' => 'AESPA_KARINA',
                'type' => 'discount',
                'discount_type' => 'percent',
                'discount_value' => 30,
                'min_order_amount' => 10000,
                'max_discount_amount' => 100000,
                'description' => '관리자가 고객님를 위해 준비한 특별 선물 (최대 10만원 할인)',
                'starts_at' => now(),
                'ends_at' => null, // 상시 사용 가능
                'is_active' => true,
            ],
            [
                'name' => '(기간만료) 지난 시즌 할인 쿠폰',
                'code' => 'OLD_SEASON',
                'type' => 'discount',
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'min_order_amount' => 30000,
                'description' => '이 쿠폰은 이미 종료된 쿠폰입니다.',
                'starts_at' => now()->subMonths(3),
                'ends_at' => now()->subMonths(2),
                'is_active' => false,
            ],
            [
                'name' => '상시 무료배송 쿠폰 (무제한)',
                'code' => null, // 자동 발급 전용
                'type' => 'shipping',
                'discount_type' => 'fixed',
                'discount_value' => 3000,
                'min_order_amount' => 0,
                'description' => '별도 조건 없이 언제든 사용 가능한 상시 무료 배송 혜택',
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'name' => '정기 구독 10% 할인',
                'code' => 'REGULAR10',
                'type' => 'discount',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_order_amount' => 20000,
                'max_discount_amount' => 10000,
                'description' => '매달 정기 구독 회원에게 발급되는 추가 할인 혜택',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'name' => '생일 축하해 5천원 선물',
                'code' => null,
                'type' => 'discount',
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'min_order_amount' => 10000,
                'description' => '고객님의 소중한 생일을 위해 준비한 작은 선물',
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'name' => '럭키 드로우 77% 파격 할인',
                'code' => 'LUCKY77',
                'type' => 'discount',
                'discount_type' => 'percent',
                'discount_value' => 77,
                'min_order_amount' => 100000,
                'max_discount_amount' => 77000,
                'description' => '운 좋은 고객님만 쓸 수 있는 초강력 할인 쿠폰',
                'starts_at' => now(),
                'ends_at' => now()->addHours(24),
                'is_active' => true,
            ],
            [
                'name' => '준비 중인 이벤트 쿠폰',
                'code' => 'COMINGSOON',
                'type' => 'discount',
                'discount_type' => 'fixed',
                'discount_value' => 1000,
                'min_order_amount' => 0,
                'description' => '아직 활성화되지 않은 미래의 이벤트 쿠폰',
                'starts_at' => now()->addDays(30),
                'ends_at' => now()->addDays(60),
                'is_active' => false,
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::updateOrCreate(
                ['name' => $couponData['name']],
                $couponData
            );
        }
    }
}
