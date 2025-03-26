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

    public function getChartData(Request $request)
    {
        $months = $request->query('months', 12);
    
        $data = Feedback::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, rating, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month', 'rating')
            ->orderBy('month', 'ASC')
            ->get();
    
        // Format data to group ratings per month
        $formattedData = [];
    
        foreach ($data as $item) {
            $month = $item->month;
            $rating = $item->rating;
            $total = $item->total;
    
            if (!isset($formattedData[$month])) {
                $formattedData[$month] = ['month' => $month, 'ratings' => array_fill(1, 5, 0)];
            }
    
            $formattedData[$month]['ratings'][$rating] = $total;
        }
    
        return response()->json(array_values($formattedData));
    }
    
    


}
