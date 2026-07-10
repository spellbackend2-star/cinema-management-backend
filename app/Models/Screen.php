<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cinema_id',
        'name',
        'screen_type',
        'capacity',
        'sound_system',
        'is_active',
    ];

    protected $casts = [
        'capacity'  => 'integer',
        'is_active' => 'boolean',
    ];

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
    public function seatCategories()
{
    return $this->hasMany(SeatCategory::class);
}

}
