<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
         return $this->user()->hasRole('company_admin');
    }

    public function rules(): array
    {
        $loggedInUser = $this->user();

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            

            'role' => [
                'required',
                'string',
                Rule::in($this->assignableRoles()),
            ],

            'company_id' => [
                $loggedInUser->hasRole('company_admin') ? 'nullable' : 'prohibited',
                'integer',
                'exists:companies,id',
            ],

            'cinema_id' => ['nullable', 'integer', 'exists:cinemas,id'],

            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email already exists.',
            'company_id.prohibited' => 'You are not allowed to set the company for this staff member.',
        ];
    }

    /**
     * Roles the current user is allowed to assign.
     */
    private function assignableRoles(): array
    {
        $user = $this->user();

       
        if ($user->hasRole('company_admin')) {
            return ['Branch_manager', 'cashier', 'ticket_checker'];
        }

        return [];
    }
}