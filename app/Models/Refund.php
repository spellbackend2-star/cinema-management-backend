<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{

    protected $fillable = [

        'payment_id',
        'amount',
        'reason',
        'status',
        'processed_by_user_id',
        'processed_at'

    ];


    protected $casts = [

        'processed_at' => 'datetime'

    ];


    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }


    public function processedBy()
    {
        return $this->belongsTo(
            User::class,
            'processed_by_user_id'
        );
    }
}
