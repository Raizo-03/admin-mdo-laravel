<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'UserProfile'; // Consider renaming to lowercase snake_case: 'user_profiles'
    protected $primaryKey = 'user_id';
    public $timestamps = false; // Disable timestamps if not used

    // Prevent mass assignment issues
    protected $fillable = ['user_id', 'contact_number', 'address', 'guardian_contact_number', 'guardian_address'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // Ensure 'user_id' is the correct foreign key
    }
}
