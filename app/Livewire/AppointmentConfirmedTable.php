<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;
use Carbon\Carbon;



class AppointmentConfirmedTable extends Component
{
    use WithPagination;

    public $search = '';
    public $approvedAppointmentsCount;
    
    // Modal related properties
    public $showStatusModal = false;
    public $selectedAppointment = null;
    public $newStatus = '';
    public $dateFilter = '';


    public $tempDateFilter = '';
    public $debugInfo = '';


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
        try {
            // Fetch confirmed appointments with search functionality and date filter
            $query = Appointment::where('status', 'Approved'); // Only approved appointments
            
            // Apply search filter
            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('umak_email', 'like', '%' . $this->search . '%')
                      ->orWhere('service', 'like', '%' . $this->search . '%')
                      ->orWhere('service_type', 'like', '%' . $this->search . '%')
                      ->orWhere('booking_date', 'like', '%' . $this->search . '%')
                      ->orWhere('remarks', 'like', '%' . $this->search . '%');
                });
            }
            
            // Apply date filter using Carbon
            if ($this->dateFilter) {
                try {
                    $filterDate = Carbon::parse($this->dateFilter)->format('Y-m-d');
                    $query->whereDate('booking_date', $filterDate);
                } catch (\Exception $e) {
                }
            }
            $appointments = $query->orderBy('booking_date', 'desc')
                                ->paginate(10);
            
            return view('livewire.appointment-confirmed-table', [
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            $this->debugInfo = "Render error: " . $e->getMessage();
            return view('livewire.appointment-confirmed-table', [
                'appointments' => collect([])
            ]);
        }
    }

    public function applyDateFilter()
    {
        try {
            if ($this->tempDateFilter) {
                // Store current value for debugging
                $oldValue = $this->dateFilter;
                
                // Update filter
                $this->dateFilter = $this->tempDateFilter;
                
                // $this->debugInfo = "Filter changed from: " . $oldValue . " to: " . $this->dateFilter;
                $this->resetPage(); // Reset pagination to first page
            } else {
                $this->debugInfo = "No date selected in tempDateFilter";
            }
        } catch (\Exception $e) {
            $this->debugInfo = "Apply filter error: " . $e->getMessage();
        }
    }
    
    public function resetDateFilter()
    {
        $this->dateFilter = '';
        $this->tempDateFilter = '';
        // $this->debugInfo = "Filter reset";
        $this->resetPage(); // Reset pagination to first page
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