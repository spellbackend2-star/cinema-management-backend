<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = $this->route('company');
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:150',
                // This excludes the current record
                Rule::unique('companies')->ignore($id)
            ],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:150',
                // This excludes the current record
                Rule::unique('companies')->ignore($id),
                'regex:/^[a-z0-9-]+$/',
                'lowercase'
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
                // This excludes the current record
                Rule::unique('companies')->ignore($id)
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                // This excludes the current record
                Rule::unique('companies')->ignore($id)
            ],
            'logo_url' => [
                'nullable',
                'url',
                'max:500'
            ],
            'pan_vat_no' => [
                'nullable',
                'string',
                'max:50',
                // This excludes the current record
                Rule::unique('companies')->ignore($id),
                'regex:/^[a-zA-Z0-9\-]+$/'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {

            return [
            'name.required' => 'The company name is required.',
            'name.unique' => 'A company with this name already exists.',
            'slug.required' => 'The company slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.lowercase' => 'The slug must be in lowercase.',
            'email.unique' => 'This email is already registered.',
            'email.email' => 'Please enter a valid email address.',
            'phone.unique' => 'This phone number is already registered.',
            'logo_url.url' => 'Please enter a valid URL for the logo.',
            'pan_vat_no.unique' => 'This PAN/VAT number is already registered.',
            'pan_vat_no.regex' => 'The PAN/VAT number contains invalid characters.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
