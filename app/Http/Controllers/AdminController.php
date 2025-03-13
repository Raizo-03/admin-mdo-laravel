<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function countUsers()
    {
        $totalAdmin = Admin::count(); // Get total number of users
        return view('dashboard.index', compact('totalAdmin')); // Pass to view
    }
}
