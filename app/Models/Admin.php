<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        // store last issued api token (plain text) for debugging/dev purposes
        // NOTE: storing plain tokens in DB is sensitive; consider hashing or removing in production
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
