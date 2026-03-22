<?php

namespace Tests\Feature\Admin;

use App\Models\Operator;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // 1. 관리자 계정 생성
        $this->admin = Operator::factory()->create();
        
        // 2. 기본 설정 시딩 (테스트 환경에서도 필요!)
        $this->seed(\Database\Seeders\SiteSettingSeeder::class);
        $this->seed(\Database\Seeders\AdminMenuSeeder::class);
    }

    /**
     * 기본 설정 페이지 조회 테스트
     */
    public function test_admin_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Women'); // 시더에 들어있는 기본값 확인
        $response->assertSee('백민오빠'); // 우리가 넣은 대표자명 확인 🕵️‍♀️✨
    }

    /**
     * 기본 설정 업데이트 테스트 (새로운 필드들 포함!)
     */
    public function test_admin_can_update_settings(): void
    {
        $newData = [
            'mall_name' => '에스파 럭셔리 스토어',
            'business_name' => '(주)마이월드',
            'business_number' => '999-88-77777',
            'representative_name' => '유지민(카리나)',
            'mail_order_report_number' => '제 2026-광야-0001호',
            'business_address' => '서울특별시 광야구 넥스트레벨 1층',
            'privacy_manager' => '윈터 (winter@aespa.com)',
            'customer_center_phone' => '02-1234-5678',
            'customer_center_email' => 'cs@aespa.example.com',
            'cs_hours' => '365일 24시간 연중무휴 💖',
            'kakao_consult_url' => 'https://pf.kakao.com/_aespa_fan',
            'site_description' => '넥스트 레벨로 이끄는 프리미엄 스토어입니다.',
            'site_keywords' => '에스파, 광야, 넥스트레벨, 카리나',
            'shipping_fee' => 2500,
            'free_shipping_threshold' => 30000,
            'point_earn_rate' => 5.5,
            'maintenance_mode' => 0,
            'alimtalk_test_mode' => 1,
            'order_auto_cancel_hours' => 48,
            'welcome_points' => 10000,
            'min_use_points' => 1000,
            'point_expiry_months' => 12,
            'review_reward_points' => 500
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.settings.update'), $newData);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        // DB에 진짜 잘 바뀌었는지 꼼꼼하게 확인! 🕵️‍♀️✨
        $this->assertEquals('에스파 럭셔리 스토어', SiteSetting::getValue('mall_name'));
        $this->assertEquals('유지민(카리나)', SiteSetting::getValue('representative_name'));
        $this->assertEquals('365일 24시간 연중무휴 💖', SiteSetting::getValue('cs_hours'));
        $this->assertEquals('에스파, 광야, 넥스트레벨, 카리나', SiteSetting::getValue('site_keywords'));
    }
}
