<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Trivia;


class TriviaTable extends Component
{
    use WithPagination;
    public $search = '';
    public $showModal = false; // <-- Add this line
    public $question;
    public $answer;
    public $totalTrivias; // <-- Add this line

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $trivias = Trivia::where('question', 'like', '%'.$this->search.'%')
                        ->orWhere('answer', 'like', '%'.$this->search.'%')
                        ->paginate(10);

        $this->totalTrivias = Trivia::count(); // <-- Count total trivias
               
        return view('livewire.trivia-table', compact('trivias'));
    }

    public function deleteTrivia($id)
    {
        $trivia = Trivia::find($id);
        if ($trivia) {
            $trivia->delete();
            session()->flash('message', 'Trivia deleted successfully.');
        }
    }

    public function createTrivia()
    {
        $this->showModal = true;
        $this->reset(['question', 'answer']); // Reset input fields
    }

    public function saveTrivia()
    {
        $this->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Trivia::create([
            'question' => $this->question,
            'answer' => $this->answer,
        ]);

        $this->showModal = false;
        $this->trivias = Trivia::all(); // Refresh data
    }

}
