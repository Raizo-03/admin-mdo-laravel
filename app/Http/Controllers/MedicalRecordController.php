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
            'booking_id' => 'required|exists:Bookings,booking_id',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
    
        $existingRecord = MedicalRecord::where('booking_id', $request->booking_id)->first();
    
        if ($existingRecord) {
            return $this->update($request, $request->booking_id);
        }
    
        $admin = Auth::guard('admin')->user();
    
        MedicalRecord::create([
            'booking_id' => $request->booking_id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'doctor_id' => $admin->admin_id,
            'doctor' => $admin->name,
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
    
        $medicalRecord = MedicalRecord::where('booking_id', $booking_id)->firstOrFail();
        $admin = Auth::guard('admin')->user();
    
        $medicalRecord->update([
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'doctor_id' => $admin->admin_id,
            'doctor' => $admin->name,
            'notes' => $request->notes,
        ]);
    
        return redirect()->back()->with('success', 'Medical record updated successfully!');
    }
    
}