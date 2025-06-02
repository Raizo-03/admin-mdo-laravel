<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin; // Adjust to your admin model
use Carbon\Carbon;

class NurseExportController extends Controller
{
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            
            // Get ONLY admins with 'admin' role - this is now enforced
            $query = Admin::where('role', 'nurse');
            
            // Remove the role filter since we only want admins
            // The role filter has been removed to ensure only 'admin' role is exported
            
            // Filter by status (active, archived) - this can still be applied
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            
            // Search functionality - only within admin role
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('username', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('name', 'like', '%' . $search . '%')
                      ->orWhere('title', 'like', '%' . $search . '%');
                });
            }
            
            $admins = $query->orderBy('created_at', 'desc')->get();
            
            if ($admins->isEmpty()) {
                return response()->json(['error' => 'No admin users found to export'], 404);
            }
            
            if ($format === 'excel') {
                return $this->exportExcel($admins);
            } else {
                return $this->exportCsv($admins);
            }
            
        } catch (\Exception $e) {
            \Log::error('Admin export error: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }
    
    private function exportCsv($admins)
    {
        $filename = 'admin_users_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($admins) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Admin ID',
                'Username',
                'Email',
                'Name',
                'Title',
                'Role',
                'Status',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($admins as $admin) {
                fputcsv($file, [
                    $admin->admin_id,
                    $admin->username,
                    $admin->email,
                    $admin->name ?? 'N/A',
                    $admin->title ?? 'N/A',
                    ucfirst($admin->role), // This will always be 'Admin' now
                    ucfirst($admin->status),
                    $admin->created_at ? Carbon::parse($admin->created_at)->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportExcel($admins)
    {
        $filename = 'admin_users_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($admins) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add CSV headers
            fputcsv($file, [
                'Admin ID',
                'Username',
                'Email',
                'Name',
                'Title',
                'Role',
                'Status',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($admins as $admin) {
                fputcsv($file, [
                    $admin->admin_id,
                    $admin->username,
                    $admin->email,
                    $admin->name ?? 'N/A',
                    $admin->title ?? 'N/A',
                    ucfirst($admin->role), // This will always be 'Admin' now
                    ucfirst($admin->status),
                    $admin->created_at ? Carbon::parse($admin->created_at)->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}