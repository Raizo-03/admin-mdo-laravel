<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable; // 

    protected $table = 'Admins'; // Explicitly set the table name
    protected $primaryKey = 'admin_id';
    public $timestamps = false; // <-- Add this to disable timestamps

    protected $fillable = ['username','name', 'email', 'password', 'profile_picture', 'role'];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
