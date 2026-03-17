<?php

namespace Tests\Feature\Admin;

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Operator $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Operator::factory()->create();
    }

    /** @test */
    public function 관리자_기본_설정_화면에_접근할_수_있다(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('기본 설정');
    }

    /** @test */
    public function 관리자_기본_설정을_저장할_수_있다(): void
    {
        $payload = [
            'mall_name' => 'Active Women LAB',
            'customer_center_phone' => '02-1234-5678',
            'customer_center_email' => 'help@herfield.test',
            'business_name' => 'Active Women Co.',
            'business_number' => '123-45-67890',
            'shipping_fee' => 4000,
            'free_shipping_threshold' => 70000,
            'point_earn_rate' => 2.5,
            'maintenance_mode' => true,
            'order_auto_cancel_hours' => 48,
        ];

        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertDatabaseHas('site_settings', [
            'setting_key' => 'mall_name',
            'setting_value' => 'Active Women LAB',
        ]);
        $this->assertDatabaseHas('site_settings', [
            'setting_key' => 'shipping_fee',
            'setting_value' => '4000',
        ]);
        $this->assertDatabaseHas('site_settings', [
            'setting_key' => 'maintenance_mode',
            'setting_value' => '1',
        ]);
    }

    /** @test */
    public function 관리자_택배사_설정을_저장할_수_있다(): void
    {
        // 1. 기존 설정에 택배사 데이터 추가해서 보내기
        $payload = [
            'mall_name' => 'Active Women',
            'shipping_fee' => 3000,
            'free_shipping_threshold' => 50000,
            'point_earn_rate' => 1.0,
            'maintenance_mode' => false,
            'order_auto_cancel_hours' => 24,
            'couriers' => [
                ['name' => '신규택배', 'url' => 'https://new-courier.com/{tracking_number}'],
                ['name' => '번개배송', 'url' => 'https://flash.com/trace/{tracking_number}'],
            ]
        ];

        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));

        // 2. DB에 JSON 형태로 잘 저장되었는지 확인! 
        $this->assertDatabaseHas('site_settings', [
            'setting_key' => 'couriers',
            'setting_value' => json_encode($payload['couriers'], JSON_UNESCAPED_UNICODE),
        ]);

        // 3. 서비스에서 다시 불러왔을 때 배열로 잘 변환되는지 확인! 
        $service = app(\App\Services\Admin\SettingManagementService::class);
        $settings = $service->getSettings();

        $this->assertIsArray($settings['couriers']);
        $this->assertCount(2, $settings['couriers']);
        $this->assertEquals('신규택배', $settings['couriers'][0]['name']);
        $this->assertEquals('번개배송', $settings['couriers'][1]['name']);
    }

    /** @test */
    public function 관리자_택배사_항목을_삭제할_수_있다(): void
    {
        // 1. 처음엔 2개 저장! 
        $payload = [
            'mall_name' => 'Active Women',
            'shipping_fee' => 3000,
            'free_shipping_threshold' => 50000,
            'point_earn_rate' => 1.0,
            'maintenance_mode' => false,
            'order_auto_cancel_hours' => 24,
            'couriers' => [
                ['name' => '삭제될택배', 'url' => 'https://del.com/{tracking_number}'],
                ['name' => '살아남은택배', 'url' => 'https://survive.com/{tracking_number}'],
            ]
        ];
        $this->actingAs($this->admin, 'admin')->patch(route('admin.settings.update'), $payload);

        // 2. 하나만 남겨서 다시 보내기 (UI에서 삭제 버튼 누른 것과 같은 효과!)
        $payload['couriers'] = [
            ['name' => '살아남은택배', 'url' => 'https://survive.com/{tracking_number}'],
        ];
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));

        // 3. 하나만 남았는지 확인! 
        $service = app(\App\Services\Admin\SettingManagementService::class);
        $settings = $service->getSettings();

        $this->assertCount(1, $settings['couriers']);
        $this->assertEquals('살아남은택배', $settings['couriers'][0]['name']);
    }

    /** @test */
    public function 점검_모드가_활성화되면_일반_사용자는_접속할_수_없다(): void
    {
        // 1. 점검 모드 활성화 
        $service = app(\App\Services\Admin\SettingManagementService::class);
        $service->updateSettings([
            'mall_name' => 'Active Women',
            'shipping_fee' => 3000,
            'free_shipping_threshold' => 50000,
            'point_earn_rate' => 1.0,
            'maintenance_mode' => true, // 점검 중!
            'order_auto_cancel_hours' => 24,
        ]);

        // 2. 일반 사용자로 메인 페이지 접속 시도
        $response = $this->get('/');

        // 503 점검 중 응답이 와야 함! 
        $response->assertStatus(503);
        $response->assertSee('서비스 점검 중입니다');
    }

    /** @test */
    public function 점검_모드_중에도_관리자는_사이트에_접속할_수_있다(): void
    {
        $this->withoutExceptionHandling();
        // 1. 점검 모드 활성화 
        $service = app(\App\Services\Admin\SettingManagementService::class);
        $service->updateSettings([
            'mall_name' => 'Active Women',
            'shipping_fee' => 3000,
            'free_shipping_threshold' => 50000,
            'point_earn_rate' => 1.0,
            'maintenance_mode' => true, // 점검 중!
            'order_auto_cancel_hours' => 24,
        ]);

        // 2. 관리자 권한으로 메인 페이지 접속 시도
        $response = $this->actingAs($this->admin, 'admin')->get('/');

        // 200 OK 응답이 와야 함! 
        $response->assertStatus(200);
    }
}
