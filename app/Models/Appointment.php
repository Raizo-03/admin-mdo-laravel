<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'Bookings'; // Explicitly set the table name
    protected $primaryKey = 'booking_id';

    public $timestamps = false; // Disable automatic timestamp management

}
