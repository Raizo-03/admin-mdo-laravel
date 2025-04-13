<?php

namespace App\Http\Controllers;

use App\Models\VitalSigns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;


class VitalSignsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:Bookings,booking_id',
            'height_cm' => 'required|numeric',
            'weight_kg' => 'required|numeric',
            'blood_pressure' => 'required|string|max:20',
            'temperature_c' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        // Check if a record already exists for the given booking_id
        $existingVitalSign = VitalSigns::where('booking_id', $request->booking_id)->first();

        if ($existingVitalSign) {
            // If a record exists, update it
            return $this->update($request, $request->booking_id);
        }

        // If no existing record, create a new one
        VitalSigns::create([
            'booking_id' => $request->booking_id,
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
            'blood_pressure' => $request->blood_pressure,
            'temperature_c' => $request->temperature_c,
            'attending_nurse' => Auth::guard('admin')->user()->name,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Vital signs saved successfully!');
    }

    public function update(Request $request, $booking_id)
    {
        $request->validate([
            'height_cm' => 'required|numeric',
            'weight_kg' => 'required|numeric',
            'blood_pressure' => 'required|string|max:20',
            'temperature_c' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        // Find the existing record by booking_id
        $vitalSigns = VitalSigns::where('booking_id', $booking_id)->firstOrFail();

        // Update the record with the new data
        $vitalSigns->update([
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
            'blood_pressure' => $request->blood_pressure,
            'temperature_c' => $request->temperature_c,
            'attending_nurse' => Auth::guard('admin')->user()->name,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Vital signs updated successfully!');
    }


    public function show($bookingId)
    {
        // This should be loading the Booking model, not VitalSigns
        $appointment = Appointment::findOrFail($bookingId);
        
        return view('appointments.show', compact('appointment'));
    }

}
