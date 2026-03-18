<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '이메일 주소를 입력해주세요.',
            'code.required' => '인증번호를 입력해주세요.',
            'code.size' => '인증번호는 6자리여야 합니다.',
        ];
    }
}
