<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PitchPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'pitch_id',
        'start_time',
        'end_time',
        'price_per_hour',
    ];

    public function pitch()
    {
        return $this->belongsTo(Pitch::class);
    }
}
