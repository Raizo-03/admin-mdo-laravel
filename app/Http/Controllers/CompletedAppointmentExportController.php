<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment; // Adjust to your appointment model
use Carbon\Carbon;

class CompletedAppointmentExportController extends Controller
{
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        // Get only confirmed appointments
        $appointments = Appointment::where('status', 'Completed')
                                 ->orderBy('booking_date', 'desc')
                                 ->orderBy('booking_time', 'desc')
                                 ->get();
        
        if ($format === 'excel') {
            return $this->exportExcel($appointments);
        } else {
            return $this->exportCsv($appointments);
        }
    }
    
    private function exportCsv($appointments)
    {
        $filename = 'appointments_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Email',
                'Service',
                'Service Type',
                'Date',
                'Time',
                'Remarks',
                'Status',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->booking_id,
                    $appointment->umak_email,
                    $appointment->service,
                    $appointment->service_type,
                    Carbon::parse($appointment->booking_date)->toFormattedDateString(),
                    Carbon::parse($appointment->booking_time)->format('g:i A'),
                    $appointment->remarks,
                    $appointment->status ?? 'Confirmed', // Adjust field name as needed
                    $appointment->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportExcel($appointments)
    {
        // Create CSV file with Excel-friendly formatting
        $filename = 'appointments_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Email',
                'Service',
                'Service Type',
                'Date',
                'Time',
                'Remarks',
                'Status',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->booking_id,
                    $appointment->umak_email,
                    $appointment->service,
                    $appointment->service_type,
                    Carbon::parse($appointment->booking_date)->toFormattedDateString(),
                    Carbon::parse($appointment->booking_time)->format('g:i A'),
                    $appointment->remarks,
                    $appointment->status ?? 'Confirmed',
                    $appointment->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}