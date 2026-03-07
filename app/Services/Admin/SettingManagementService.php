<?php

namespace App\Services\Admin;

use App\Models\SiteSetting;

class SettingManagementService
{
    /**
     * 기본 설정의 기본값을 정의한다.
     */
    private const DEFAULT_SETTINGS = [
        'mall_name' => 'HER FIELD',
        'customer_center_phone' => '1588-0000',
        'customer_center_email' => 'support@herfield.example',
        'business_name' => 'HER FIELD',
        'business_number' => '',
        'shipping_fee' => 3000,
        'free_shipping_threshold' => 50000,
        'point_earn_rate' => 1.0,
        'maintenance_mode' => false,
        'order_auto_cancel_hours' => 24,
    ];

    /**
     * 타입별 변환 기준을 정의한다.
     */
    private const VALUE_TYPES = [
        'mall_name' => 'string',
        'customer_center_phone' => 'string',
        'customer_center_email' => 'string',
        'business_name' => 'string',
        'business_number' => 'string',
        'shipping_fee' => 'int',
        'free_shipping_threshold' => 'int',
        'point_earn_rate' => 'float',
        'maintenance_mode' => 'bool',
        'order_auto_cancel_hours' => 'int',
    ];

    /**
     * 관리자 기본 설정을 조회한다.
     *
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        $settings = self::DEFAULT_SETTINGS;

        $stored = SiteSetting::query()
            ->whereIn('setting_key', array_keys(self::DEFAULT_SETTINGS))
            ->pluck('setting_value', 'setting_key')
            ->all();

        foreach ($stored as $key => $value) {
            if (! array_key_exists($key, $settings)) {
                continue;
            }

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
    private function castSettingValue(string $key, ?string $value): mixed
    {
        $type = self::VALUE_TYPES[$key] ?? 'string';

        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => $value === '1',
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

        return match ($type) {
            'int' => (string) ((int) $value),
            'float' => (string) ((float) $value),
            'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0',
            default => trim((string) $value),
        };
    }
}
