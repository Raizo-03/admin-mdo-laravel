<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment; // Import the Appointment model
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function countAppointments()
    {
        $totalAppointments = Appointment::count(); // Count total appointments
        return view('dashboard.index', compact('totalAppointments'));
    }

    public function getAppointmentsData(Request $request)
    {
        $months = $request->input('months', 12); // Get filter value (default: 12 months)

        // Define service types for Medical and Dental
        $medicalServices = [
            'General Consultation', 'Hhealth Screening', 'Vaccination Services', 'Referrals to Specialists',
            'Health Education', 'Physical Examinations', 'Treatment for Minor Illnesses',
            'Laboratory Services', 'Medical Certificate for OJT'
        ];

        $dentalServices = [
            'Dental Consultation', 'Tooth Extraction', 'Teeth Cleaning', 'Dental Fillings',
            'Dental Health Education', 'Emergency Dental Care', 'Referrals to Dental Specialists'
        ];

        // Fetch Medical data
        $medicalAppointments = Appointment::select('service_type', DB::raw('COUNT(*) as total'))
            ->where('service', 'Medical')
            ->where('booking_date', '>=', Carbon::now()->subMonths($months))
            ->whereIn('service_type', $medicalServices)
            ->groupBy('service_type')
            ->pluck('total', 'service_type');

        // Fetch Dental data
        $dentalAppointments = Appointment::select('service_type', DB::raw('COUNT(*) as total'))
            ->where('service', 'Dental')
            ->where('booking_date', '>=', Carbon::now()->subMonths($months))
            ->whereIn('service_type', $dentalServices)
            ->groupBy('service_type')
            ->pluck('total', 'service_type');

        // Combine labels for both Medical and Dental
        $labels = array_merge($medicalServices, $dentalServices);

        // Format data for Chart.js
        $medicalData = [];
        $dentalData = [];

        foreach ($labels as $type) {
            $medicalData[] = $medicalAppointments[$type] ?? 0;
            $dentalData[] = $dentalAppointments[$type] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'medicalData' => $medicalData,
            'dentalData' => $dentalData
        ]);
    }
}
