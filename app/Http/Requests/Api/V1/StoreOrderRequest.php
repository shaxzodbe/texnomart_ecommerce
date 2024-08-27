<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\DeliveryStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'product_price' => 'required|numeric',
            'delivery_price' => 'nullable|numeric',
            'total_price' => 'required|numeric',
            'status' => ['required', Rule::in(OrderStatusEnum::getAllCases())],
            'payment_status' => ['required', Rule::in(PaymentStatusEnum::getAllCases())],
            'delivery_status' => ['required', Rule::in(DeliveryStatusEnum::getAllCases())],
        ];
    }
}
