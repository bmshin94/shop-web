<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 성공: 진행 중인 주문이 없는 회원은 탈퇴할 수 있다. ✨
     */
    public function test_member_can_withdraw_successfully_with_no_pending_orders()
    {
        // 1. 회원 생성 및 로그인
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'phone' => '010-1234-5678',
            'points' => 5000,
            'status' => '활성'
        ]);

        // 2. 미사용 쿠폰 하나 추가 ✨
        $coupon = Coupon::create([
            'name' => '탈퇴 전 마지막 쿠폰 😢',
            'code' => 'TEST-COUPON-' . \Illuminate\Support\Str::random(5),
            'type' => 'discount',
            'discount_type' => 'fixed',
            'discount_value' => 3000,
            'is_active' => true,
        ]);
        
        $member->coupons()->attach($coupon->id, [
            'assigned_at' => now(),
            'used_at' => null
        ]);

        $this->actingAs($member);

        // 3. 탈퇴 요청 (POST) 🚀
        $response = $this->postJson(route('mypage.withdraw.post'));

        // 4. 검증 🧐
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // DB 데이터 확인
        $member->refresh();
        $this->assertEquals('탈퇴', $member->status);
        $this->assertEquals(0, $member->points);
        $this->assertStringContainsString('withdrawn_', $member->email);
        $this->assertEquals('000-0000-0000', $member->phone);
        
        // 쿠폰 삭제 확인 (미사용 쿠폰만 삭제 로직)
        $this->assertEquals(0, $member->coupons()->wherePivot('used_at', null)->count());

        // 로그아웃 확인
        $this->assertGuest();
    }

    /**
     * 검증: 탈퇴 후 30일 이내에는 동일한 이메일로 재가입할 수 없다. 🛡️
     */
    public function test_member_cannot_re_register_within_30_days_of_withdrawal()
    {
        $email = 'withdrawn-user@example.com';

        // 1. 회원 탈퇴 기록 생성 (어제 탈퇴한 것으로 설정)
        \DB::table('withdrawn_accounts')->insert([
            'email' => $email,
            'withdrawn_at' => now()->subDay(),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // 2. 동일한 이메일로 회원가입 시도 🚀
        $userData = [
            'name' => '재가입시도자',
            'email' => $email,
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
            'phone' => '010-9999-8888',
            'terms' => ['service', 'privacy']
        ];

        // 휴대폰 인증 데이터 미리 생성 (필수 검증 통과용)
        \App\Models\PhoneVerification::create([
            'phone' => '01099998888',
            'code' => '123456',
            'is_verified' => true,
            'expires_at' => now()->addMinutes(10)
        ]);

        $response = $this->postJson(route('register.post'), $userData);

        // 3. 검증 🧐
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
        $this->assertStringContainsString('탈퇴 후 30일이 지나지 않아 가입이 불가능합니다', $response->json('errors.email.0'));
    }

    /**
     * 실패: 진행 중인 주문(배송중 등)이 있으면 탈퇴할 수 없다. 🛡️
     */
    public function test_member_cannot_withdraw_if_has_pending_orders()
    {
        // 1. 회원 및 '배송중' 주문 생성
        $member = Member::factory()->create(['status' => '활성']);
        Order::factory()->create([
            'member_id' => $member->id,
            'order_status' => '배송중' // 탈퇴 불가능한 상태!
        ]);

        $this->actingAs($member);

        // 2. 탈퇴 요청 🚀
        $response = $this->postJson(route('mypage.withdraw.post'));

        // 3. 검증 🧐
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $response->assertJsonPath('message', '진행 중인 주문이나 클레임 건이 있어 탈퇴가 불가능합니다. 모든 처리가 완료된 후 다시 시도해주세요.');

        // DB 데이터가 변하지 않았는지 확인
        $member->refresh();
        $this->assertEquals('활성', $member->status);
        $this->assertAuthenticatedAs($member);
    }
}
