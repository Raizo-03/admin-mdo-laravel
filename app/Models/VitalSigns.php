<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class VitalSigns extends Model
{
    use HasFactory;

    protected $table = 'vital_signs';
    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'booking_id',
        'height_cm',
        'weight_kg',
        'blood_pressure',
        'temperature_c',
        'attending_nurse',
        'notes',
    ];

    public $timestamps = false;
    
    public function booking()
    {
        return $this->belongsTo(Appointment::class, 'booking_id', 'booking_id');
    }
}