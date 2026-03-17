<?php

namespace Tests\Feature\Auth;

use App\Models\Member;
use App\Models\NotificationTemplate;
use App\Models\NotificationLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. 알림 템플릿 미리 생성 (가입 축하용 - 알림톡 우선) ✨
        NotificationTemplate::create([
            'code' => 'WELCOME_JOIN',
            'send_type' => 'alimtalk', // 알림톡 우선 발송 방식! 🚀
            'name' => '회원가입 환영 인사',
            'template_id' => 'TMP_WELCOME_001',
            'content' => "[Premium Store] 가입을 환영합니다! \n\n안녕하세요, #{name}님!",
            'is_active' => true,
        ]);

        // 2. 기본 알림 드라이버 설정 (테스트용)
        config(['alimtalk.default' => 'solapi']);
        config(['services.solapi.api_key' => 'test_key']);
        config(['services.solapi.api_secret' => 'test_secret']);

        // DB 설정을 실발송 모드로 강제 전환! (Http Fake 작동을 위해) 🚀
        \App\Models\SiteSetting::updateOrCreate(['setting_key' => 'alimtalk_test_mode'], ['setting_value' => '0']);
    }

    /**
     * 회원가입 시 알림톡 발송 통합 테스트
     */
    public function test_it_sends_welcome_notification_on_successful_registration()
    {
        // 1. HTTP 요청 가로채기 (솔라피 API 호출 모킹)
        Http::fake([
            'api.solapi.com/*' => Http::response(['status' => 'success'], 200),
        ]);

        // 2. 가입 요청 데이터 준비
        $phone = '01099998888';
        $registrationData = [
            'name' => '키라나',
            'email' => 'kirana@example.com',
            'password' => 'StrongPass123!', // 강력한 비밀번호로 교체! 
            'password_confirm' => 'StrongPass123!', // 필드 이름 password_confirm으로 수정! 
            'phone' => $phone,
            'agreements' => [1, 2, 3], 
        ];

        // 3. 휴대폰 인증 완료 상태 시뮬레이션 (DB에 인증 데이터 생성) 
        \App\Models\PhoneVerification::create([
            'phone' => $phone,
            'code' => '123456',
            'is_verified' => true,
            'expires_at' => now()->addMinutes(10), 
        ]);

        // 4. 회원가입 요청 (POST)
        $response = $this->postJson('/register', $registrationData);

        // 5. 가입 성공 응답 확인 (JSON 응답이므로 status check)
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // 5. DB에 회원 생성 확인
        $this->assertDatabaseHas('members', [
            'name' => '키라나',
            'email' => 'kirana@example.com',
            'phone' => '01099998888',
        ]);

        // 6. [핵심] 알림 발송 이력(Log)이 DB에 남았는지 확인! 
        // MemberService에서 가입 직후 알림을 쏘기 때문에 이력이 있어야 해! 
        $this->assertDatabaseHas('notification_logs', [
            'recipient' => '01099998888',
            'status' => '성공',
            'notification_type' => 'WelcomeNotification',
        ]);

        // 7. 발송된 메시지 내용 검증 (템플릿 변수가 잘 치환됐는지 확인!) 
        $log = NotificationLog::where('recipient', '01099998888')->first();
        $this->assertStringContainsString('키라나님!', $log->message);
    }
}
