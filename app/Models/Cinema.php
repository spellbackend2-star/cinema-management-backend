<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'address',
        'country',
        'city',
        'area',
        'latitude',
        'longitude',
        'contact_number',
        'email',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];
    public function cinemas()
    {
        return $this->hasMany(Cinema::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
