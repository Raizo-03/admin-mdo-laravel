<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;
use Carbon\Carbon;

class StudentBookingsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $studentEmail;
    public $bookingsCount;
    public $selectedBooking = null;
    public $showStatusModal = false;
    public $newStatus = '';
    public $dateFilter = '';
    public $tempDateFilter = '';
    
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
            $query = Appointment::where('umak_email', $this->studentEmail)
                ->where('status', 'Completed');

            // Apply date filter to count as well
            if ($this->dateFilter) {
                try {
                    $filterDate = Carbon::parse($this->dateFilter)->format('Y-m-d');
                    $query->whereDate('booking_date', $filterDate);
                } catch (\Exception $e) {
                    // If date parsing fails, ignore the filter
                }
            }

            $this->bookingsCount = $query->count();
        }
    }

    public function render()
    {
        $bookings = collect([]);
        
        if ($this->studentEmail) {
            $query = Appointment::where('umak_email', $this->studentEmail)
                ->where('status', 'Completed')
                ->where(function ($subQuery) {
                    $subQuery->where('service', 'like', '%' . $this->search . '%')
                             ->orWhere('service_type', 'like', '%' . $this->search . '%')
                             ->orWhere('booking_date', 'like', '%' . $this->search . '%')
                             ->orWhere('remarks', 'like', '%' . $this->search . '%');
                });

            // Apply date filter if set
            if ($this->dateFilter) {
                try {
                    $filterDate = Carbon::parse($this->dateFilter)->format('Y-m-d');
                    $query->whereDate('booking_date', $filterDate);
                } catch (\Exception $e) {
                    // If date parsing fails, ignore the filter for this render
                    // You could also set an error message here if needed
                }
            }

            $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);
            
            // Update the count for pagination message
            $this->bookingsCount = $bookings->total();
        }

        return view('livewire.student-bookings-table', [
            'bookings' => $bookings
        ]);
    }

    // Date Filter Functionality
    public function applyDateFilter()
    {
        try {
            if ($this->tempDateFilter) {
                // Validate the date format
                $validatedDate = Carbon::parse($this->tempDateFilter)->format('Y-m-d');
                
                // Update filter with validated date
                $this->dateFilter = $validatedDate;
                
                // Reset pagination to first page
                $this->resetPage();
                
                // Update bookings count
                $this->updateBookingsCount();
                
            } else {
                // If no date selected, clear the filter
                $this->resetDateFilter();
            }
        } catch (\Exception $e) {
            // Handle invalid date format
            $this->addError('dateFilter', 'Invalid date format. Please select a valid date.');
            $this->tempDateFilter = '';
        }
    }
    
    public function resetDateFilter()
    {
        $this->dateFilter = '';
        $this->tempDateFilter = '';
        
        // Reset pagination to first page
        $this->resetPage();
        
        // Update bookings count
        $this->updateBookingsCount();
        
        // Clear any date-related errors
        $this->resetErrorBag('dateFilter');
    }

    // Optional: Add a method to handle date filter changes in real-time
    public function updatedTempDateFilter()
    {
        // Automatically apply filter when date is selected
        if ($this->tempDateFilter) {
            $this->applyDateFilter();
        }
    }

    // Optional: Clear filters method
    public function clearAllFilters()
    {
        $this->search = '';
        $this->dateFilter = '';
        $this->tempDateFilter = '';
        $this->resetPage();
        $this->updateBookingsCount();
        $this->resetErrorBag();
    }
}