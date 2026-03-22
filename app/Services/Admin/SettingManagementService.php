<?php

namespace App\Services\Admin;

use App\Models\SiteSetting;

class SettingManagementService
{
    /**
     * 기본 설정의 기본값을 정의한다.
     */
    private const DEFAULT_SETTINGS = [
        'mall_name' => 'Active Women',
        'customer_center_phone' => '1588-0000',
        'customer_center_email' => 'support@herfield.example',
        'cs_hours' => '',
        'kakao_consult_url' => '',
        'business_name' => 'Active Women',
        'business_number' => '',
        'representative_name' => '',
        'mail_order_report_number' => '',
        'business_address' => '',
        'privacy_manager' => '',
        'site_description' => '',
        'site_keywords' => '',
        'shipping_fee' => 3000,
        'free_shipping_threshold' => 50000,
        'point_earn_rate' => 1.0,
        'welcome_points' => 3000,
        'review_reward_points' => 500,
        'min_use_points' => 1000,
        'point_expiry_months' => 12,
        'maintenance_mode' => false,
        'alimtalk_test_mode' => true,
        'order_auto_cancel_hours' => 24,
        'couriers' => [
            ['name' => 'CJ대한통운', 'url' => 'https://www.doortodoor.co.kr/parcel/doortodoor_search.jsp?f_invc_no={tracking_number}'],
            ['name' => '한진택배', 'url' => 'https://www.hanjin.com/kor/CMS/DeliveryMgr/WaybillResult.do?mCode=MN038&wblNum={tracking_number}'],
            ['name' => '롯데택배', 'url' => 'https://www.lotteglogis.com/home/reservation/tracking/linkView?InvNo={tracking_number}'],
            ['name' => '로젠택배', 'url' => 'https://www.ilogen.com/web/personal/trace/{tracking_number}'],
            ['name' => '우체국택배', 'url' => 'https://service.epost.go.kr/trace.RetrieveDomRcvTracePost.comm?POST_STR={tracking_number}'],
            ['name' => '경동택배', 'url' => 'https://kdexp.com/basic_search.kd?barcode={tracking_number}'],
            ['name' => '대신택배', 'url' => 'https://www.daesin.co.kr/kr/service/service01.jsp?wbl={tracking_number}'],
            ['name' => 'CU 편의점택배', 'url' => 'https://www.cupost.co.kr/post/tracking/tracking.cupost?inv_no={tracking_number}'],
            ['name' => 'GS25 편의점택배', 'url' => 'https://www.cvsnet.co.kr/invoice/tracking.do?invoice_no={tracking_number}']
        ],
    ];

    /**
     * 타입별 변환 기준을 정의한다.
     */
    private const VALUE_TYPES = [
        'mall_name' => 'string',
        'customer_center_phone' => 'string',
        'customer_center_email' => 'string',
        'cs_hours' => 'string',
        'kakao_consult_url' => 'string',
        'business_name' => 'string',
        'business_number' => 'string',
        'representative_name' => 'string',
        'mail_order_report_number' => 'string',
        'business_address' => 'string',
        'privacy_manager' => 'string',
        'site_description' => 'string',
        'site_keywords' => 'string',
        'shipping_fee' => 'int',
        'free_shipping_threshold' => 'int',
        'point_earn_rate' => 'float',
        'welcome_points' => 'int',
        'review_reward_points' => 'int',
        'min_use_points' => 'int',
        'point_expiry_months' => 'int',
        'maintenance_mode' => 'bool',
        'alimtalk_test_mode' => 'bool',
        'order_auto_cancel_hours' => 'int',
        'couriers' => 'json',
    ];

    /**
     * 관리자 기본 설정을 조회한다.
     *
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        $settings = [];
        $stored = SiteSetting::query()
            ->whereIn('setting_key', array_keys(self::DEFAULT_SETTINGS))
            ->pluck('setting_value', 'setting_key')
            ->all();

        foreach (self::DEFAULT_SETTINGS as $key => $defaultValue) {
            $value = $stored[$key] ?? $defaultValue;
            $settings[$key] = $this->castSettingValue($key, $value);
        }

        return $settings;
    }

    /**
     * 관리자 기본 설정을 저장한다.
     *
     * @param  array<string, mixed>  $payload
     * @return void
     */
    public function updateSettings(array $payload): void
    {
        $upserts = [];
        $now = now();

        foreach (self::DEFAULT_SETTINGS as $key => $defaultValue) {
            if (! array_key_exists($key, $payload)) {
                continue;
            }

            $upserts[] = [
                'setting_key' => $key,
                'setting_value' => $this->normalizeSettingValue($key, $payload[$key]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($upserts === []) {
            return;
        }

        SiteSetting::query()->upsert(
            $upserts,
            ['setting_key'],
            ['setting_value', 'updated_at']
        );
    }

    /**
     * 저장된 설정값을 타입에 맞춰 변환한다.
     *
     * @param  string  $key
     * @param  string|null  $value
     * @return mixed
     */
    private function castSettingValue(string $key, mixed $value): mixed
    {
        $type = self::VALUE_TYPES[$key] ?? 'string';

        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => $value === '1',
            'json' => is_array($value) ? $value : json_decode((string) ($value ?? '[]'), true),
            default => (string) ($value ?? ''),
        };
    }

    /**
     * 입력 설정값을 문자열로 정규화한다.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return string
     */
    private function normalizeSettingValue(string $key, mixed $value): string
    {
        $type = self::VALUE_TYPES[$key] ?? 'string';

        if ($type === 'bool') {
            return $value ? '1' : '0';
        }

        return match ($type) {
            'int' => (string) ((int) $value),
            'float' => (string) ((float) $value),
            'json' => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
            default => trim((string) $value),
        };
    }
}
