<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'staff';

    protected $fillable = [
        'company_id',
        'cinema_id',
        'employee_code',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}