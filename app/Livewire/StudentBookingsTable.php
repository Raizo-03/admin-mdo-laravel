<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;

class StudentBookingsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $studentEmail;
    public $bookingsCount;
    public $selectedBooking = null;
    public $showStatusModal = false;
    public $newStatus = '';

    protected $listeners = ['loadStudentBookings' => 'setStudentEmail'];

    public function setStudentEmail($email)
    {
        // Check if $email is a string or an array or object
        if (is_string($email)) {
            $this->studentEmail = $email;
        } 
        // If it's passed as an array with an email key
        else if (is_array($email) && isset($email['email'])) {
            $this->studentEmail = $email['email'];
        }
        // If it's passed as a single value without a key
        else {
            $this->studentEmail = $email;
        }
        
        $this->resetPage();
        $this->updateBookingsCount();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateBookingsCount()
    {
        if ($this->studentEmail) {
            $this->bookingsCount = Appointment::where('umak_email', $this->studentEmail)
                ->where('status', 'Completed')
                ->count();
        }
    }

    public function render()
    {
        $bookings = collect([]);
        
        if ($this->studentEmail) {
            $bookings = Appointment::where('umak_email', $this->studentEmail)
            ->where('status', 'Completed')
            ->where(function ($query) {
                $query->where('service', 'like', '%' . $this->search . '%')
                      ->orWhere('service_type', 'like', '%' . $this->search . '%')
                      ->orWhere('booking_date', 'like', '%' . $this->search . '%')
                      ->orWhere('remarks', 'like', '%' . $this->search . '%');
            })
            ->orderBy('booking_date', 'desc')
            ->paginate(10);
    
        $this->bookingsCount = $bookings->total();  // Update the count for pagination message
        }

        return view('livewire.student-bookings-table', [
            'bookings' => $bookings
        ]);
    }
    

}