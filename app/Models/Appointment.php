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
    
    protected $fillable = [
        'umak_email',
        'service',
        'service_type',
        'booking_date',
        'booking_time',
        'remarks',
        'status',
    ];
    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime',
        'created_at' => 'datetime',
    ];
    
    public function user()
{
    return $this->belongsTo(User::class, 'umak_email', 'umak_email');
}

public function vitalSigns()
{
    return $this->hasOne(VitalSigns::class, 'booking_id', 'booking_id');
}
public function medicalRecord()
{
    return $this->hasOne(MedicalRecord::class, 'booking_id', 'booking_id');
}


}
