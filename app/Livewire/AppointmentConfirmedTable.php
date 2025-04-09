<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;

class AppointmentConfirmedTable extends Component
{
    use WithPagination;

    public $search = '';


    // Reset pagination when search is updated
    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        // Fetch confirmed appointments with search functionality
        $appointments = Appointment::where('status', 'Approved') // Only approved appointments
            ->where(function ($query) {
                // Search by service, service_type, booking_date, or remarks
                $query->where('umak_email', 'like', '%' . $this->search . '%')
                      ->orWhere('service', 'like', '%' . $this->search . '%')
                      ->orWhere('service_type', 'like', '%' . $this->search . '%')
                      ->orWhere('booking_date', 'like', '%' . $this->search . '%')
                      ->orWhere('remarks', 'like', '%' . $this->search . '%');
            })
            ->orderBy('booking_date', 'desc') // Order by booking_date
            ->paginate(10); // Pagination of 10 appointments per page

        return view('livewire.appointment-confirmed-table', compact('appointments'));
    }
}
