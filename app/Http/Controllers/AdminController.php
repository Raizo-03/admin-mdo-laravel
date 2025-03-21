<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

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

    // Debugging: Check if a file is received
    if (!$request->hasFile('profile_picture')) {
        Log::error('No file uploaded.');
        return back()->withErrors(['profile_picture' => 'No file uploaded.']);
    }

    $uploadedFile = $request->file('profile_picture');

    // Debugging: Check file details
    Log::info('Uploaded file details: ', $uploadedFile->toArray());

    // Ensure the uploaded file is valid
    if (!$uploadedFile->isValid()) {
        Log::error('Uploaded file is not valid.');
        return back()->withErrors(['profile_picture' => 'Uploaded file is not valid.']);
    }

    try {
        // Upload to Cloudinary
        $uploadedFileUrl = Cloudinary::upload($uploadedFile->getRealPath(), [
            'folder' => 'profile_pictures' // Optional: Store in a specific folder
        ])->getSecurePath();

        // Update the admin profile picture in the database
        $admin->profile_picture = $uploadedFileUrl;
        $admin->save();

        Log::info('Profile picture uploaded successfully: ' . $uploadedFileUrl);

        return redirect()->route('admin.profile')->with('success', 'Profile picture updated successfully.');
    } catch (\Exception $e) {
        // Log the error
        Log::error('Cloudinary upload failed: ' . $e->getMessage());

        return back()->withErrors(['profile_picture' => 'Failed to upload profile picture. Please try again.']);
    }
}

}
