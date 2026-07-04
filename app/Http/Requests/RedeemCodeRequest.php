<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedeemCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:3', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'يرجى إدخال كود الشحن.',
            'code.min'      => 'كود الشحن قصير جداً.',
            'code.max'      => 'كود الشحن طويل جداً.',
        ];
    }
}
