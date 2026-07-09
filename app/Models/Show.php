<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'movie_id',
        'screen_id',
        'start_time',
        'end_time',
        'language_id',
        'booking_open_at',
        'booking_close_at',
        'format',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'show_date' => 'date',
        'booking_open_at' => 'datetime',
        'booking_close_at' => 'datetime',
    ];

    /**
     * Schedule
     */
    public function schedule()
    {
        return $this->belongsTo(ShowSchedule::class, 'schedule_id');
    }

    /**
     * Movie
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Screen
     */
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Language
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Seat Prices
     */
    public function prices()
    {
        return $this->hasMany(ShowPrice::class);
    }

    /**
     * Show Seats
     */
    public function seats()
    {
        return $this->hasMany(ShowSeat::class);
    }
    public function showSeats()
    {
        return $this->hasMany(ShowSeat::class);
    }
}
