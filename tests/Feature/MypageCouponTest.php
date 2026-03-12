<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MypageCouponTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 쿠폰 목록 조회 및 데이터 노출 여부 테스트
     */
    public function test_user_can_view_their_coupons(): void
    {
        // 1. 회원 및 쿠폰 생성
        $member = Member::factory()->create();
        $activeCoupon = Coupon::create([
            'name' => '활성 쿠폰',
            'type' => 'discount',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'description' => '사용 가능한 쿠폰입니다.',
            'ends_at' => now()->addDays(10),
            'is_active' => true,
        ]);
        
        $expiredCoupon = Coupon::create([
            'name' => '만료 쿠폰',
            'type' => 'discount',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'description' => '기간이 지난 쿠폰입니다.',
            'ends_at' => now()->subDays(1),
            'is_active' => true,
        ]);

        // 2. 회원에게 쿠폰 할당
        $member->coupons()->attach([$activeCoupon->id, $expiredCoupon->id]);

        // 3. 페이지 접속 및 데이터 검증
        $response = $this->actingAs($member)->get(route('mypage.coupon'));

        $response->assertStatus(200);
        $response->assertSee('활성 쿠폰');
        $response->assertSee('사용 가능한 쿠폰입니다.');
        $response->assertDontSee('만료 쿠폰'); // 만료된 쿠폰은 보이지 않아야 함
    }

    /**
     * 쿠폰이 없을 때 안내 문구가 나오는지 테스트
     */
    public function test_shows_empty_message_when_no_coupons(): void
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($member)->get(route('mypage.coupon'));

        $response->assertStatus(200);
        $response->assertSee('현재 보유하신 쿠폰이 없습니다.');
    }
}
