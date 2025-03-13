<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function countAdmins()
    {
        $totalUsers = User::count(); // Get total number of users
        return view('dashboard.index', compact('totalUsers')); // Pass to view
    }


    public function getMonthlyUserRegistrations(Request $request)
    {
        $months = $request->input('months', 12); // Default to last 12 months
        $startDate = Carbon::now()->subMonths($months)->startOfMonth();
    
        $monthlyRegistrations = User::where('status', 'active')
            ->where('created_at', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();
    
        if ($monthlyRegistrations->isEmpty()) {
            return response()->json(['labels' => [], 'data' => [], 'message' => 'No data found'], 200);
        }
    
        $labels = $monthlyRegistrations->pluck('month')->map(fn($date) => Carbon::createFromFormat('Y-m', $date)->format('M Y'));
        $data = $monthlyRegistrations->pluck('count');
    
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    
    
    
    
}
