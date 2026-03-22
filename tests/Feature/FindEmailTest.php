<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\PhoneVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 아이디 찾기 페이지 접근 테스트
     */
    public function test_find_email_page_is_accessible(): void
    {
        $response = $this->get(route('email.find'));
        $response->assertStatus(200);
    }

    /**
     * 휴대폰 번호로 아이디 찾기 성공 테스트
     */
    public function test_find_email_success(): void
    {
        $phone = '01046669565';
        $email = 'karina@aespa.com';
        
        Member::factory()->create([
            'email' => $email,
            'phone' => $phone,
        ]);

        // 1. 휴대폰 인증 완료 상태 만들기
        PhoneVerification::create([
            'phone' => str_replace('-', '', $phone),
            'code' => '123456',
            'expires_at' => now()->addMinutes(3),
            'is_verified' => true,
        ]);

        // 2. 아이디 찾기 요청
        $response = $this->postJson(route('email.find.post'), [
            'phone' => $phone,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
        
        // 마스킹된 이메일 포함 확인 (kar***@aespa.com)
        $this->assertStringContainsString('kar', $response->json('email'));
        $this->assertStringContainsString('***', $response->json('email'));
        $this->assertStringContainsString('@aespa.com', $response->json('email'));
    }

    /**
     * 인증되지 않은 번호로 아이디 찾기 시도 시 실패 테스트
     */
    public function test_find_email_fails_without_verification(): void
    {
        $response = $this->postJson(route('email.find.post'), [
            'phone' => '010-9999-8888',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => '휴대폰 인증이 완료되지 않았습니다.',
            ]);
    }

    /**
     * 가입되지 않은 번호로 아이디 찾기 시도 시 실패 테스트
     */
    public function test_find_email_fails_for_non_member(): void
    {
        $phone = '010-0000-0000';
        
        // 인증은 완료했지만 회원은 아님
        PhoneVerification::create([
            'phone' => str_replace('-', '', $phone),
            'code' => '123456',
            'expires_at' => now()->addMinutes(3),
            'is_verified' => true,
        ]);

        $response = $this->postJson(route('email.find.post'), [
            'phone' => $phone,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => '입력하신 번호로 가입된 정보가 없습니다.',
            ]);
    }
}
