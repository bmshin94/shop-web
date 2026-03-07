<?php

namespace App\Http\Requests\Admin;

use App\Models\Operator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOperatorRequest extends FormRequest
{
    /**
     * 관리자 운영자 수정 요청을 허용한다.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 운영자 수정 입력값을 검증한다.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Operator|null $operator */
        $operator = $this->route('operator');
        $menuKeys = array_keys(Operator::menuDefinitions());

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('operators', 'email')->ignore($operator?->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(Operator::STATUSES)],
            'menu_permissions_submitted' => ['nullable', 'boolean'],
            'menu_permissions' => ['nullable', 'array'],
            'menu_permissions.*' => ['string', Rule::in($menuKeys)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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
            'name' => '운영자명',
            'email' => '이메일',
            'phone' => '연락처',
            'status' => '운영자상태',
            'password' => '비밀번호',
            'password_confirmation' => '비밀번호 확인',
        ];
    }

    /**
     * 빈 문자열을 null로 정리해 저장 데이터를 안정화한다.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $menuPermissions = collect($this->input('menu_permissions', []))
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->map(fn ($value) => trim((string) $value))
            ->values()
            ->all();

        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => trim((string) $this->input('email')),
            'phone' => $this->normalizeNullableText($this->input('phone')),
            'status' => trim((string) $this->input('status')),
            'menu_permissions' => $menuPermissions,
            'password' => $this->normalizeNullableText($this->input('password')),
            'password_confirmation' => $this->normalizeNullableText($this->input('password_confirmation')),
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
