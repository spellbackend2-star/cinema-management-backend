<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowSeat extends Model
{
    use HasFactory;

    public const AVAILABLE = 'AVAILABLE';
    public const LOCKED = 'LOCKED';
    public const BOOKED = 'BOOKED';
    public const BLOCKED = 'BLOCKED';

    protected $fillable = [
        'show_id',
        'seat_id',
        'status',
        'locked_by',
        'locked_until',
        'price',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Show
     */
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    /**
     * Seat
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * User holding the seat lock
     */

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }
}
