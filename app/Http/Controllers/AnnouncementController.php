<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $imageUrl = null;
            if ($request->hasFile('image')) {
                // Upload to Cloudinary
                $uploadResult = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'announcements'
                ]);
                $imageUrl = $uploadResult->getSecurePath();
            }

            // Create Announcement
            $announcement = new Announcement();
            $announcement->title = $request->title;
            $announcement->details = $request->details;
            $announcement->image_url = $imageUrl;
            $announcement->save();

            return response()->json(['success' => true, 'message' => 'Announcement created successfully!']);
        } catch (\Exception $e) {
            Log::error('Error storing announcement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create announcement.'], 500);
        }
    }

    


}
