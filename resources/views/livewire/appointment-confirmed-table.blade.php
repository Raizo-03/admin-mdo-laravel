<div>
    <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Confirmed Appointments</h1>
    <p class="text-gray-400 mb-4">Manage Confirmed Appointments</p>

    {{-- Date Range Filter --}}
    <div class="bg-gray-800 p-4 rounded-lg mb-4">
        <h3 class="text-white text-lg mb-3">Date Range Filter</h3>
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex flex-col">
                <label for="dateFrom" class="text-gray-300 text-sm mb-1">From Date</label>
                <input 
                    type="date" 
                    id="dateFrom"
                    wire:model="tempDateFromFilter" 
                    class="px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white"
                >
            </div>
            
            <div class="flex flex-col">
                <label for="dateTo" class="text-gray-300 text-sm mb-1">To Date</label>
                <input 
                    type="date" 
                    id="dateTo"
                    wire:model="tempDateToFilter" 
                    class="px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white"
                >
            </div>
            
            <div class="flex items-end gap-2">
                <button 
                    wire:click="applyDateFilter" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors"
                >
                    Apply Filter
                </button>
                
                @if($dateFromFilter || $dateToFilter)
                <button 
                    wire:click="resetDateFilter" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors"
                >
                    Clear Filter
                </button>
                @endif
            </div>
        </div>
        
        {{-- Display active filters --}}
        @if($dateFromFilter || $dateToFilter)
            <div class="mt-3 text-gray-300 text-sm">
                <span class="font-semibold">Active Filter:</span>
                @if($dateFromFilter && $dateToFilter)
                    From {{ \Carbon\Carbon::parse($dateFromFilter)->format('M d, Y') }} 
                    to {{ \Carbon\Carbon::parse($dateToFilter)->format('M d, Y') }}
                @elseif($dateFromFilter)
                    From {{ \Carbon\Carbon::parse($dateFromFilter)->format('M d, Y') }} onwards
                @elseif($dateToFilter)
                    Up to {{ \Carbon\Carbon::parse($dateToFilter)->format('M d, Y') }}
                @endif
            </div>
        @endif
    </div>

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
                            <td>{{ \Carbon\Carbon::parse($appointment->booking_date)->toFormattedDateString() }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->booking_time)->format('g:i A') }}</td>
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
        <div class="border-t pt-4 pb-2 float-right pr-10">
                <div class="flex justify-center items-center space-x-4">
                    <h3 class="text-sm font-semibold text-gray-700">Export Data:</h3>
                    <button 
                        onclick="downloadData('csv')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Download CSV</span>
                    </button>
                    <button 
                        onclick="downloadData('excel')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Download Excel</span>
                    </button>
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

  <script>
        function downloadData(format) {
            // Show loading alert
            Swal.fire({
                title: 'Preparing Download...',
                text: `Generating ${format.toUpperCase()} file, please wait.`,
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make the download request
            fetch(`{{ route('confirmedappointments.export') }}?format=${format}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.blob();
            })
            .then(blob => {
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                
                // Set filename with current date
                const now = new Date();
                const dateStr = now.toISOString().split('T')[0];
                a.download = `appointments_${dateStr}.csv`;
                
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                // Show success alert
                Swal.fire({
                    title: 'Download Complete!',
                    text: `Your ${format.toUpperCase()} file has been downloaded successfully.`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Download error:', error);
                
                // Show error alert
                Swal.fire({
                    title: 'Download Failed',
                    text: 'There was an error downloading the file. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>