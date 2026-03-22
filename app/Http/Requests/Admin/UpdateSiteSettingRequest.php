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
            'cs_hours' => ['nullable', 'string', 'max:255'],
            'kakao_consult_url' => ['nullable', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:100'],
            'business_number' => ['nullable', 'string', 'max:30'],
            'representative_name' => ['nullable', 'string', 'max:50'],
            'mail_order_report_number' => ['nullable', 'string', 'max:100'],
            'business_address' => ['nullable', 'string', 'max:255'],
            'privacy_manager' => ['nullable', 'string', 'max:100'],
            'site_description' => ['nullable', 'string', 'max:255'],
            'site_keywords' => ['nullable', 'string', 'max:255'],
            'shipping_fee' => ['required', 'integer', 'min:0', 'max:1000000'],
            'free_shipping_threshold' => ['required', 'integer', 'min:0', 'max:10000000'],
            'point_earn_rate' => ['required', 'numeric', 'between:0,100'],
            'maintenance_mode' => ['required', 'boolean'],
            'alimtalk_test_mode' => ['required', 'boolean'],
            'order_auto_cancel_hours' => ['required', 'integer', 'min:1', 'max:720'],
            'couriers' => ['nullable', 'array'],
            'couriers.*.name' => ['required_with:couriers', 'string', 'max:50'],
            'couriers.*.url' => ['required_with:couriers', 'string', 'max:255'],
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
            'cs_hours' => '고객센터 운영시간',
            'kakao_consult_url' => '카카오톡 상담 URL',
            'business_name' => '상호명',
            'business_number' => '사업자등록번호',
            'representative_name' => '대표자명',
            'mail_order_report_number' => '통신판매업 신고번호',
            'business_address' => '사업장 소재지',
            'privacy_manager' => '개인정보관리책임자',
            'site_description' => 'SEO 사이트 설명',
            'site_keywords' => 'SEO 사이트 키워드',
            'shipping_fee' => '기본 배송비',
            'free_shipping_threshold' => '무료배송 기준금액',
            'point_earn_rate' => '포인트 적립률',
            'maintenance_mode' => '점검 모드',
            'alimtalk_test_mode' => '알림 발송 테스트 모드',
            'order_auto_cancel_hours' => '미결제 자동취소 시간',
            'couriers' => '택배사 설정',
            'couriers.*.name' => '택배사명',
            'couriers.*.url' => '추적 URL',
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
            'cs_hours' => $this->normalizeNullableText($this->input('cs_hours')),
            'kakao_consult_url' => $this->normalizeNullableText($this->input('kakao_consult_url')),
            'business_name' => $this->normalizeNullableText($this->input('business_name')),
            'business_number' => $this->normalizeNullableText($this->input('business_number')),
            'representative_name' => $this->normalizeNullableText($this->input('representative_name')),
            'mail_order_report_number' => $this->normalizeNullableText($this->input('mail_order_report_number')),
            'business_address' => $this->normalizeNullableText($this->input('business_address')),
            'privacy_manager' => $this->normalizeNullableText($this->input('privacy_manager')),
            'site_description' => $this->normalizeNullableText($this->input('site_description')),
            'site_keywords' => $this->normalizeNullableText($this->input('site_keywords')),
            'maintenance_mode' => $this->boolean('maintenance_mode'),
            'alimtalk_test_mode' => $this->boolean('alimtalk_test_mode'),
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
