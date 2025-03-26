<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class AnnouncementController extends Controller
{
    public function countAnnouncements()
    {
        $totalAnnouncement = Announcement::count(); // Get total number of users
        return view('dashboard.index', compact('totalAnnouncement')); // Pass to view
    }


    public function index()
    {
        $announcement = Announcement::all(); // Fetch all trivia entries from the database
        return view('dashboard.contentmanagement.announcement.index', compact('announcement'));
    }

    public function create()
    {
        return view('dashboard.contentmanagement.announcement.index');
    }


    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $image_url = null;
            $admin = auth('admin')->user();

            if (!$admin) {
                return back()->with('error', 'You must be logged in to create announcements.');
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Initialize Cloudinary
                $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

                // Upload the image
                $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'resource_type' => 'auto',
                    'folder' => 'announcements'
                ]);

                // Get the secure image URL
                $image_url = $uploadResult['secure_url'] ?? null;
            }

            // Save the announcement in the database
            $announcement = Announcement::create([
                'title' => $validatedData['title'],
                'details' => $validatedData['details'],
                'image_url' => $image_url,
                'status' => 'review',
            ]);

            if ($announcement) {
                return back()->with('success', 'Announcement saved successfully!');
            } else {
                return back()->with('error', 'Failed to save announcement.');
            }
        } catch (\Exception $e) {
            Log::error('Error saving announcement: ' . $e->getMessage());
            return back()->with('error', 'Error uploading image: ' . $e->getMessage());
        }
    }

    


}
