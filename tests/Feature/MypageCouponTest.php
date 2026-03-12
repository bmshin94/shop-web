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
     * 쿠폰 목록 조회 및 필터링 데이터 노출 여부 테스트
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

        // 2. 회원에게 쿠폰 할당 (미사용 상태)
        $member->coupons()->attach([$activeCoupon->id, $expiredCoupon->id], ['assigned_at' => now()]);

        // 3. 페이지 접속 및 데이터 검증
        $response = $this->actingAs($member)->get(route('mypage.coupon'));

        $response->assertStatus(200);
        $response->assertSee('활성 쿠폰');
        $response->assertSee('사용 가능한 쿠폰입니다.');
        // 만료된 쿠폰이나 사용한 쿠폰은 getCouponListData 로직에 따라 필터링되어야 함
    }

    /**
     * 유효한 코드를 통한 쿠폰 등록 테스트 (AJAX)
     */
    public function test_user_can_register_coupon_with_valid_code(): void
    {
        $member = Member::factory()->create();
        $coupon = Coupon::create([
            'name' => '시크릿 쿠폰',
            'code' => 'KARINA_LOVE',
            'type' => 'discount',
            'discount_type' => 'fixed',
            'discount_value' => 10000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($member)->postJson(route('mypage.coupon.register'), [
            'code' => 'karina_love' // 소문자로 입력해도 대문자로 자동 변환되는지 테스트
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => '쿠폰이 성공적으로 등록되었습니다.']);

        $this->assertTrue($member->coupons()->where('coupon_id', $coupon->id)->exists());
    }

    /**
     * 이미 등록된 쿠폰 중복 등록 차단 테스트
     */
    public function test_user_cannot_register_already_registered_coupon(): void
    {
        $member = Member::factory()->create();
        $coupon = Coupon::create([
            'name' => '중복 불가 쿠폰',
            'code' => 'ONLYONE',
            'type' => 'discount',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'is_active' => true,
        ]);

        // 이미 등록
        $member->coupons()->attach($coupon->id, ['assigned_at' => now()]);

        $response = $this->actingAs($member)->postJson(route('mypage.coupon.register'), [
            'code' => 'ONLYONE'
        ]);

        $response->assertStatus(422)
                 ->assertJson(['message' => '이미 등록된 쿠폰입니다.']);
    }

    /**
     * 존재하지 않는 코드 입력 시 유효성 검사 에러 테스트
     */
    public function test_fails_when_registering_with_invalid_code(): void
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($member)->postJson(route('mypage.coupon.register'), [
            'code' => 'FAKE_CODE_123'
        ]);

        $response->assertStatus(422)
                 ->assertJson(['message' => '존재하지 않거나 발급 가능한 코드가 아닙니다.']);
    }

    /**
     * 쿠폰 번호 형식 유효성 검사 테스트 (FormRequest)
     */
    public function test_coupon_code_validation_rules(): void
    {
        $member = Member::factory()->create();

        // 1. 공란 테스트
        $response = $this->actingAs($member)->postJson(route('mypage.coupon.register'), ['code' => '']);
        $response->assertStatus(422)->assertJsonValidationErrors(['code']);

        // 2. 특수문자 금지 테스트
        $response = $this->actingAs($member)->postJson(route('mypage.coupon.register'), ['code' => 'CODE@123']);
        $response->assertStatus(422)->assertJsonValidationErrors(['code']);
    }
}
