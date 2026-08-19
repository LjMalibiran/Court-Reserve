<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'is_active',
    ];

    // A court can have many reservations over time
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}