<?php

namespace App\Http\Requests\Admin;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * 관리자 회원 수정 요청을 허용한다.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 회원 수정 입력값을 검증한다.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Member|null $member */
        $member = $this->route('member');

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('members', 'email')->ignore($member?->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(Member::STATUSES)],
        ];
    }

    /**
     * 필드명을 한국어로 치환한다.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => '회원명',
            'email' => '이메일',
            'phone' => '연락처',
            'status' => '회원상태',
        ];
    }

    /**
     * 빈 문자열을 null로 정리해 저장 데이터를 안정화한다.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => trim((string) $this->input('email')),
            'phone' => $this->normalizeNullableText($this->input('phone')),
            'status' => trim((string) $this->input('status')),
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
