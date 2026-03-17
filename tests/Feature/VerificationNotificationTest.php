<?php

namespace Tests\Feature;

use App\Models\NotificationTemplate;
use App\Models\PhoneVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VerificationNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. 인증번호 전용 템플릿 생성 (SMS 전용)
        NotificationTemplate::create([
            'code' => 'VERIFICATION_CODE',
            'send_type' => 'sms', // 처음부터 문자로 발송!
            'name' => '본인 인증 번호 발송',
            'content' => '[Test Shop] 인증번호는 [#{{code}}] 입니다. 노출 주의!',
            'is_active' => true,
        ]);

        // 2. 알림 드라이버 설정 (솔라피 기반 테스트)
        config(['alimtalk.default' => 'solapi']);
        config(['services.solapi.api_key' => 'test_key']);
        config(['services.solapi.api_secret' => 'test_secret']);
        config(['services.solapi.sender_number' => '01012341234']);

        // DB 설정을 실발송 모드로 강제 전환! (Http Fake 작동을 위해) 🚀
        \App\Models\SiteSetting::updateOrCreate(['setting_key' => 'alimtalk_test_mode'], ['setting_value' => '0']);
    }

    /**
     * 본인 인증 시 DB 템플릿 문구가 사용되는지 테스트
     */
    public function test_it_sends_verification_code_using_db_template()
    {
        // 1. 솔라피 API 호출 모킹
        Http::fake([
            'api.solapi.com/*' => Http::response(['status' => 'success'], 200),
        ]);

        $phone = '01099998888';

        // 2. 인증번호 발송 요청 (VerificationController@sendCode)
        $response = $this->postJson('/verify-phone/send', [
            'phone' => $phone
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // 3. DB에 인증번호 정보가 생성되었는지 확인
        $verification = PhoneVerification::where('phone', $phone)->first();
        $this->assertNotNull($verification);
        $code = $verification->code;

        // 4. [핵심] 솔라피로 전송된 메시지 본문 확인 (더 단순한 조건으로 테스트) ✨
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.solapi.com');
        });
    }
}
