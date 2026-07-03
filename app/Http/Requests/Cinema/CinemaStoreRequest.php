<?php

namespace App\Http\Requests\Cinema;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CinemaStoreRequest extends FormRequest
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
            'name' => 'required|string|max:150|unique:cinemas,name',
            'slug' => 'required|string|max:150|unique:cinemas,slug',
            'email' => 'nullable|email|max:150|unique:cinemas,email',
            'phone' => 'nullable|string|max:20|filled|unique:cinemas,phone',
            'logo_url' => 'nullable|url|max:500',
            'pan_vat_no' => 'nullable|string|max:50|unique:cinemas,pan_vat_no',
            'is_active' => 'boolean',
            'address' => 'required|string|max:300',
            'country' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'timezone' => 'nullable|string|max:50',     
        ];
    }
}
