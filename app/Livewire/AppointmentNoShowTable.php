<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;
use Carbon\Carbon;


class AppointmentNoShowTable extends Component
{
    use WithPagination;

    public $search = '';
    public $noshowAppointmentCount;

    public $showDeleteModal = false;

    public $showStatusModal = false;
    public $newStatus = '';

    // Date range filter properties (updated from single date filter)
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $tempDateFromFilter = '';
    public $tempDateToFilter = '';
    public $debugInfo = '';

    public $selectedAppointment = null;

    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    public function mount()
    {
        // Get the count of completed appointments
        $this->noshowAppointmentCount = Appointment::where('status', 'No Show')->count();
    }

    public function openStatusModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::find($appointmentId);
        $this->newStatus = 'Approved'; // Default selection
        $this->showStatusModal = true;
    }

    // Close the modal
    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->selectedAppointment = null;
        $this->newStatus = '';
    }

    public function updateStatus()
    {
        if ($this->selectedAppointment && in_array($this->newStatus, ['Approved', 'Completed'])) {
            $this->selectedAppointment->status = $this->newStatus;
            $this->selectedAppointment->save();
            
            // Update the counter after changing status
            $this->noshowAppointmentCount = Appointment::where('status', 'No Show')->count();
            
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
            // Fetch no show appointments with search functionality and date range filter
            $query = Appointment::where('status', 'No Show'); // Only no show appointments
            
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
            
            // Apply date range filter
            if ($this->dateFromFilter && $this->dateToFilter) {
                try {
                    $fromDate = Carbon::parse($this->dateFromFilter)->format('Y-m-d');
                    $toDate = Carbon::parse($this->dateToFilter)->format('Y-m-d');
                    $query->whereBetween('booking_date', [$fromDate, $toDate]);
                } catch (\Exception $e) {
                    // Handle date parsing error silently
                }
            } elseif ($this->dateFromFilter) {
                // Only from date provided
                try {
                    $fromDate = Carbon::parse($this->dateFromFilter)->format('Y-m-d');
                    $query->whereDate('booking_date', '>=', $fromDate);
                } catch (\Exception $e) {
                    // Handle date parsing error silently
                }
            } elseif ($this->dateToFilter) {
                // Only to date provided
                try {
                    $toDate = Carbon::parse($this->dateToFilter)->format('Y-m-d');
                    $query->whereDate('booking_date', '<=', $toDate);
                } catch (\Exception $e) {
                    // Handle date parsing error silently
                }
            }
            
            $appointments = $query->orderBy('booking_date', 'desc')
                                ->paginate(10);
            
            return view('livewire.appointment-no-show-table', [
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            $this->debugInfo = "Render error: " . $e->getMessage();
            return view('livewire.appointment-no-show-table', [
                'appointments' => collect([])
            ]);
        }
    }

    public function applyDateFilter()
    {
        try {
            // Validate that from date is not later than to date
            if ($this->tempDateFromFilter && $this->tempDateToFilter) {
                $fromDate = Carbon::parse($this->tempDateFromFilter);
                $toDate = Carbon::parse($this->tempDateToFilter);
                
                if ($fromDate->gt($toDate)) {
                    $this->dispatch('showAlert', [
                        'type' => 'error',
                        'title' => 'Invalid Date Range!',
                        'text' => 'From date cannot be later than To date.'
                    ]);
                    return;
                }
            }
            
            // Apply the filters
            $this->dateFromFilter = $this->tempDateFromFilter;
            $this->dateToFilter = $this->tempDateToFilter;
            
            $this->resetPage(); // Reset pagination to first page
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'title' => 'Error!',
                'text' => 'Invalid date format. Please check your dates.'
            ]);
        }
    }
    
    public function resetDateFilter()
    {
        $this->dateFromFilter = '';
        $this->dateToFilter = '';
        $this->tempDateFromFilter = '';
        $this->tempDateToFilter = '';
        $this->resetPage(); // Reset pagination to first page
    }

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
            $this->selectedAppointment->delete();

            // Show SweetAlert success
            $this->dispatch('showAlert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Deleted successfully!'
            ]);

            $this->closeDeleteModal();
            $this->completedAppointmentsCount = Appointment::where('status', 'No Show')->count();
        }
    }
}
