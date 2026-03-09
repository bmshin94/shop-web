<?php

namespace Tests\Feature;

use App\Models\PhoneVerification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PhoneVerificationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 인증번호 발송 요청이 정상적으로 처리되는지 테스트합니다.
     */
    public function test_send_verification_code(): void
    {
        $response = $this->postJson(route('sms.send'), [
            'phone' => '010-4666-9565',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('phone_verifications', [
            'phone' => '01046669565',
        ]);
    }

    /**
     * 올바른 인증번호 입력 시 인증이 성공하는지 테스트합니다.
     */
    public function test_verify_correct_code(): void
    {
        // 1. 미리 인증번호 생성
        $phone = '01046669565';
        $code = '123456';
        PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(3),
        ]);

        // 2. 검증 요청
        $response = $this->postJson(route('sms.verify'), [
            'phone' => '010-4666-9565',
            'code' => $code,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => '인증이 완료되었습니다.',
            ]);

        $this->assertTrue(PhoneVerification::where('phone', $phone)->first()->is_verified);
    }

    /**
     * 잘못된 인증번호 입력 시 인증이 실패하는지 테스트합니다.
     */
    public function test_verify_incorrect_code(): void
    {
        $phone = '01046669565';
        PhoneVerification::create([
            'phone' => $phone,
            'code' => '111111',
            'expires_at' => now()->addMinutes(3),
        ]);

        $response = $this->postJson(route('sms.verify'), [
            'phone' => '010-4666-9565',
            'code' => '222222',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * 인증번호가 만료(3분 경과)되었을 때 인증이 실패하는지 테스트합니다.
     */
    public function test_verify_expired_code(): void
    {
        $phone = '01046669565';
        $code = '123456';
        
        // 1. 이미 5분 전에 생성된 (만료된) 인증번호 생성
        PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->subMinutes(2), // 현재보다 이전 시간으로 설정
        ]);

        // 2. 검증 요청
        $response = $this->postJson(route('sms.verify'), [
            'phone' => '010-4666-9565',
            'code' => $code,
        ]);

        // 3. 실패 확인
        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => '인증번호가 일치하지 않거나 만료되었습니다.',
            ]);
    }
}
