<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;

class AppointmentCompletedTable extends Component
{
    use WithPagination;

    public $search = '';
    public $completedAppointmentsCount;

    public $showDeleteModal = false;

    public $showStatusModal = false;
    public $newStatus = '';

    public $selectedAppointment = null;

    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    public function mount()
    {
        // Get the count of completed appointments
        $this->completedAppointmentsCount = Appointment::where('status', 'Completed')->count();
    }

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

    public function updateStatus()
    {
        if ($this->selectedAppointment && in_array($this->newStatus, ['Approved', 'No Show'])) {
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
        // Fetch completed appointments with search functionality
        $appointments = Appointment::where('status', 'Completed')
            ->where(function ($query) {
                $query->where('umak_email', 'like', '%' . $this->search . '%')
                      ->orWhere('service', 'like', '%' . $this->search . '%')
                      ->orWhere('service_type', 'like', '%' . $this->search . '%')
                      ->orWhere('booking_date', 'like', '%' . $this->search . '%')
                      ->orWhere('remarks', 'like', '%' . $this->search . '%');
            })
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('livewire.appointment-completed-table', [
            'appointments' => $appointments
        ]);
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
            $this->completedAppointmentsCount = Appointment::where('status', 'Completed')->count();
        }
    }
}
