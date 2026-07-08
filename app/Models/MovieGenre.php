<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieGenre extends Model
{
    protected $table = 'movie_genres';

    public $timestamps = false;

    protected $fillable = [
        'movie_id',
        'genre_id',
    ];


    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_genres');
    }
}
