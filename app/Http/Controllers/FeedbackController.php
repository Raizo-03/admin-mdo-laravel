<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;


class FeedbackController extends Controller
{
    public function countFeedbacks()
    {
        $totalFeedback = Feedback::count(); // Get total number of users
        return view('dashboard.index', compact('totalFeedback')); // Pass to view
    }

    public function index()
    {
        $feedback = Feedback::all(); // Fetch all trivia entries from the database
        return view('dashboard.contentmanagement.feedback.index', compact('feedback')); // Pass to view
    }

    public function create()
    {
        return view('dashboard.contentmanagement.feedback.index');
    }

}
