<?php

namespace App\Http\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRefundStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // handled by policy in controller
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['PROCESSED', 'FAILED'])],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}