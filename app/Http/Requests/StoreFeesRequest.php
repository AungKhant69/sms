<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|lte:remaining_amount',
            'payment_type' => 'required|in:Cash,Credit Card',
            'message' => 'nullable|string',
        ];
    }
}
