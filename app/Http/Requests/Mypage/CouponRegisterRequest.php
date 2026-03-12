<?php

namespace App\Http\Requests\Mypage;

use Illuminate\Foundation\Http\FormRequest;

class CouponRegisterRequest extends FormRequest
{
    /**
     * 사용자의 요청 권한 확인
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 쿠폰 등록 유효성 검사 규칙
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_]+$/' // 영문, 숫자, 언더바만 허용하는 명품 규칙!
            ],
        ];
    }

    /**
     * 유효성 검사 에러 메시지
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'code.required' => '쿠폰 번호를 입력해 주세요.',
            'code.string' => '올바른 형식의 쿠폰 번호가 아닙니다.',
            'code.max' => '쿠폰 번호는 최대 50자까지 입력 가능합니다.',
            'code.regex' => '쿠폰 번호는 영문, 숫자, 언더바(_)만 포함할 수 있습니다.',
        ];
    }
}
