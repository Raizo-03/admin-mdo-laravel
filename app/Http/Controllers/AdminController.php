<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class AdminController extends Controller
{
    public function countAdmins()
    {
        $totalAdmin = Admin::count(); // Get total number of users
        return view('dashboard.index', compact('totalAdmin')); // Pass to view
    }

    public function admins(){
        $admins = Admin::all(); // Get all users (since they are all students)
        return view('dashboard.users.admins.index', compact('admins'));
    }
    public function show($id)
    {
        $adminProfile = Admin::where('admin_id', $id)
            ->select('admin_id', 'username', 'email')
            ->first();
    
        // Debugging: Show fetched data
    
        if (!$adminProfile) {
            return response()->json([
                'admin_id' => 'Not found',
                'username' => 'Not foundt',
                'email' => 'Not found',
            ]);

        }
    
        return response()->json($adminProfile, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function updateAdmin(Request $request, $admin_id)
{
    $admin = Admin::findOrFail($admin_id);

    $admin->username = $request->username;
    $admin->email = $request->email;

    // Only update password if it's provided
    if (!empty($request->password)) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    return response()->json(['success' => true, 'message' => 'Admin updated successfully']);
}

public function profile()
{
    $admin = Auth::guard('admin')->user(); // Get the currently logged-in admin
    return view('dashboard.users.admins.profile', compact('admin'));
}

public function updateProfilePicture(Request $request) {
    $admin = Auth::guard('admin')->user(); // Get the logged-in admin

    if (!$request->hasFile('profile_picture')) {
        return back()->withErrors(['profile_picture' => 'No file uploaded.']);
    }

    $uploadedFile = $request->file('profile_picture');

    $uploadedFileUrl = Cloudinary::upload($request->file('profile_picture')->getRealPath())->getSecurePath();
    $admin->profile_picture = $uploadedFileUrl; // Store full Cloudinary URL
    $admin->save();


    return redirect()->route('admin.profile')->with('success', 'Profile picture updated successfully');
}


}
