<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Cloudinary\Cloudinary;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

class AnnouncementTable extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $details, $image, $image_url;
    public $isModalOpen = false;
    public $search = '';
    public $viewTitle, $viewDetails, $viewImage;
    public $isViewModalOpen = false;

    public $isPublishModalOpen = false;
    public $announcementIdToPublish;
    protected $listeners = ['confirmPublish' => 'confirmPublish'];
    public $isDeleteModalOpen = false;
    public $announcementIdToDelete;
    public $deleteAnnouncementId;
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'details' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    

    public function createAnnouncement()
    {
        $this->reset(['title', 'details', 'image', 'image_url']);
        $this->isModalOpen = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.announcement-table', [
            'reviewAnnouncements' => Announcement::where('status', 'review')->paginate(10),
            'publishedAnnouncements' => Announcement::where('status', 'published')->paginate(10), // Fix 'publish' -> 'published'
        ]);
    }
    

    public function saveAnnouncement()
    {
        $this->validate();

        try {
            $this->image_url = null;
            $admin = auth('admin')->user();

            if (!$admin) {
                return redirect()->route('login')->with('error', 'You must be logged in to update your profile picture.');
            }

            if ($this->image) {
                // Initialize Cloudinary using the URL from .env
                $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

                // Upload the image to Cloudinary
                $uploadResult = $cloudinary->uploadApi()->upload($this->image->getRealPath(), [
                    'resource_type' => 'auto',
                    'folder' => 'announcements'
                ]);

                // Get the secure image URL
                $this->image_url = $uploadResult['secure_url'] ?? null;
            }

            // Save the announcement directly to the database
            $announcement = Announcement::create([
                'title' => $this->title,
                'details' => $this->details,
                'image_url' => $this->image_url,
                'status' => 'review',  // Default status when creating an announcement
            ]);

            if ($announcement) {
                session()->flash('message', 'Announcement saved successfully!');
                $this->reset(['title', 'details', 'image', 'image_url']);
            } else {
                session()->flash('error', 'Failed to save announcement.');
            }

        } catch (\Exception $e) {
            Log::error('Error saving announcement: ' . $e->getMessage());
            session()->flash('error', 'Error uploading image: ' . $e->getMessage());
        }

        $this->isModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function viewAnnouncement($id)
{
    $announcement = Announcement::find($id);

    if ($announcement) {
        $this->viewTitle = $announcement->title;
        $this->viewDetails = $announcement->details;
        $this->viewImage = $announcement->image_url;
        $this->isViewModalOpen = true;
    }
}

public function closeViewModal()
{
    $this->isViewModalOpen = false;
}


public function confirmPublish($id)
{
    $this->announcementIdToPublish = $id;
    $this->isPublishModalOpen = true;
}


public function publish()
{
    if ($this->announcementIdToPublish) {
        $announcement = Announcement::find($this->announcementIdToPublish);
        if ($announcement) {
            $announcement->status = 'published'; // Update status
            $announcement->save();

            $this->isPublishModalOpen = false; // Close modal
            $this->announcementIdToPublish = null; // Reset variable
        }
    }
}

public function confirmDelete($id)
{
    $this->deleteAnnouncementId = $id;
    $this->isDeleteModalOpen = true;
}

public function deleteAnnouncement()
{
    if ($this->deleteAnnouncementId) {
        Announcement::find($this->deleteAnnouncementId)->delete();
        $this->isDeleteModalOpen = false;
        $this->deleteAnnouncementId = null;
    }
}


}
