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
    
    // Date range filter properties (updated from single date filter)
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $tempDateFromFilter = '';
    public $tempDateToFilter = '';
    public $debugInfo = '';
    
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

            // Apply date range filter to count as well
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

            $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);
            
            // Update the count for pagination message
            $this->bookingsCount = $bookings->total();
        }

        return view('livewire.student-bookings-table', [
            'bookings' => $bookings
        ]);
    }

    // Date Range Filter Functionality
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
            $this->updateBookingsCount(); // Update count with new filters
            
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
        $this->updateBookingsCount(); // Update count after clearing filters
    }

    // Optional: Clear all filters method
    public function clearAllFilters()
    {
        $this->search = '';
        $this->dateFromFilter = '';
        $this->dateToFilter = '';
        $this->tempDateFromFilter = '';
        $this->tempDateToFilter = '';
        $this->resetPage();
        $this->updateBookingsCount();
    }
}