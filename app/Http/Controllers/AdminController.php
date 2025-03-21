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
   // Validate the uploaded image
   $request->validate([
    'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
]);

try {
    // Upload the image to Cloudinary
    $uploadedFile = Cloudinary::upload($request->file('profile_picture')->getRealPath(), [
        'folder' => 'profile_pictures'
    ]);

    // Retrieve secure URL and public ID
    $uploadedFileUrl = $uploadedFile->getSecurePath();
    $publicId = $uploadedFile->getPublicId();

    // Check if Cloudinary returned a valid URL
    if (!$uploadedFileUrl) {
        return back()->with('error', 'Failed to upload image to Cloudinary.');
    }

    // Update the authenticated user's profile picture in the database
    $admin = auth()->user();
    if (!$admin) {
        return back()->with('error', 'User not authenticated.');
    }

    $admin->profile_picture = $uploadedFileUrl;
    $admin->cloudinary_public_id = $publicId; // Store public ID for future reference
    $admin->save();

    return back()->with('success', 'Profile picture updated successfully!');
} catch (\Exception $e) {
    return back()->with('error', 'Cloudinary upload failed: ' . $e->getMessage());
}
}
}