<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check if a record already exists for the given booking_id
        $existingRecord = MedicalRecord::where('booking_id', $request->booking_id)->first();

        if ($existingRecord) {
            // If a record exists, update it
            return $this->update($request, $request->booking_id);
        }

        // If no existing record, create a new one
        MedicalRecord::create([
            'booking_id' => $request->booking_id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'doctor' => Auth::guard('admin')->user()->name,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Medical record saved successfully!');
    }

    public function update(Request $request, $booking_id)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Find the existing record by booking_id
        $medicalRecord = MedicalRecord::where('booking_id', $booking_id)->firstOrFail();

        // Update the record with the new data
        $medicalRecord->update([
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'doctor' => Auth::guard('admin')->user()->name,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Medical record updated successfully!');
    }
}