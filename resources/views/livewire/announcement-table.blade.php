<div>
    <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Announcements</h1>
    <p class="text-gray-400 mb-4">Create, Review, and Publish</p>


<div>
    <!-- Add Announcement Button -->
    <button wire:click="createAnnouncement" class="bg-blue-600 text-white px-4 py-2 rounded-md mb-4">
        + Add Announcement
    </button>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
            <button wire:click="$set('isModalOpen', false)" class="absolute top-2 right-3 text-gray-600 hover:text-red-600">
                ✖
            </button>

            <h2 class="text-2xl font-bold mb-4">Add Announcement</h2>

            <!-- Image Upload Preview -->
            <div class="mb-4">
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-40 object-cover rounded-md mb-2">
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500 rounded-md">
                        No Image Selected
                    </div>
                @endif
                <input type="file" wire:model="image" class="mt-2">
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Title Input -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Title</label>
                <input type="text" wire:model="title" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300">
                {{-- @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
            </div>

            <!-- Details Input -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Details</label>
                <textarea wire:model="details" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"></textarea>
                {{-- @error('details') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('isModalOpen', false)" class="bg-gray-500 text-white px-4 py-2 rounded-md">
                    Cancel
                </button>
                <button wire:click="saveAnnouncement" class="bg-blue-600 text-white px-4 py-2 rounded-md">
                    Save
                </button>
            </div>
        </div>
    </div>
    @endif
</div>


    <h2 class="text-2xl font-bold text-white mb-4">For Review</h2>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="overflow-x-auto p-4">
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Title</th>
                <th class="border px-4 py-2">Details</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
               </thead>
            <tbody id="triviaTable">
            @foreach ($reviewAnnouncements as $index => $announcement)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $announcement->title }}</td>
                    <td class="border px-4 py-2">{{ $announcement->details }}</td>
                    <td class="border px-4 py-2 text-center whitespace-nowrap">
                            <button wire:click="viewAnnouncement({{ $announcement->id }})" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            View
                        </button>
                                    <button wire:click="confirmPublish({{ $announcement->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                        Publish
                                    </button>
                        
                        <button wire:click="confirmDelete({{ $announcement->id }})" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

            <!-- Pagination inside Livewire Component -->
<div class="flex justify-between items-center p-4">
    <span class="text-sm text-gray-600">
        Showing {{ $reviewAnnouncements->firstItem() }} to {{ $reviewAnnouncements->lastItem() }} of {{ $reviewAnnouncements->total() }} rows
    </span>
    <div class="flex items-center space-x-2">
        <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($reviewAnnouncements->onFirstPage()) disabled @endif>
            «
        </button>
        @foreach ($reviewAnnouncements->getUrlRange(1, $reviewAnnouncements->lastPage()) as $page => $url)
            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($reviewAnnouncements->currentPage() == $page) bg-blue-500 text-white @endif">
                {{ $page }}
            </button>
        @endforeach
        <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$reviewAnnouncements->hasMorePages()) disabled @endif>
            »
        </button>
    </div>
</div>

    </div>
    </div>


    <h2 class="text-2xl font-bold text-white mb-4 pt-10">Published</h2>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="overflow-x-auto p-4">
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Title</th>
                <th class="border px-4 py-2">Details</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
               </thead>
            <tbody id="triviaTable">
                @foreach ($publishedAnnouncements as $index => $announcement)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $announcement->title }}</td>
                    <td class="border px-4 py-2">{{ $announcement->details }}</td>
                    <td class="border px-4 py-2 text-center whitespace-nowrap">
                        <button wire:click="viewAnnouncement({{ $announcement->id }})" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            View
                        </button>
                        <button wire:click="confirmDelete({{ $announcement->id }})" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

                <!-- Pagination inside Livewire Component -->
<div class="flex justify-between items-center p-4">
    <span class="text-sm text-gray-600">
        Showing {{ $publishedAnnouncements->firstItem() }} to {{ $publishedAnnouncements->lastItem() }} of {{ $publishedAnnouncements->total() }} rows
    </span>
    <div class="flex items-center space-x-2">
        <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($publishedAnnouncements->onFirstPage()) disabled @endif>
            «
        </button>
        @foreach ($publishedAnnouncements->getUrlRange(1, $publishedAnnouncements->lastPage()) as $page => $url)
            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($publishedAnnouncements->currentPage() == $page) bg-blue-500 text-white @endif">
                {{ $page }}
            </button>
        @endforeach
        <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$publishedAnnouncements->hasMorePages()) disabled @endif>
            »
        </button>
    </div>
</div>
   </div>
    </div>
    
<!-- View Announcement Modal -->
@if($isViewModalOpen)
<div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">{{ $viewTitle }}</h2>
        
        @if($viewImage)
        <img src="{{ $viewImage }}" alt="Announcement Image" class="mb-4 w-full rounded-lg">
        @endif

        <p class="text-gray-700">{{ $viewDetails }}</p>
        
        <div class="mt-4 flex justify-end">
            <button wire:click="closeViewModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Close
            </button>
        </div>
    </div>
</div>
@endif

<!-- Publish Confirmation Modal -->
@if($isPublishModalOpen)
<div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50" wire:key="publish-modal">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Confirm Publish</h2>
        <p>Are you sure you want to publish this announcement?</p>
        <div class="mt-4 flex justify-end space-x-2">
            <button wire:click="$set('isPublishModalOpen', false)" class="bg-gray-500 text-white px-4 py-2 rounded-md">
                No
            </button>
            <button wire:click="publish" class="bg-green-600 text-white px-4 py-2 rounded-md">
                Yes
            </button>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
@if($isDeleteModalOpen)
<div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
        <p>Are you sure you want to delete this announcement?</p>
        <div class="mt-4 flex justify-end space-x-2">
            <button wire:click="$set('isDeleteModalOpen', false)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </button>
            <button wire:click="deleteAnnouncement" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Delete
            </button>
        </div>
    </div>
</div>
@endif

</div>
