<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:150|unique:companies,name',
            'slug' => 'required|string|max:150|unique:companies,slug',
            'email' => 'nullable|email|max:150|unique:companies,email',
            'phone' => 'nullable|string|max:20|filled|unique:companies,phone',
            'logo_url' => 'nullable|url|max:500',
            'pan_vat_no' => 'nullable|string|max:50|unique:companies,pan_vat_no',
            'owner_details' => 'nullable|array',
            'owner_details.name' => 'required_with:owner_details|string|max:100',
            'owner_details.email' => 'required_with:owner_details|email|max:150|unique:users,email',
            'owner_details.phone' => 'nullable|string|max:20|filled|unique:users,phone',
            'owner_details.password' => 'required_with:owner_details|string|min:8|max:255|confirmed',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The company name is required.',
            'name.unique' => 'A company with this name already exists.',
            'name.regex' => 'The company name contains invalid characters.',
            'slug.required' => 'The company slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.lowercase' => 'The slug must be in lowercase.',
            'email.unique' => 'This email is already registered.',
            'email.email' => 'Please enter a valid email address.',
            'phone.unique' => 'This phone number is already registered.',
            'logo_url.url' => 'Please enter a valid URL for the logo.',
            'pan_vat_no.unique' => 'This PAN/VAT number is already registered.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
