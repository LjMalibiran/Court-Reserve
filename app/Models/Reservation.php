<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_code',  // Crucial for the QR scanner
        'user_id',
        'walk_in_name',      // For unregistered walk-in customers
        'court_id',
        'start_time',
        'end_time',
        'total_price',
        'amount_paid',
        'payment_reference', // For GCash tracking
        'status',
    ];

    // Casts the database timestamps into Carbon date objects for easy math
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // A reservation belongs to one specific user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A reservation belongs to one specific court
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    // A reservation can have multiple payment records (e.g., Down payment + Balance)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // A reservation can have multiple rented items (Rackets, shuttlecocks)
    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }
}