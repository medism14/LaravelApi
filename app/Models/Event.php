<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reservation;


class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'address',
        'capacity',
        'remainingPlaces',
        'category_id',
    ];
    
    public function category() {
        return $this->belongsTo(User::class);
    }

    public function reservations () {
        return $this->hasMany(Reservation::class);
    }
}
