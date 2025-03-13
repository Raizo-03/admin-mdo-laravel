<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function countAnnouncements()
    {
        $totalAnnouncement = Announcement::count(); // Get total number of users
        return view('dashboard.index', compact('totalAnnouncement')); // Pass to view
    }
}
