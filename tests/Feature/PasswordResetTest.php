<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\EmailVerification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 비밀번호 찾기 페이지 접근 테스트
     */
    public function test_find_password_page_is_accessible(): void
    {
        $response = $this->get(route('password.find'));
        $response->assertStatus(200);
    }

    /**
     * 가입된 이메일로 인증번호 발송 테스트
     */
    public function test_send_auth_code_to_existing_email(): void
    {
        $member = Member::factory()->create(['email' => 'find@example.com']);

        $response = $this->postJson(route('email.send'), [
            'email' => 'find@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('email_verifications', [
            'email' => 'find@example.com',
        ]);
    }

    /**
     * 가입되지 않은 이메일로 인증번호 발송 시도 테스트
     */
    public function test_send_auth_code_to_non_existing_email(): void
    {
        $response = $this->postJson(route('email.send'), [
            'email' => 'no-member@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * 올바른 인증번호로 인증 성공 테스트
     */
    public function test_verify_email_code_success(): void
    {
        EmailVerification::create([
            'email' => 'test@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(3),
        ]);

        $response = $this->postJson(route('email.verify'), [
            'email' => 'test@example.com',
            'code' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * 비밀번호 재설정 성공 테스트
     */
    public function test_password_reset_success(): void
    {
        $member = Member::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('old-password'),
        ]);

        // 1. 미리 인증 완료 상태 만들기
        EmailVerification::create([
            'email' => 'reset@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(3),
            'is_verified' => true,
        ]);

        // 2. 비밀번호 변경 요청
        $response = $this->postJson(route('password.reset.post'), [
            'email' => 'reset@example.com',
            'password' => 'new-password123!',
            'password_confirm' => 'new-password123!',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // 3. 비밀번호가 변경되었는지 확인
        $member->refresh();
        $this->assertTrue(Hash::check('new-password123!', $member->password));
    }
}
