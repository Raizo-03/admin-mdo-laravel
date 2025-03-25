<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Feedback extends Model
{
    use HasFactory;
    protected $table = 'Feedback'; // Explicitly define table name (optional if it matches Laravel convention)

    protected $fillable = ['service_type', 'rating', 'message', 'created_at']; // Add this line

}
