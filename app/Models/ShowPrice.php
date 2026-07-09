<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'category_id',
        'base_price',
        'tax_percent',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'tax_percent' => 'decimal:2',
    ];

    /**
     * Show
     */
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    /**
     * Seat Category
     */
    public function category()
    {
        return $this->belongsTo(SeatCategory::class, 'category_id');
    }

    /**
     * Calculate price including tax.
     */
    public function getTotalPriceAttribute()
    {
        return $this->base_price + (($this->base_price * $this->tax_percent) / 100);
    }
}