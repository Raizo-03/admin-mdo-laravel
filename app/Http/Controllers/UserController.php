<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function countUsers()
    {
        $totalUsers = User::count(); // Get total number of users
        return view('dashboard.index', compact('totalUsers')); // Pass to view
    }

    public function students()
    {
    $students = User::all(); // Get all users (since they are all students)
    return view('dashboard.users.students.index', compact('students'));
    }


    
    
    public function updateStatus(Request $request) {
        \Log::info($request->all()); // Debugging log

        $user = User::where('student_id', $request->student_id)->first();

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        // Store "active" or "inactive" as a string
        $user->status = ($request->status === "active") ? "active" : "inactive";
        
        $user->save();

        return back()->with('success', 'Status updated successfully');
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
    
    
    public function show($id)
    {
        // Fetch the student from the User table
        $student = User::findOrFail($id);
        
        // Fetch the user profile associated with the student
        $profile = $student->profile; 
        
        // Fetch the medical info associated with the student
        $medical = $student->medicalInfo; 
    
        // If no medical info exists, create a placeholder object
        if (!$medical) {
            $medical = (object)[
                'sex' => 'Not yet set',
                'blood_type' => 'Not yet set',
                'allergies' => 'Not yet set',
                'medical_conditions' => 'Not yet set',
                'medications' => 'Not yet set'
            ];
        }
    
        return view('dashboard.users.students.show', compact('student', 'profile', 'medical'));
    }
    public function update(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:Users,user_id', // Changed from id to user_id
        'student_id' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
    ]);
    
    $student = User::where('user_id', $validated['user_id'])->firstOrFail(); // Changed from id to user_id
    
    $student->student_id = $validated['student_id'];
    $student->first_name = $validated['first_name'];
    $student->last_name = $validated['last_name'];
    $student->save();
    
    return redirect()->route('students.show', $student->user_id)
    ->with('success', 'Student information updated successfully');

}
    
}
