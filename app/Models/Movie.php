<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'original_title',
        'description',
        'duration_min',
        'release_date',
        'production_house',
        'country',
        'censor_rating',
        'poster_url',
        'banner_url',
        'trailer_url',
        'status',
        'imdb_rating',
    ];

    protected $casts = [
        'release_date' => 'date',
        'imdb_rating' => 'decimal:1',
    ];


    // Movie belongs to many Genres
    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'movie_genres',
            'movie_id',
            'genre_id'
        );
    }


    // Movie belongs to many Languages
    public function languages()
    {
        return $this->belongsToMany(
            Language::class,
            'movie_languages',
            'movie_id',
            'language_id'
        )
        ->withPivot('is_original');
    }


   
   


    // Get people through movie_cast
    public function people()
    {
        return $this->belongsToMany(
            Person::class,
            'movie_cast',
            'movie_id',
            'person_id'
        )
        ->withPivot([
            'credit_type',
            'character_name',
            'display_order'
        ]);
    }
}