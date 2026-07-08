<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'photo_url',
        'bio'
    ];


    public function movies()
    {
        return $this->belongsToMany(
            Movie::class,
            'movie_cast',
            'person_id',
            'movie_id'
        )
        ->withPivot([
            'credit_type',
            'character_name',
            'display_order'
        ]);
    }
}