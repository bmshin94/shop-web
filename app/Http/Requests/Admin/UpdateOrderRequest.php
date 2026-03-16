<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateOrderRequest extends FormRequest
{
    /**
     * 관리자 주문 수정 요청을 허용한다.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 주문/배송 수정 입력값을 검증한다.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $orderStatus = $this->input('order_status');

        return [
            'order_status' => ['required', Rule::in(Order::ORDER_STATUSES)],
            'payment_status' => ['required', Rule::in(Order::PAYMENT_STATUSES)],
            'courier' => [
                Rule::requiredIf(fn (): bool => in_array($orderStatus, Order::SHIPPING_STARTED_STATUSES, true)),
                'nullable',
                'string',
                'max:100',
            ],
            'tracking_number' => [
                Rule::requiredIf(fn (): bool => in_array($orderStatus, Order::SHIPPING_STARTED_STATUSES, true)),
                'nullable',
                'string',
                'max:100',
            ],
            'admin_memo' => ['nullable', 'string', 'max:2000'],
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
            'order_status' => '주문상태',
            'payment_status' => '결제상태',
            'courier' => '택배사',
            'tracking_number' => '송장번호',
            'admin_memo' => '관리자 메모',
        ];
    }

    /**
     * 사용자에게 보여줄 검증 메시지를 정의한다.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'courier.required' => '배송중 이후 상태에서는 택배사를 반드시 입력해야 합니다.',
            'tracking_number.required' => '배송중 이후 상태에서는 송장번호를 반드시 입력해야 합니다.',
        ];
    }

    /**
     * 상태 조합의 일관성을 추가 검증한다.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $orderStatus = (string) $this->input('order_status');
            $paymentStatus = (string) $this->input('payment_status');

            if (in_array($orderStatus, Order::SHIPPING_STARTED_STATUSES, true) && $paymentStatus === '결제대기') {
                $validator->errors()->add('payment_status', '결제대기 상태의 주문은 출고 또는 배송 처리할 수 없습니다.');
            }

            if (in_array($paymentStatus, Order::PAYMENT_CANCELLED_STATUSES, true) && !in_array($orderStatus, Order::PAYMENT_CANCELLED_STATUSES, true)) {
                $validator->errors()->add('order_status', '환불 또는 취소가 완료된 주문은 주문상태를 취소완료나 환불완료로 맞춰야 합니다.');
            }
        });
    }

    /**
     * 빈 문자열을 null로 정리해 저장 데이터를 안정화한다.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'courier' => $this->normalizeNullableText($this->input('courier')),
            'tracking_number' => $this->normalizeNullableText($this->input('tracking_number')),
            'admin_memo' => $this->normalizeNullableText($this->input('admin_memo')),
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
