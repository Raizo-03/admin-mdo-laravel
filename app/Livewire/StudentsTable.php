<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class StudentsTable extends Component
{
    use WithPagination;
    public $statusFilter = 'all'; // 'all', 'active', or 'inactive'
    public $roleFilter = 'all'; // 'all', 'student', or 'faculty'

    public $search = '';

    // Reset pagination when search query changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

   public function render()
    {
        $query = User::where(function ($q) {
            $q->where('first_name', 'like', '%' . $this->search . '%')
              ->orWhere('last_name', 'like', '%' . $this->search . '%')
              ->orWhere('student_id', 'like', '%' . $this->search . '%')
              ->orWhere('umak_email', 'like', '%' . $this->search . '%');
        });

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply role filter
        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate counts for all combinations
        $statusCounts = [
            'all' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
        ];

        $roleCounts = [
            'all' => User::count(),
            'student' => User::where('role', 'student')->count(),
            'faculty' => User::where('role', 'faculty')->count(),
        ];

        return view('livewire.students-table', [
            'students' => $students,
            'statusCounts' => $statusCounts,
            'roleCounts' => $roleCounts,
        ]);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->roleFilter = 'all';
        $this->resetPage();
    }
}