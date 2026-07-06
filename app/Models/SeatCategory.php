<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'screen_id',
        'name',
        'image_icon',
        'display_order',
    ];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}