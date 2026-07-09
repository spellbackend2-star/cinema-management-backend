<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'screen_id',
        'category_id',
        'row_label',
        'seat_number',
        'seat_label',
        'seat_type',
        'pos_x',
        'pos_y',
        'rotation',
        'width',
        'height',
        'is_active',
    ];

    protected $casts = [
        'pos_x' => 'integer',
        'pos_y' => 'integer',
        'rotation' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Seat belongs to a Screen.
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Seat belongs to a Seat Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SeatCategory::class, 'category_id');
    }
}