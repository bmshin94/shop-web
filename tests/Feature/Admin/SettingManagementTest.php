<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 관리자_기본_설정_화면에_접근할_수_있다(): void
    {
        $response = $this->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('기본 설정');
    }

    /** @test */
    public function 관리자_기본_설정을_저장할_수_있다(): void
    {
        $payload = [
            'mall_name' => 'HER FIELD LAB',
            'customer_center_phone' => '02-1234-5678',
            'customer_center_email' => 'help@herfield.test',
            'business_name' => 'HER FIELD Co.',
            'business_number' => '123-45-67890',
            'shipping_fee' => 4000,
            'free_shipping_threshold' => 70000,
            'point_earn_rate' => 2.5,
            'maintenance_mode' => true,
            'order_auto_cancel_hours' => 48,
        ];

        $response = $this->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertDatabaseHas('site_settings', [
            'setting_key' => 'mall_name',
            'setting_value' => 'HER FIELD LAB',
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
}
