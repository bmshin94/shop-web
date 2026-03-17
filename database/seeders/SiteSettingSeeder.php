<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'mall_name' => 'Active Women',
            'customer_center_phone' => '1588-0000',
            'customer_center_email' => 'support@herfield.example',
            'business_name' => 'Active Women',
            'business_number' => '123-45-67890',
            'shipping_fee' => '3000',
            'free_shipping_threshold' => '50000',
            'point_earn_rate' => '1.0',
            'maintenance_mode' => '0',
            'alimtalk_test_mode' => '1', // 기본값은 테스트 모드(1)로 설정! ✨
            'order_auto_cancel_hours' => '24',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }
    }
}
