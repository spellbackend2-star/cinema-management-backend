<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;




class LoyaltyAccount extends Model
{

    public $timestamps = false;
    protected $fillable = [

        'user_id',
        'points_balance',
        'tier'

    ];
 protected $casts = [
        'points_balance' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function transactions()
    {
        return $this->hasMany(
            LoyaltyTransaction::class
        );
    }
}
