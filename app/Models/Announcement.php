<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Announcement extends Model
{
    use HasFactory;
    protected $table = 'Announcements'; // Explicitly define table name (optional if it matches Laravel convention)
    public $timestamps = false; // Add this line


    protected $fillable = ['title', 'details', 'image_url'];



}
