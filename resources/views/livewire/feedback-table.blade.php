<div>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-white mb-4">Feedbacks</h1>

        <!-- 🔹 Date Range Filter -->
        <div class="bg-white p-4 rounded-lg shadow-lg flex space-x-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" wire:model="startDate" class="border p-2 rounded-md w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" wire:model="endDate" class="border p-2 rounded-md w-full">
            </div>
            <div class="flex items-end">
                <button wire:click="filterByDate" class="bg-blue-600 text-white px-4 py-2 rounded-md">Filter</button>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto p-4">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">#</th>
                            <th class="border px-4 py-2">Service Type</th>
                            <th class="border px-4 py-2">Date</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $index => $feedback)
                            <tr>
                                <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                <td class="border px-4 py-2">{{ $feedback->service_type }}</td>
                                <td class="border px-4 py-2">{{ $feedback->created_at->format('F j, Y') }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <button wire:click="viewFeedback({{ $feedback->id }})" 
                                        class="bg-blue-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="flex justify-between items-center p-4">
                    <span class="text-sm text-gray-600">
                        Showing {{ $feedbacks->firstItem() }} to {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} rows
                    </span>
                    <div class="flex items-center space-x-2">
                        <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($feedbacks->onFirstPage()) disabled @endif>
                            «
                        </button>
                        @foreach ($feedbacks->getUrlRange(1, $feedbacks->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($feedbacks->currentPage() == $page) bg-blue-500 text-white @endif">
                                {{ $page }}
                            </button>
                        @endforeach
                        <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$feedbacks->hasMorePages()) disabled @endif>
                            »
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔹 Feedback Details Modal (Simplified) -->
        @if($showModal)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full text-center">
                    <!-- Header with Student Icon and "Student" Label -->
                    <div class="flex items-center justify-center mb-4 space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L1 7l11 5 9-4.09V15a5 5 0 01-10 0v-2.26L3 8.18v2.54L12 16l9-5.27V8.18l-9 4.54V7l7.91-3.5L12 2z"/>
                        </svg>
                        <span class="text-lg font-semibold text-gray-700">Student</span>
                    </div>

                    @if ($selectedFeedback)
                        <div class="mb-4">
                            @for ($i = 0; $i < $selectedFeedback->rating; $i++) 
                                ⭐ 
                            @endfor
                        </div>
                        <p class="italic text-gray-700">{{ $selectedFeedback->message }}</p>
                    @endif

                    <button wire:click="closeModal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-md">
                        Close
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>
