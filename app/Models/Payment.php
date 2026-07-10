<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

protected $fillable=[

    'booking_id',
    'amount',
    'currency',
    'payment_method',
    'transaction_id',
    'gateway_response',
    'status',
    'paid_at'

];


protected $casts=[

    'gateway_response'=>'array',
    'paid_at'=>'datetime'

];


public function booking()
{
    return $this->belongsTo(Booking::class);
}


public function refunds()
{
    return $this->hasMany(Refund::class);
}

}
