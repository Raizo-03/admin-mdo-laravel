<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalInfo extends Model
{  use HasFactory;

    protected $table = 'medical_info'; // Specify the correct table name

    
    protected $fillable = [
        'user_id',
        'sex',
        'blood_type',
        'allergies',
        'medical_conditions',
        'medications',
    ];

public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}
}

