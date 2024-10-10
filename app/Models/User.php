<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use Laravel\Sanctum\HasApiTokens;


class User extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    const ROLE_ADMIN = 0;
    const ROLE_USER = 1;

    public function isAdmin () {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser () {
        return $this->role === self::ROLE_USER;
    }

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

}
