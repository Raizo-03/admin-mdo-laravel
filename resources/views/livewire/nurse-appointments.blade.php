<div class="container mx-auto p-6">

    <!-- Search Input -->
    <div class="flex items-center gap-4 mb-6">
        <input type="text" id="searchInput" placeholder="Search attended appointment..." class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white-900 text-black shadow-md">
    </div>

    <!-- Table Container -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <!-- Table Header -->
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2 text-left">#</th>
                        <th class="border px-4 py-2 text-left">Email</th>
                        <th class="border px-4 py-2 text-left">Service</th>
                        <th class="border px-4 py-2 text-left">Service Type</th>
                        <th class="border px-4 py-2 text-left">Date</th>
                        <th class="border px-4 py-2 text-left">Time</th>
                        <th class="border px-4 py-2 text-left">Remarks</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody id="appointmentTable">
                    @foreach ($appointments as $index => $appointment)
                        <tr class="hover:bg-gray-100 transition-colors duration-200">
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $appointment->umak_email }}</td>
                            <td class="border px-4 py-2">{{ $appointment->service }}</td>
                            <td class="border px-4 py-2">{{ $appointment->service_type }}</td>
                            <td class="border px-4 py-2">{{ $appointment->booking_date }}</td>
                            <td class="border px-4 py-2">{{ $appointment->booking_time }}</td>
                            <td class="border px-4 py-2">{{ $appointment->remarks }}</td>
                            <td class="border px-4 py-2">
                                <!-- Green status for 'Completed' -->
                                <span class="inline-block px-3 py-1 rounded-full 
                                    {{ $appointment->status === 'Completed' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <!-- Action Button -->
                                <a href="{{ route('appointments.completed.show', $appointment->booking_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-between items-center p-4">
                <span class="text-sm text-gray-600">
                    Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() ?? 0 }} appointments
                </span>
                <div class="flex items-center space-x-2">
                    <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($appointments->onFirstPage()) disabled @endif>
                        «
                    </button>
                    @if($appointments->lastPage() > 0)
                        @foreach ($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($appointments->currentPage() == $page) bg-blue-500 text-white @endif">
                                {{ $page }}
                            </button>
                        @endforeach
                    @endif
                    <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$appointments->hasMorePages()) disabled @endif>
                        »
                    </button>
                </div>
            </div>
        </div>
    </div>
    


    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- SweetAlert Handler -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('showAlert', (data) => {
                Swal.fire({
                    icon: data[0].type,
                    title: data[0].title,
                    text: data[0].text,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            });
        });
    </script>
    

    <script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#appointmentTable tr");
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

</script>

