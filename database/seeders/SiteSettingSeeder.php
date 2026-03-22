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
            'cs_hours' => '평일 10:00 ~ 17:00 (점심시간 12:00 ~ 13:30 / 토,일,공휴일 휴무)',
            'kakao_consult_url' => 'https://pf.kakao.com/_xxxxxx',
            'business_name' => 'Active Women',
            'business_number' => '123-45-67890',
            'representative_name' => '백민오빠',
            'mail_order_report_number' => '제 2026-서울강남-0000호',
            'business_address' => '서울특별시 강남구 가로수길 5, 1201호 (에스파빌딩)',
            'privacy_manager' => '백민오빠 (admin@admin.com)',
            'site_description' => '액티브한 여성들을 위한 프리미엄 스포츠웨어 스토어, Active Women입니다.',
            'site_keywords' => '요가복, 레깅스, 필라테스복, 여성스포츠웨어, 액티브웨어',
            'shipping_fee' => '3000',
            'free_shipping_threshold' => '50000',
            'point_earn_rate' => '1.0',
            'maintenance_mode' => '0',
            'alimtalk_test_mode' => '1',
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
