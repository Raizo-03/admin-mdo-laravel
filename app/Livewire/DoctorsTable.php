<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // New property for status filter
    public $username = '';
    public $name = ''; // Added missing name property
    public $email = '';
    public $password = '';
    public $isModalOpen = false;

    public $deleteId, $deleteUsername, $deleteEmail;
    public $isDeleteModalOpen = false;

    // Status change properties
    public $statusId, $statusUsername, $statusCurrentStatus;
    public $isStatusModalOpen = false;
    public $newStatus = '';

    protected $rules = [
        'username' => 'required|string|max:255|unique:Admins,username',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:Admins,email',
        'password' => 'required|string|min:3',
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when status filter changes
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->username = '';
        $this->name = ''; // Added name reset
        $this->email = '';
        $this->password = '';
    }

    public function addDoctor()
    {
        $this->validate();

        Admin::create([
            'username' => $this->username,
            'name' => $this->name, 
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'doctor'
        ]);
        $this->dispatch('doctor-added')->to(null);
        session()->flash('message', 'Doctor added successfully.');
        $this->closeModal();
    }

    public function render()
    {
        $query = Admin::where('role', 'doctor')
            ->where(function ($q) {
                $q->where('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
            });

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $admins = $query->paginate(10);
        $user = Auth::guard('admin')->user();

        // Get counts for filter buttons
        $statusCounts = [
            'all' => Admin::where('role', 'doctor')->count(),
            'active' => Admin::where('role', 'doctor')->where('status', 'active')->count(),
            'archived' => Admin::where('role', 'doctor')->where('status', 'archived')->count(),
        ];
    
        return view('livewire.doctors-table', compact('admins', 'user', 'statusCounts'));
    }
    
    public function openDeleteModal($id, $username, $email)
    {
        $this->deleteId = $id;
        $this->deleteUsername = $username;
        $this->deleteEmail = $email;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->reset(['deleteId', 'deleteUsername', 'deleteEmail']);
        $this->isDeleteModalOpen = false;
    }

    public function deleteAdmin()
    {
        if ($this->deleteId) {
            Admin::where('admin_id', $this->deleteId)->delete();
            session()->flash('message', 'Admin deleted successfully.');
        }

        $this->closeDeleteModal();
    }

    // Status change methods
    public function openStatusModal($id, $username, $currentStatus)
    {
        $this->statusId = $id;
        $this->statusUsername = $username;
        $this->statusCurrentStatus = $currentStatus;
        $this->newStatus = $currentStatus;
        $this->isStatusModalOpen = true;
    }

    public function closeStatusModal()
    {
        $this->reset(['statusId', 'statusUsername', 'statusCurrentStatus', 'newStatus']);
        $this->isStatusModalOpen = false;
    }

    public function updateStatus()
    {
        if ($this->statusId && $this->newStatus) {
            Admin::where('admin_id', $this->statusId)->update([
                'status' => $this->newStatus
            ]);
            
        $this->dispatch('status-updated')->to(null);
        }

        $this->closeStatusModal();
    }

    // Method to clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
    }
}