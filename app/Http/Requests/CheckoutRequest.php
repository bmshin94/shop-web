<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipient_name' => 'required|string|max:50',
            'recipient_phone' => 'required|string|max:20',
            'recipient_zipcode' => 'required|string|max:10',
            'recipient_address' => 'required|string|max:255',
            'recipient_detail_address' => 'nullable|string|max:255',
            'shipping_message' => 'nullable|string|max:500',
            'payment_method' => 'required|string|in:card,vbank,kakaopay,naverpay',
            'applied_points' => 'nullable|integer|min:0',
            // 실제 장바구니/바로구매 아이템 데이터는 세션 우선 고려
        ];
    }
}
