<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;

class AppointmentConfirmedTable extends Component
{
    use WithPagination;

    public $search = '';
    public $approvedAppointmentsCount;
    
    // Modal related properties
    public $showStatusModal = false;
    public $selectedAppointment = null;
    public $newStatus = '';

    // Reset pagination when search is updated
    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    // Automatically count the approved appointments in the mount method
    public function mount()
    {
        // Get the count of approved appointments
        $this->approvedAppointmentsCount = Appointment::where('status', 'Approved')->count();
    }

    // Open the status change modal
    public function openStatusModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::find($appointmentId);
        $this->newStatus = 'Completed'; // Default selection
        $this->showStatusModal = true;
    }

    // Close the modal
    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->selectedAppointment = null;
        $this->newStatus = '';
    }

    // Update the appointment status
    public function updateStatus()
    {
        if ($this->selectedAppointment && in_array($this->newStatus, ['Completed', 'No Show'])) {
            $this->selectedAppointment->status = $this->newStatus;
            $this->selectedAppointment->save();
            
            // Update the counter after changing status
            $this->approvedAppointmentsCount = Appointment::where('status', 'Approved')->count();
            
            // Close the modal
            $this->closeStatusModal();
            
            // Using SweetAlert instead of session flash message
            $this->dispatch('showAlert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Appointment status updated successfully!'
            ]);
        }
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
    
        // Pass the $appointments variable to the view
        return view('livewire.appointment-confirmed-table', [
            'appointments' => $appointments
        ]);
    }

    public $showDeleteModal = false;

        public function openDeleteModal($id)
        {
            $this->selectedAppointment = Appointment::find($id);
            $this->showDeleteModal = true;
        }

        public function closeDeleteModal()
        {
            $this->showDeleteModal = false;
            $this->selectedAppointment = null;
        }

        public function deleteAppointment()
        {
            if ($this->selectedAppointment) {
                // Delete the appointment
                $this->selectedAppointment->delete();
                
                // Show SweetAlert with JavaScript
                $this->dispatch('showAlert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Deleted successfully!'
                ]);
                
                $this->closeDeleteModal();
            }
        }
}