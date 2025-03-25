<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Feedback;
use Carbon\Carbon;
class FeedbackTable extends Component
{
    use WithPagination;

    public $startDate, $endDate, $selectedFeedback, $showModal = false;

    public function render()
    {
        $query = Feedback::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        }

        $feedbacks = $query->latest()->paginate(10);

        return view('livewire.feedback-table', compact('feedbacks'));
    }

    public function filterByDate()
    {
        $this->resetPage(); // Reset pagination when filtering
    }

    public function viewFeedback($id)
    {
        $this->selectedFeedback = Feedback::find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedFeedback = null;
    }
}
