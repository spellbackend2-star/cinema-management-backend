<?php

namespace App\Http\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;

class ProcessRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Any authenticated user can request; payment ownership checked in controller/policy if needed.
        return true;
    }

   public function rules(): array
{
    return [
        'admin_response' => ['nullable', 'string', 'max:1000'],
    ];
}
}