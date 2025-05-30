<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminsTable extends Component
{
    use WithPagination;
    public $statusFilter = 'all'; // New property for status filter

    public $search = '';

           // Status change properties
    public $statusId, $statusUsername, $statusCurrentStatus;
    public $isStatusModalOpen = false;
    public $newStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

   public function render()
    {
        $query = Admin::where('role', 'admin')
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
            'all' => Admin::where('role', 'admin')->count(),
            'active' => Admin::where('role', 'admin')->where('status', 'active')->count(),
            'archived' => Admin::where('role', 'admin')->where('status', 'archived')->count(),
        ];
    
        return view('livewire.admins-table', compact('admins', 'user', 'statusCounts'));
    }

    // Status change methods
    public function openStatusModal($id, $username, $currentStatus)
    {
        $this->statusId = $id;
        $this->statusUsername = $username;
        $this->statusCurrentStatus = $currentStatus;
        $this->newStatus = $currentStatus; // Set current status as default
        $this->isStatusModalOpen = true;
    }

    public function closeStatusModal()
    {
        $this->reset(['statusId', 'statusUsername', 'statusCurrentStatus', 'newStatus']);
        $this->isStatusModalOpen = false;
    }
public function updateStatus()
{
    try {
        if ($this->statusId && $this->newStatus) {
            $updated = Admin::where('admin_id', $this->statusId)->update([
                'status' => $this->newStatus
            ]);

            if ($updated) {
                $this->dispatch('status-updated');
            } else {
                $this->dispatch('status-error');
            }
        }
    } catch (\Exception $e) {
        $this->dispatch('status-error');
    }

    $this->closeStatusModal();
}

        public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
    }
    
    
}
