<div>
    <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Confirmed Appointments</h1>
    <p class="text-gray-400 mb-4">Manage Confirmed Appointments</p>

    <div class="flex items-center gap-4 mb-4">
        <input type="text" id="searchInput" placeholder="Search confirmed appointment..." class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white">
    </div>

    <div class="flex items-center gap-4 mb-4">
        <div class="bg-white shadow-md rounded-lg px-3 py-2 h-55 flex items-center w-3xl">
            <div class="mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-600 text-l mr-1">Total Confirmed Appointments</h3>
                <p class="text-l font-bold text-blue-600">{{ $approvedAppointmentsCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">#</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Service</th>
                        <th class="border px-4 py-2">Service Type</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Time</th>
                        <th class="border px-4 py-2">Remarks</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
            <tbody id="appointmentTable">
                    @foreach ($appointments as $index => $appointment)
                        <tr>
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $appointment->umak_email }}</td>
                            <td class="border px-4 py-2">{{ $appointment->service }}</td>
                            <td class="border px-4 py-2">{{ $appointment->service_type }}</td>
                            <td class="border px-4 py-2">{{ $appointment->booking_date }}</td>
                            <td class="border px-4 py-2">{{ $appointment->booking_time }}</td>
                            <td class="border px-4 py-2">{{ $appointment->remarks }}</td>
                            <td class="border px-4 py-2">
                                    <a href="{{ route('appointments.confirmed.show', $appointment->booking_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md mr-1">
                                        View
                                    </a>
                                <button wire:click="openStatusModal({{ $appointment->booking_id }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md">
                                    Complete
                                </button>
                                <button wire:click="openDeleteModal({{ $appointment->booking_id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md">
                                    Delete
                                </button>
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
    
    <!-- Status Change Modal -->
    @if($showStatusModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 max-w-md">
            <h2 class="text-2xl font-bold mb-4">Update Appointment Status</h2>
            
            @if($selectedAppointment)
            <div class="mb-4">
                <p class="text-gray-700"><strong>Email:</strong> {{ $selectedAppointment->umak_email }}</p>
                <p class="text-gray-700"><strong>Service:</strong> {{ $selectedAppointment->service }}</p>
                <p class="text-gray-700"><strong>Date/Time:</strong> {{ $selectedAppointment->booking_date }} at {{ $selectedAppointment->booking_time }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Change Status to:</label>
                <div class="flex flex-col space-y-2">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="newStatus" value="Completed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Completed</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="newStatus" value="No Show" class="form-radio h-5 w-5 text-red-600">
                        <span class="ml-2 text-gray-700">No Show</span>
                    </label>
                </div>
            </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button wire:click="closeStatusModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Cancel
                </button>
                <button wire:click="updateStatus" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    Update Status
                </button>
            </div>
        </div>
    </div>
    @endif

        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 max-w-md">
                <h2 class="text-2xl font-bold mb-4">Delete Appointment</h2>
                
                @if($selectedAppointment)
                <div class="mb-4">
                    <p class="text-gray-700 mb-4">Are you sure you want to delete this appointment?</p>
                    <p class="text-gray-700"><strong>Email:</strong> {{ $selectedAppointment->umak_email }}</p>
                    <p class="text-gray-700"><strong>Service:</strong> {{ $selectedAppointment->service }}</p>
                    <p class="text-gray-700"><strong>Date/Time:</strong> {{ $selectedAppointment->booking_date }} at {{ $selectedAppointment->booking_time }}</p>
                </div>
                @endif

                <div class="flex justify-end space-x-2">
                    <button wire:click="closeDeleteModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button wire:click="deleteAppointment" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @endif

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

