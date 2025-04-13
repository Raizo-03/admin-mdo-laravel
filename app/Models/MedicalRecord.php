<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';
    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'booking_id',
        'diagnosis',
        'prescription',
        'doctor',
        'notes',
    ];

    public $timestamps = false;

    public function booking()
    {
        return $this->belongsTo(Appointment::class, 'booking_id', 'booking_id');
    }
}