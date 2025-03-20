<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function show($id)
    {
        $userProfile = UserProfile::where('user_id', $id)
            ->select('user_id', 'contact_number', 'address', 'guardian_contact_number', 'guardian_address')
            ->first();
    
        // Debugging: Show fetched data
    
        if (!$userProfile) {
            return response()->json([
                'contact_number' => 'Not yet set',
                'address' => 'Not yet set',
                'guardian_contact_number' => 'Not yet set',
                'guardian_address' => 'Not yet set'
            ]);
    
        }
    
        return response()->json($userProfile, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    
}
