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

    Log::info('Received profile picture update request for admin ID: ' . $admin->id);

    // Validate the file input
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $uploadedFile = $request->file('profile_picture');

    if (!$uploadedFile->isValid()) {
        Log::error('Invalid file upload attempt.');
        return back()->withErrors(['profile_picture' => 'Uploaded file is not valid.']);
    }

    try {
        // Delete old profile picture if it exists (Cloudinary only)
        if (!empty($admin->profile_picture)) {
            $publicId = pathinfo(parse_url($admin->profile_picture, PHP_URL_PATH), PATHINFO_FILENAME);
            Cloudinary::destroy('profile_pictures/' . $publicId);
            Log::info('Old profile picture deleted: ' . $admin->profile_picture);
        }

        // Upload new profile picture to Cloudinary
        $uploadedFileUrl = Cloudinary::upload($uploadedFile->getRealPath(), [
            'folder' => 'profile_pictures', // Store images in this folder on Cloudinary
        ])->getSecurePath();

        // Update the profile picture in the database
        $admin->profile_picture = $uploadedFileUrl;
        $admin->save();

        Log::info('New profile picture uploaded successfully: ' . $uploadedFileUrl);

        return redirect()->route('admin.profile')->with('success', 'Profile picture updated successfully.');
    } catch (\Exception $e) {
        Log::error('Cloudinary upload failed: ' . $e->getMessage());

        return back()->withErrors(['profile_picture' => 'Failed to upload profile picture. Please try again.']);
    }
}

}
