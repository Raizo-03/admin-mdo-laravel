<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admin;

class AdminsTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $admins = Admin::where('role', 'admin') // Only fetch admins with role "admin"
                       ->where(function ($query) {
                           $query->where('username', 'like', '%' . $this->search . '%')
                                 ->orWhere('email', 'like', '%' . $this->search . '%');
                       })
                       ->paginate(10);
    
        return view('livewire.admins-table', compact('admins'));
    }


    
    
}
