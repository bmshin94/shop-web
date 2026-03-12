<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * 사용자의 요청 권한을 확인합니다.
     */
    public function authorize(): bool
    {
        return true; // 관리자 미들웨어에서 이미 걸러지므로 true 반환
    }

    /**
     * 유효성 검사 규칙을 정의합니다.
     */
    public function rules(): array
    {
        $couponId = $this->route('coupon'); // 수정 시 현재 쿠폰 ID

        return [
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:50',
                'unique:coupons,code,' . $couponId,
            ],
            'type' => 'required|in:discount,shipping',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->input('discount_type') === 'percent' && $value > 100) {
                        $fail('비율 할인 시 할인 값은 100을 넘을 수 없습니다.');
                    }
                },
            ],
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * 에러 메시지를 커스텀합니다.
     */
    public function messages(): array
    {
        return [
            'name.required' => '쿠폰명은 필수 입력 사항입니다.',
            'type.required' => '쿠폰 유형을 선택해 주세요.',
            'discount_type.required' => '할인 방식을 선택해 주세요.',
            'discount_value.required' => '할인 값을 입력해 주세요.',
            'discount_value.numeric' => '할인 값은 숫자여야 합니다.',
            'discount_value.min' => '할인 값은 0 이상이어야 합니다.',
            'code.unique' => '이미 사용 중인 쿠폰 코드입니다.',
            'ends_at.after_or_equal' => '종료일은 시작일보다 빠를 수 없습니다.',
        ];
    }
}
