<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'company_id',
        'cinema_id',
        'employee_code',
        'name',
        'email',
        'phone',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
    public function loyaltyAccount()
    {
        return $this->hasOne(
            LoyaltyAccount::class
        );
    }


    public function bookings()
    {
        return $this->hasMany(
            Booking::class
        );
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
