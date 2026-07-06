<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StaffIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return $this->user()->hasRole('company_admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'search'      => ['nullable', 'string', 'max:100'],
            'role_id'     => ['nullable', 'integer', 'exists:roles,id'],
            'company_id'  => ['nullable', 'integer', 'exists:companies,id'],
            'cinema_id'   => ['nullable', 'integer', 'exists:cinemas,id'],
            'status'      => ['nullable', 'boolean'],
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'        => ['nullable', 'integer', 'min:1'],
            'sort_by'     => ['nullable', 'in:id,name,email,created_at'],
            'sort_order'  => ['nullable', 'in:asc,desc'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'role_id.exists'    => 'Selected role does not exist.',
            'company_id.exists' => 'Selected company does not exist.',
            'cinema_id.exists'  => 'Selected cinema does not exist.',
        ];
    }
}