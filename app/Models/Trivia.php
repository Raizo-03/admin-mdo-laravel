<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Trivia extends Model
{
    use HasFactory;
    public $timestamps = false; // Add this line

    protected $table = 'Trivia'; // Explicitly define table name (optional if it matches Laravel convention)
    protected $fillable = ['question', 'answer'];


}
