<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You already check permission in controller
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'],

            'permissions' => ['required', 'array', 'min:1'],

            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Role is required',
            'permissions.required' => 'Permissions are required',
            'permissions.array' => 'Permissions must be an array',
        ];
    }
}