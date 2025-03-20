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
    public function update(Request $request, $id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();

        $request->validate([
            'contact_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'guardian_contact_number' => 'nullable|string|max:15',
            'guardian_address' => 'nullable|string|max:255',
        ]);

        $profile->update([
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'guardian_contact_number' => $request->guardian_contact_number,
            'guardian_address' => $request->guardian_address,
        ]);

        return redirect()->back()->with('success', 'User profile updated successfully.');
    }
    
}
