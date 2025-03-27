<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    use HasFactory;

    protected $table = 'Messages'; // Match your DB table name
    protected $primaryKey = 'message_id'; // Set primary key

    protected $fillable = [
        'sender_email',
        'receiver_email',
        'message',
        'status',
        'timestamp'
    ];

    public $timestamps = false; // Since you're using a `timestamp` column
}
