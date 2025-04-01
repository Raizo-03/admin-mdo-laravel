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
    public function doctors()
    {
        // Get all doctors
        $doctors = Admin::where('role', 'doctor')->get();  // Get only users with the 'doctor' role
        return view('dashboard.users.doctors.index', compact('doctors'));  // Return the view with doctors data
    }
    
    public function nurses()
    {
        // Get all doctors
        $nurses = Admin::where('role', 'nurse')->get();  // Get only users with the 'doctor' role
        return view('dashboard.users.nurses.index', compact('nurses'));  // Return the view with doctors data
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



public function updateProfilePicture(Request $request)
{
    // Validate the uploaded image
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        // Check if user is authenticated
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('login')->with('error', 'You must be logged in to update your profile picture.');
        }

        // Check if the file is uploaded
        $file = $request->file('profile_picture');
        if ($file && $file->isValid()) {
            // Use the Cloudinary URL from .env directly
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            
            // Upload the file using the uploadApi with folder specification
            $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'resource_type' => 'auto',
                'folder' => 'profile_pictures'  // Specify the folder here
            ]);
            
            // Extract the URL from the result
            $uploadedFileUrl = $uploadResult['secure_url'];
            
            // Only save the profile_picture URL to the database
            $admin->profile_picture = $uploadedFileUrl;
            $admin->save();

            return back()->with('success', 'Profile picture updated successfully!');
        } else {
            return back()->with('error', 'No valid file uploaded.');
        }
    } catch (\Exception $e) {
        // Log detailed error information
        Log::error('Cloudinary upload error: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        return back()->with('error', 'Cloudinary upload failed: ' . $e->getMessage());
    }
}
}