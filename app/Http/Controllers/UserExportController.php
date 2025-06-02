<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Adjust to your student/user model
use Carbon\Carbon;

class UserExportController extends Controller
{
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            
            // Get students with filters applied (similar to your current filtering logic)
            $query = User::query();
            
            // Apply filters if they exist in the request
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            
            if ($request->has('role') && $request->role !== 'all') {
                $query->where('role', $request->role);
            }
            
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('student_id', 'like', '%' . $search . '%')
                      ->orWhere('umak_email', 'like', '%' . $search . '%');
                });
            }
            
            $students = $query->with('profile') // Load profile relationship if needed
                             ->orderBy('created_at', 'desc')
                             ->get();
            
            if ($students->isEmpty()) {
                return response()->json(['error' => 'No students found to export'], 404);
            }
            
            if ($format === 'excel') {
                return $this->exportExcel($students);
            } else {
                return $this->exportCsv($students);
            }
            
        } catch (\Exception $e) {
            \Log::error('Student export error: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }
    
    private function exportCsv($students)
    {
        $filename = 'students_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'UMAK ID',
                'First Name',
                'Last Name',
                'UMAK Email',
                'Status',
                'Role',
                'Verified',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_id,
                    $student->first_name,
                    $student->last_name,
                    $student->umak_email,
                    ucfirst($student->status),
                    ucfirst($student->role),
                    $student->verified ? 'Yes' : 'No',
                    $student->created_at ? Carbon::parse($student->created_at)->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportExcel($students)
    {
        $filename = 'students_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add CSV headers
            fputcsv($file, [
                'UMAK ID',
                'First Name',
                'Last Name',
                'Email',
                'Status',
                'Role',
                'Verified',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_id,
                    $student->first_name,
                    $student->last_name,
                    $student->umak_email,
                    ucfirst($student->status),
                    ucfirst($student->role),
                    $student->verified ? 'Yes' : 'No',
                    $student->created_at ? Carbon::parse($student->created_at)->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}