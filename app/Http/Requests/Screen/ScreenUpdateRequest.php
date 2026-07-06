<?php

namespace App\Http\Requests\Screen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScreenUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // tighten if only company_admin/cinema_admin should update
    }

    public function rules(): array
    {
        $screenId = $this->route('screen'); // adjust if your route param differs

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('screens', 'name')
                    ->where(fn ($query) => $query->where(
                        'cinema_id',
                        $this->input('cinema_id') ?? $this->route('screen')?->cinema_id
                    ))
                    ->ignore($screenId),
            ],

            'screen_type' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['STANDARD', 'IMAX', '3D', '4DX', 'DOLBY_ATMOS', 'RECLINER_HALL']),
            ],

            'capacity' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:2000',
            ],

            'sound_system' => [
                'nullable',
                'string',
                'max:50',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],

            // cinema_id intentionally NOT accepted here — moving a screen to a
            // different cinema is a sensitive structural change (would orphan
            // its existing seats/sessions). Add a dedicated endpoint if needed.
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A screen with this name already exists in this cinema.',
            'screen_type.in' => 'Invalid screen type selected.',
            'capacity.max' => 'Capacity seems unrealistically high — please verify.',
        ];
    }
}