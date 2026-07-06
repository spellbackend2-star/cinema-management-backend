<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('staff'));
    }

    public function rules(): array
    {
        $staffId = $this->route('staff'); // adjust if your route param differs

        return [
            'name' => ['sometimes', 'required', 'string', 'max:150'],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($staffId),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            'password' => ['nullable', 'string', 'min:8', 'confirmed'],


            'role' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['Branch_manager', 'cashier', 'ticket_checker']), // matches assignableRoles, excludes customer + company_admin
            ],

            'cinema_id' => ['nullable', 'integer', 'exists:cinemas,id'],

            'is_active' => ['sometimes', 'boolean'],

            // company_id intentionally NOT accepted here — reassigning a staff
            // member's company is a separate, more sensitive action.
            // If you need it, add a dedicated endpoint restricted to super-admin.
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email already exists.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
