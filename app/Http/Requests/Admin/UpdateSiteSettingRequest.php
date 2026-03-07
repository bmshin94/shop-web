<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    /**
     * 관리자 기본 설정 수정 요청을 허용한다.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 기본 설정 입력값을 검증한다.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'mall_name' => ['required', 'string', 'max:100'],
            'customer_center_phone' => ['nullable', 'string', 'max:30'],
            'customer_center_email' => ['nullable', 'email', 'max:120'],
            'business_name' => ['nullable', 'string', 'max:100'],
            'business_number' => ['nullable', 'string', 'max:30'],
            'shipping_fee' => ['required', 'integer', 'min:0', 'max:1000000'],
            'free_shipping_threshold' => ['required', 'integer', 'min:0', 'max:10000000'],
            'point_earn_rate' => ['required', 'numeric', 'between:0,100'],
            'maintenance_mode' => ['required', 'boolean'],
            'order_auto_cancel_hours' => ['required', 'integer', 'min:1', 'max:720'],
        ];
    }

    /**
     * 필드명을 한글로 변환한다.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'mall_name' => '쇼핑몰명',
            'customer_center_phone' => '고객센터 전화번호',
            'customer_center_email' => '고객센터 이메일',
            'business_name' => '상호명',
            'business_number' => '사업자등록번호',
            'shipping_fee' => '기본 배송비',
            'free_shipping_threshold' => '무료배송 기준금액',
            'point_earn_rate' => '포인트 적립률',
            'maintenance_mode' => '점검 모드',
            'order_auto_cancel_hours' => '미결제 자동취소 시간',
        ];
    }

    /**
     * 기본 설정 수정 전에 입력값을 정리한다.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'mall_name' => trim((string) $this->input('mall_name')),
            'customer_center_phone' => $this->normalizeNullableText($this->input('customer_center_phone')),
            'customer_center_email' => $this->normalizeNullableText($this->input('customer_center_email')),
            'business_name' => $this->normalizeNullableText($this->input('business_name')),
            'business_number' => $this->normalizeNullableText($this->input('business_number')),
            'maintenance_mode' => $this->boolean('maintenance_mode'),
        ]);
    }

    /**
     * 텍스트 입력값을 trim 후 nullable 문자열로 변환한다.
     *
     * @param  mixed  $value
     * @return string|null
     */
    private function normalizeNullableText(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
