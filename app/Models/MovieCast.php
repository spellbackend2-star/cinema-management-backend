<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieCast extends Model
{
    public $timestamps = false;

    protected $table = 'movie_cast';


    protected $fillable = [
        'movie_id',
        'person_id',
        'credit_type',
        'character_name',
        'display_order'
    ];


    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }


    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}