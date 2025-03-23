<div>
    <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Trivias</h1>

    <div class="flex items-center gap-4 mb-4">
        <input type="text" id="searchInput" placeholder="Search trivias..." class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white">
    </div>


    <!-- Add Trivia Button -->
    <button wire:click="createTrivia" class="bg-blue-600 text-white px-4 py-2 rounded-md mb-4">
        + Add Trivia
    </button>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="overflow-x-auto p-4">
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Question</th>
                <th class="border px-4 py-2">Answer</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
            <tbody id="triviaTable">
            @foreach ($trivias as $index => $trivia)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $trivia->question }}</td>
                    <td class="border px-4 py-2">{{ $trivia->answer }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="deleteTrivia({{ $trivia->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md">
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
        Showing {{ $trivias->firstItem() }} to {{ $trivias->lastItem() }} of {{ $trivias->total() }} rows
    </span>
    <div class="flex items-center space-x-2">
        <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($trivias->onFirstPage()) disabled @endif>
            «
        </button>
        @foreach ($trivias->getUrlRange(1, $trivias->lastPage()) as $page => $url)
            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($trivias->currentPage() == $page) bg-blue-500 text-white @endif">
                {{ $page }}
            </button>
        @endforeach
        <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$trivias->hasMorePages()) disabled @endif>
            »
        </button>
    </div>
</div>


    </div>
</div>


    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-bold mb-4">Add Trivia</h2>

            <label class="block text-gray-700">Question:</label>
            <input type="text" wire:model="question" class="w-full p-2 border rounded mb-2">

            <label class="block text-gray-700">Answer:</label>
            <input type="text" wire:model="answer" class="w-full p-2 border rounded mb-4">

            <div class="flex justify-end space-x-2">
                <button wire:click="$set('showModal', false)" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button wire:click="saveTrivia" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </div>
    </div>
    @endif



</div>
<script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#triviaTable tr");
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

</script>
