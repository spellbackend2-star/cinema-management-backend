<?php

namespace App\Http\Requests\Movie;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:200',
                Rule::unique('movies')->where(function ($query) {
                    return $query->whereDate('release_date', $this->release_date);
                }),
            ],
            'original_title' => 'nullable|string|max:200',
            'description' => 'nullable|string',

            'duration_min' => 'required|integer|min:1|max:600',

            'status' => 'required|in:UPCOMING,RUNNING,ENDED',

            'release_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $status = $this->status;
                    $releaseDate = Carbon::parse($value)->startOfDay();
                    $today = Carbon::today();

                    switch ($status) {
                        case 'UPCOMING':
                            if ($releaseDate->lt($today)) {
                                $fail('The release date must be today or later for an upcoming movie.');
                            }
                            break;

                        case 'RUNNING':
                            if ($releaseDate->gt($today)) {
                                $fail('A running movie cannot have a future release date.');
                            }
                            break;

                        case 'ENDED':
                            if ($releaseDate->gte($today)) {
                                $fail('An ended movie must have a past release date.');
                            }
                            break;
                    }
                },
            ],

            'production_house' => 'nullable|string|max:150',
            'country' => 'nullable|string|max:100',

            'censor_rating' => 'required|in:U,U/A,A,S',

            'poster_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'trailer_url' => 'nullable|url|max:500',

            'imdb_rating' => 'nullable|numeric|between:0,10',

            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',


            // Languages (movie_languages)
            'languages' => 'required|array|min:1',
            'languages.*.language_id' => 'required|exists:languages,id',
            'languages.*.is_original' => 'nullable|boolean',

            //Cast & Crew (movie_cast)
            'people' => 'nullable|array',
            'people.*.person_id' => 'required|exists:people,id',
            'people.*.credit_type' => 'required|in:ACTOR,DIRECTOR,PRODUCER,WRITER,MUSIC',
            'people.*.character_name' => 'nullable|string|max:150',
            'people.*.display_order' => 'nullable|integer|min:1',
        ];
    }
    public function messages(): array
    {
        return [
            'title.unique' => 'A movie with this title and release date already exists.',
        ];
    }
}
