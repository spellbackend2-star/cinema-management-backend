<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShowSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'screen_id',
        'start_date',
        'end_date',
        'show_time',
        'days_of_week',
        'language_id',
        'format',
        'booking_opens_offset_min',
        'booking_closes_offset_min',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'show_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
    ];

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
     * Shows generated from this schedule
     */
    public function shows()
    {
        return $this->hasMany(Show::class, 'schedule_id');
    }

    /**
     * Creator (Staff/User)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}