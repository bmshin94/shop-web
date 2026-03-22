<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 회원가입 페이지가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('pages.register');
    }

    /**
     * 이메일 중복 확인 기능이 정상적으로 작동하는지 테스트합니다.
     */
    public function test_email_duplication_check_works(): void
    {
        // 1. 기존 사용자 생성
        Member::factory()->create([
            'email' => 'existing@example.com',
        ]);

        // 2. 중복된 이메일로 체크 요청
        $response = $this->postJson('/check-email', [
            'email' => 'existing@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => '이미 가입된 이메일입니다.',
            ]);

        // 3. 사용 가능한 이메일로 체크 요청
        $response = $this->postJson('/check-email', [
            'email' => 'new@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => '사용 가능한 이메일입니다.',
            ]);
    }

    /**
     * 회원가입 처리가 정상적으로 수행되는지 테스트합니다.
     */
    public function test_member_can_register(): void
    {
        $phone = '01012345678';
        
        // 1. 휴대폰 인증 완료 처리 (DB에 직접)
        \App\Models\PhoneVerification::create([
            'phone' => $phone,
            'code' => '123456',
            'expires_at' => now()->addMinutes(3),
            'is_verified' => true,
        ]);

        $userData = [
            'name' => '관리자',
            'email' => 'karina@aespa.com',
            'password' => 'password123!',
            'password_confirm' => 'password123!',
            'phone' => '010-1234-5678',
            'terms' => ['service', 'privacy'],
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('home'),
            ]);

        // DB에 저장되었는지 확인
        $this->assertDatabaseHas('members', [
            'name' => '관리자',
            'email' => 'karina@aespa.com',
            'phone' => '010-1234-5678',
            'status' => '활성',
        ]);

        // 자동 로그인 확인
        $this->assertAuthenticated();
    }

    /**
     * 올바른 정보로 로그인 시 성공하는지 테스트합니다.
     */
    public function test_member_can_login(): void
    {
        $member = Member::factory()->create([
            'email' => 'login@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123!'),
        ]);

        $response = $this->postJson(route('login.post'), [
            'email' => 'login@example.com',
            'password' => 'password123!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('home'),
            ]);

        $this->assertAuthenticatedAs($member);
    }

    /**
     * 잘못된 정보로 로그인 시 실패하는지 테스트합니다.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        Member::factory()->create([
            'email' => 'wrong@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123!'),
        ]);

        $response = $this->postJson(route('login.post'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertGuest();
    }

    /**
     * 로그아웃이 정상적으로 수행되는지 테스트합니다.
     */
    public function test_member_can_logout(): void
    {
        $member = Member::factory()->create();
        $this->actingAs($member);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    /**
     * 소셜 로그인 리다이렉트가 정상적으로 수행되는지 테스트합니다.
     */
    public function test_social_login_redirect(): void
    {
        $response = $this->get(route('login.social', 'kakao'));

        // Socialite 리다이렉트 URL 확인 (카카오 인증 페이지로 가는지)
        $response->assertRedirect();
        $this->assertStringContainsString('kauth.kakao.com', $response->getTargetUrl());
    }

    /**
     * 유효하지 않은 데이터로 회원가입 시도 시 에러가 발생하는지 테스트합니다.
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short', // 8자 미만, 규칙 미준수
            'password_confirm' => 'mismatch',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirm', 'phone']);
    }

    /**
     * 비밀번호 규칙(영문, 숫자, 특수문자 포함) 미준수 시 가입이 실패하는지 테스트합니다.
     */
    public function test_registration_fails_with_weak_password(): void
    {
        $phone = '01011112222';
        \App\Models\PhoneVerification::create([
            'phone' => $phone,
            'code' => '123456',
            'expires_at' => now()->addMinutes(3),
            'is_verified' => true,
        ]);

        $userData = [
            'name' => '테스터',
            'email' => 'test@example.com',
            'password' => 'password123', // 특수문자 없음
            'password_confirm' => 'password123',
            'phone' => '010-1111-2222',
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
