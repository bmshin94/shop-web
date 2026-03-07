<?php

namespace App\Http\Requests\Admin;

use App\Models\Exhibition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreExhibitionRequest extends FormRequest
{
    /**
     * 관리자 기획전 등록 요청을 허용한다.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 기획전 등록 입력값을 검증한다.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:160', Rule::unique('exhibitions', 'slug')],
            'status' => ['required', Rule::in(Exhibition::STATUSES)],
            'banner_image_url' => ['nullable', 'url', 'max:2048'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
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
            'title' => '기획전명',
            'slug' => '슬러그',
            'status' => '상태',
            'banner_image_url' => '배너 이미지 URL',
            'summary' => '요약',
            'description' => '상세 설명',
            'start_at' => '시작 일시',
            'end_at' => '종료 일시',
            'sort_order' => '정렬 순서',
        ];
    }

    /**
     * 기획전 등록 전에 입력값을 정리한다.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title'));
        $slugInput = trim((string) $this->input('slug'));
        $slug = $slugInput !== '' ? $slugInput : Str::slug($title, '-');

        $this->merge([
            'title' => $title,
            'slug' => $slug,
            'status' => trim((string) $this->input('status')),
            'banner_image_url' => $this->normalizeNullableText($this->input('banner_image_url')),
            'summary' => $this->normalizeNullableText($this->input('summary')),
            'description' => $this->normalizeNullableText($this->input('description')),
            'start_at' => $this->normalizeNullableText($this->input('start_at')),
            'end_at' => $this->normalizeNullableText($this->input('end_at')),
            'sort_order' => $this->normalizeSortOrder($this->input('sort_order')),
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

    /**
     * 정렬순서를 정수 문자열로 정리한다.
     *
     * @param  mixed  $value
     * @return int|null
     */
    private function normalizeSortOrder(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }
}
