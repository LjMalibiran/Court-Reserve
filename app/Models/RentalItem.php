<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'item_name',        // e.g., 'Racket', 'Shuttlecock'
        'quantity',
        'price_per_item',
        'total_price',
    ];

    // A rental item belongs to one specific reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}