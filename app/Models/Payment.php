<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'amount',
        'payment_method',   // e.g., 'GCash', 'Cash'
        'reference_number', // For GCash transactions
        'payment_type',     // e.g., 'Down Payment', 'Balance', 'Full'
        'status',           // e.g., 'Pending', 'Completed', 'Refunded'
    ];

    // A payment belongs to one specific reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}