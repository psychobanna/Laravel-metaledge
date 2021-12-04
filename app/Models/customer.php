<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class customer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "name",
        "username",
        "password",
        "email",
        "address",
        "city",
        "pin",
        "contact",
        "status"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
}
