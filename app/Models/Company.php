<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'logo_url',
        'pan_vat_no',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // One company has many cinemas
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // One company has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
