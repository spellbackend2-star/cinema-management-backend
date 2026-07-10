<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Booking extends Model
{
    use HasFactory;


    protected $fillable = [

        'booking_reference',
        'user_id',
        'booked_by_user_id',
        'show_id',
        'coupon_id',

        'subtotal',
        'tax_amount',
        'discount_amount',
        'convenience_fee',
        'total_amount',

        'status',
        'payment_status',
        'booking_source',

        'expires_at',
        'confirmed_at',
        'cancelled_at',

    ];


    protected $casts = [

        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',

    ];



    // Customer
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    // Staff/Admin who created booking
    public function bookedBy()
    {
        return $this->belongsTo(
            User::class,
            'booked_by_user_id'
        );
    }

    public function bookingSeats()
    {
        return $this->hasMany(
            BookingSeat::class
        );
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }







    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }



    public function payment()
    {
        return $this->hasOne(Payment::class);
    }



    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
    public function coupon()
    {
        return $this->belongsTo(
            Coupon::class
        );
    }


    public function loyaltyTransactions()
    {
        return $this->hasMany(
            LoyaltyTransaction::class
        );
    }
}
