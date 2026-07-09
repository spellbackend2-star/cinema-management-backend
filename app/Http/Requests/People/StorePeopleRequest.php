<?php

namespace App\Http\Requests\People;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePeopleRequest extends FormRequest
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
            'name' => 'required|string|max:150|unique:people,name',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:MALE,FEMALE,OTHER',
            'nationality' => 'nullable|string|max:100',
            'photo_url' => 'nullable|url|max:500',
            'bio' => 'nullable|string',
        ];
    }
}
