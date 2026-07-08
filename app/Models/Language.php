<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'iso_code'
    ];


    public function movies()
    {
        return $this->belongsToMany(
            Movie::class,
            'movie_languages',
            'language_id',
            'movie_id'
        )
        ->withPivot('is_original');
    }
}