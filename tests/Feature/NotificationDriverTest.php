<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\NotificationTemplate;
use App\Models\NotificationLog;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationDriverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 테스트용 회원 및 템플릿 생성
        Member::factory()->create([
            'name' => '테스트유저',
            'phone' => '01012345678',
            'email' => 'test@example.com'
        ]);

        NotificationTemplate::create([
            'code' => 'WELCOME_JOIN',
            'name' => '가입 축하',
            'template_id' => 'TMP_001',
            'content' => '#{name}님 환영합니다!',
            'is_active' => true,
        ]);
    }

    /**
     * Solapi 드라이버 작동 테스트
     */
    public function test_it_uses_solapi_driver_when_configured()
    {
        // DB 설정을 실발송 모드로 강제 전환! 🚀
        \App\Models\SiteSetting::updateOrCreate(['setting_key' => 'alimtalk_test_mode'], ['setting_value' => '0']);

        // 1. 드라이버를 solapi로 설정
        config(['alimtalk.default' => 'solapi']);
        config(['services.solapi.api_key' => 'fake_key']);
        config(['services.solapi.api_secret' => 'fake_secret']);

        // 2. HTTP 요청 가로채기 (Mocking)
        Http::fake([
            'api.solapi.com/*' => Http::response(['status' => 'success'], 200),
        ]);

        $member = Member::first();
        
        // 3. 알림 발송
        $member->notify(new WelcomeNotification());

        // 4. 검증: 솔라피 API가 호출되었는지 확인
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'solapi.com');
        });

        // 5. 검증: DB 로그 확인
        $this->assertDatabaseHas('notification_logs', [
            'status' => '성공',
            'recipient' => '01012345678'
        ]);
    }

    /**
     * Aligo 드라이버 작동 테스트
     */
    public function test_it_uses_aligo_driver_when_configured()
    {
        // DB 설정을 실발송 모드로 강제 전환! 🚀
        \App\Models\SiteSetting::updateOrCreate(['setting_key' => 'alimtalk_test_mode'], ['setting_value' => '0']);

        // 1. 드라이버를 aligo로 설정
        config(['alimtalk.default' => 'aligo']);
        config(['services.aligo.api_key' => 'fake_key']);
        config(['services.aligo.user_id' => 'fake_id']);

        // 2. HTTP 요청 가로채기 (Mocking)
        Http::fake([
            'kakaoapi.aligo.in/*' => Http::response(['result_code' => '1', 'message' => 'success'], 200),
        ]);

        $member = Member::first();
        
        // 3. 알림 발송
        $member->notify(new WelcomeNotification());

        // 4. 검증: 알리고 API가 호출되었는지 확인
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'aligo.in');
        });

        // 5. 검증: DB 로그 확인
        $this->assertDatabaseHas('notification_logs', [
            'status' => '성공',
            'recipient' => '01012345678'
        ]);
    }

    /**
     * 테스트 모드 활성화 시 실제 요청이 나가지 않는지 테스트 🚀
     */
    public function test_it_does_not_send_actual_request_in_test_mode()
    {
        // 1. DB 설정을 테스트 모드로 활성화! ✨
        \App\Models\SiteSetting::updateOrCreate(['setting_key' => 'alimtalk_test_mode'], ['setting_value' => '1']);

        // HTTP 요청 가로채기 준비
        Http::fake();

        $member = Member::first();
        
        // 2. 알림 발송 시도
        $member->notify(new WelcomeNotification());

        // 3. 검증: HTTP 요청이 단 하나도 날아가지 않았어야 함! 🚀
        Http::assertNothingSent();

        // 4. 검증: DB 로그에 '성공(테스트모드)'라고 남았는지 확인! 😊
        $this->assertDatabaseHas('notification_logs', [
            'status' => '성공(테스트모드)',
            'recipient' => '01012345678',
            'notification_type' => 'WelcomeNotification'
        ]);
    }
}
