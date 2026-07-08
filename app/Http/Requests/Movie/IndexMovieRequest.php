<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class IndexMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
                'max:100'
            ],

            'status' => [
                'nullable',
                'in:UPCOMING,RUNNING,ENDED'
            ],

            'release_date' => [
                'nullable',
                'date'
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ],
        ];
    }
}