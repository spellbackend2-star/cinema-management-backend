<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Coupon extends Model
{
    use HasFactory;


    protected $fillable = [

        'company_id',
        'code',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_value',
        'valid_from',
        'valid_until',
        'max_uses',
        'max_uses_per_user',
        'used_count',
        'is_active'

    ];


    protected $casts = [

        'valid_from'=>'datetime',
        'valid_until'=>'datetime',
        'is_active'=>'boolean'

    ];



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


}