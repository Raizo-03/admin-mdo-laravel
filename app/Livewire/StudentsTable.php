<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class StudentsTable extends Component
{
    use WithPagination;

    public $search = '';

    // Reset pagination when search query changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Apply search filter
        $students = User::where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('student_id', 'like', '%' . $this->search . '%')
                        ->orWhere('umak_email', 'like', '%' . $this->search . '%')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10); // Keep pagination

        return view('livewire.students-table', [
            'students' => $students  // ✅ Use the filtered students
        ]);
    }
}

