<?php

namespace App\Http\Requests\Cinema;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CinemaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cinema = $this->route('cinema');

        return [
            'company_id'     => 'required|exists:companies,id',
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('cinemas')
                    ->where(fn($query) => $query->where('company_id', $this->company_id)),
            ],

            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('cinemas')
                    ->where(fn($query) => $query->where('company_id', $this->company_id)),
            ],
            'address'        => 'required|string|max:300',
            'country'        => 'nullable|string|max:100',
            'city'           => 'required|string|max:100',
            'area'           => 'nullable|string|max:100',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150',
            'timezone'       => 'nullable|string|max:50',
            'is_active'      => 'nullable|boolean',
        ];
    }
}
