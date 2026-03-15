<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pitch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_hour',
        'capacity',
        'description',
        'image',
        'is_active',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function customPrices()
    {
        return $this->hasMany(PitchPrice::class);
    }
}
