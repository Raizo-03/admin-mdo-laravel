<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DoctorAppointments extends Component
{
    use WithPagination;  // For pagination

    public $search = '';  // For search functionality

    public function render()
    {
        $admin = Auth::guard('admin')->user();  // Get the logged-in admin

        // Fetch appointments related to medical records, filtering by the admin's doctor_id and status 'Completed'
        $appointments = Appointment::whereHas('medicalRecord', function ($query) use ($admin) {
            $query->where('doctor_id', $admin->admin_id);  // Use doctor_id instead of admin_id
        })
        ->where('status', 'Completed')  // Filter by status 'Completed'
        ->where(function ($query) {  // Use where function for search criteria
            $query->where('umak_email', 'like', '%' . $this->search . '%')  // Search by email
                  ->orWhere('service', 'like', '%' . $this->search . '%');  // Search by service
        })
        ->paginate(10);  // Pagination, 10 per page

        return view('livewire.doctor-appointments', [
            'appointments' => $appointments,
        ]);
    }
}
