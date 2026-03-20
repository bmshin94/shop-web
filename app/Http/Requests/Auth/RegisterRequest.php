<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:members',
                function ($attribute, $value, $fail) {
                    $withdrawn = \DB::table('withdrawn_accounts')
                        ->where('email', $value)
                        ->where('withdrawn_at', '>=', now()->subDays(30))
                        ->first();
                    
                    if ($withdrawn) {
                        $availableDate = \Carbon\Carbon::parse($withdrawn->withdrawn_at)->addDays(30)->format('Y년 m월 d일');
                        $fail("해당 이메일은 탈퇴 후 30일이 지나지 않아 가입이 불가능합니다. ({$availableDate} 이후 가입 가능)");
                    }
                },
            ],
            'password' => [
                'required', 
                'string', 
                'min:8', 
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&^#_-])[A-Za-z\d@$!%*?&^#_-]{8,}$/'
            ],
            'password_confirm' => ['required', 'same:password'],
            'phone' => [
                'required', 
                'string', 
                'max:20',
                function ($attribute, $value, $fail) {
                    $phone = str_replace('-', '', $value);
                    $isVerified = \App\Models\PhoneVerification::where('phone', $phone)
                        ->where('is_verified', true)
                        ->exists();
                    if (!$isVerified) {
                        $fail('휴대폰 인증을 완료해주세요.');
                    }
                },
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => '이름을 입력해주세요.',
            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.unique' => '이미 가입된 이메일입니다.',
            'password.required' => '비밀번호를 입력해주세요.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.regex' => '비밀번호는 영문, 숫자, 특수문자를 모두 포함해야 합니다.',
            'password_confirm.required' => '비밀번호 확인을 입력해주세요.',
            'password_confirm.same' => '비밀번호가 일치하지 않습니다.',
            'phone.required' => '휴대폰 번호를 입력해주세요.',
        ];
    }
}
