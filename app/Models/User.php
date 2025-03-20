<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    public $timestamps = false; // Disable automatic timestamps

    protected $table = 'Users'; // Explicitly define table name (optional if it matches Laravel convention)
    protected $primaryKey = 'user_id'; // Set the correct primary key

    protected $fillable = ['student_id', 'username', 'password', 'status']; // Ensure student_id and status are fillable

    // Tell Laravel to use "username" instead of "email" for authentication
    public function getAuthIdentifierName()
    {
        return 'username';
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'user_id');
    }
}
