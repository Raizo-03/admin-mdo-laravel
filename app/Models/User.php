<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['username', 'password'];

    // Tell Laravel to use "username" instead of "email" for authentication
    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
