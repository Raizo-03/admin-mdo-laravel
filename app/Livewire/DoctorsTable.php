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
    public $username = '';
    public $email = '';
    public $password = '';
    public $isModalOpen = false;

    public $deleteId, $deleteUsername, $deleteEmail;
    public $isDeleteModalOpen = false;

    protected $rules = [
        'username' => 'required|string|max:255|unique:Admins,username',
        'email' => 'required|email|unique:Admins,email',
        'password' => 'required|string|min:3',
    ];
    

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetInputFields(); // Ensure inputs are cleared before showing the modal
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
        $this->email = '';
        $this->password = '';
    }

    public function addDoctor()
    {
        $this->validate();

        Admin::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'doctor'
        ]);

        session()->flash('message', 'Doctor added successfully.');
        $this->closeModal();
    }

    public function render()
    {
        $admins = Admin::where('role', 'doctor')
            ->where(function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    
            $user = Auth::guard('admin')->user();
    
        return view('livewire.doctors-table', compact('admins', 'user'));
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



}