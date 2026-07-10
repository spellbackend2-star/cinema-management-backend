<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTransaction extends Model
{


protected $fillable=[

    'loyalty_account_id',
    'booking_id',
    'points',
    'reason'

];



public function loyaltyAccount()
{
    return $this->belongsTo(
        LoyaltyAccount::class
    );
}



public function booking()
{
    return $this->belongsTo(
        Booking::class
    );
}
}

