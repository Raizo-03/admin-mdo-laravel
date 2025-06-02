<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Admins</h1>
    <p class="text-gray-400 mb-4">All list of admins</p>

@php
    $cantAddDoctor = $user->role === 'doctor' || $user->role === 'nurse';
    $cantManageDoctor = $user->role === 'doctor' || $user->role === 'nurse';
@endphp

    <!-- Search & Filter Section -->
    <div class="mb-6 space-y-4">
        <!-- Search Input -->
        <div class="flex items-center gap-4">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Search admins..." 
                   class="flex-1 px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white">
            
            <!-- Clear Filters Button -->
            <button wire:click="clearFilters" 
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition duration-200">
                🗑️ Clear
            </button>
        </div>

        <!-- Status Filter Buttons -->
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('statusFilter', 'all')" 
                    class="px-4 py-2 rounded-md transition duration-200 {{ $statusFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                All ({{ $statusCounts['all'] }})
            </button>
            
            <button wire:click="$set('statusFilter', 'active')" 
                    class="px-4 py-2 rounded-md transition duration-200 {{ $statusFilter === 'active' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Active ({{ $statusCounts['active'] }})
            </button>
            
            <button wire:click="$set('statusFilter', 'archived')" 
                    class="px-4 py-2 rounded-md transition duration-200 {{ $statusFilter === 'archived' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Archived ({{ $statusCounts['archived'] }})
            </button>
        </div>

        <!-- Current Filter Display -->
        @if($statusFilter !== 'all' || $search)
        <div class="text-sm text-gray-300">
            <span class="font-medium">Filters applied:</span>
            @if($search)
                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs mr-2">
                    Search: "{{ $search }}"
                </span>
            @endif
            @if($statusFilter !== 'all')
                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                    Status: {{ ucfirst($statusFilter) }}
                </span>
            @endif
        </div>
        @endif
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto p-4">
           <!-- Results Count -->
            <div class="mb-4 text-sm text-gray-600">
                Showing {{ $admins->count() }} of {{ $admins->total() }} admins
                @if($statusFilter !== 'all')
                    (filtered by: {{ ucfirst($statusFilter) }} status)
                @endif
            </div>
            @if($admins->count() > 0)
            <table class="w-full border-collapse text-gray-900">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-left">
                        <th class="px-4 py-3">Profile</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody id="adminTable">
                    @foreach($admins as $admin)
                    <tr class="border-b border-gray-300 hover:bg-gray-100 transition duration-200">
                        <td class="px-4 py-3">
                        <img src="{{ $admin->profile_picture }}" 
                            alt="{{ $admin->username }}'s profile" 
                            class="w-12 h-12 rounded-full object-cover"
                            id="profilePic-{{ $admin->admin_id }}">
                        </td>
                        <td class="px-4 py-3">{{ $admin->name }}</td>
                        <td class="px-4 py-3">{{ $admin->username }}</td>
                        <td class="px-4 py-3">{{ $admin->email }}</td>
                        <td class="px-4 py-3"> 
                        @if($admin->status == 'active')
                            <span class="px-3 py-1 text-green-700 bg-green-200 rounded-full">Active</span>
                        @else
                            <span class="px-3 py-1 text-red-700 bg-red-200 rounded-full">Inactive</span>
                         {{ ucfirst($admin->status) }}

                        @endif                         
                         </td>
                        <td class="px-4 py-3 flex gap-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md"
                            onclick="viewAdminModal('{{ $admin->admin_id }}', '{{ $admin->username }}', '{{ $admin->email }}', '{{ $admin->profile_picture }}')">
                            👁 View
                        </button>
                            <button 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md
                            @if($cantManageDoctor) opacity-50 cursor-not-allowed @endif"
                            @if($cantManageDoctor) disabled @endif
                               onclick="openEditModal('{{ $admin->admin_id }}', '{{ $admin->username }}', '{{ $admin->email }}')">
                                ✏ Edit
                            </button>

                           <button 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md 
                            @if($cantManageDoctor) opacity-50 cursor-not-allowed @endif"
                            @if($cantManageDoctor) disabled @endif
                            wire:click="openStatusModal('{{ $admin->admin_id }}', '{{ $admin->username }}', '{{ $admin->status }}')"
                             >
                            🔄 Status
                        </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <!-- No Results Found -->
            <div class="text-center py-8">
                <div class="text-gray-500 text-lg mb-2">📋 No admins found</div>
                <p class="text-gray-400">
                    @if($search || $statusFilter !== 'all')
                        Try adjusting your search or filter criteria.
                    @else
                        No admins have been added yet.
                    @endif
                </p>
                @if($search || $statusFilter !== 'all')
                <button wire:click="clearFilters" 
                        class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Clear Filters
                </button>
                @endif
            </div>
            @endif

            <!-- Pagination (existing code remains the same) -->
            <div class="flex justify-between items-center p-4">
                <span class="text-sm text-gray-600">
                    Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }} of {{ $admins->total() }} rows
                </span>
                <div class="flex items-center space-x-2">
                    <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($admins->onFirstPage()) disabled @endif>
                        «
                    </button>
                    @foreach ($admins->getUrlRange(1, $admins->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($admins->currentPage() == $page) bg-blue-500 text-white @endif">
                            {{ $page }}
                        </button>
                    @endforeach
                    <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$admins->hasMorePages()) disabled @endif>
                        »
                    </button>
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


            <!-- View User Modal -->
            <div id="viewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <div class="flex flex-col items-center mb-4">
                        <img id="viewAdminProfilePic" 
                             class="w-32 h-32 rounded-full object-cover mb-4" 
                             alt="Admin Profile Picture">
                        <h2 class="text-gray-900 text-xl font-semibold" id="viewAdminFullName"></h2>
                    </div>
                    <div class="text-center">
                        <p><strong>Username:</strong> <span id="viewAdminUsername"></span></p>
                        <p><strong>Email:</strong> <span id="viewAdminEmail"></span></p>
                    </div>
                    <div class="mt-4 text-center">
                        <button class="px-4 py-2 bg-gray-600 text-white rounded-md" onclick="closeViewModal()">Close</button>
                    </div>
                </div>
            </div>


            <!-- Edit Admin Modal -->
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-gray-900 text-xl font-semibold mb-4">Edit Admin</h2>
                <form id="editAdminForm" action="{{ url('/admin-profile/update') }}" method="POST">
                    @csrf
                    @method('PUT') <!-- This tells Laravel to treat it as a PUT request -->

                    <input type="hidden" id="editAdminId" name="admin_id">

                    <div class="mb-3">
                        <label for="editAdminUsername" class="block text-gray-700">Username:</label>
                        <input type="text" id="editAdminUsername" name="username" class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <div class="mb-3">
                        <label for="editAdminEmail" class="block text-gray-700">Email:</label>
                        <input type="email" id="editAdminEmail" name="email" class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <div class="mb-3">
                        <label for="editAdminPassword" class="block text-gray-700">New Password:</label>
                        <input type="password" id="editAdminPassword" name="password" class="w-full px-3 py-2 border rounded-md">
                        <small class="text-gray-500">Leave blank if you don’t want to change the password.</small>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded-md" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
                    @if ($isStatusModalOpen)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-gray-900 text-xl font-semibold mb-4">Change Admin Status</h2>
                    <p class="text-gray-700 mb-4">Are you sure you want to change the status of <span class="font-bold">{{ $statusUsername }}</span>?</p>
                    
                    <div class="mb-4">
                        <label for="statusSelect" class="block text-gray-700 mb-2">Select Status:</label>
                        <select wire:model="newStatus" class="w-full px-3 py-2 bg-gray-200 text-gray-900 rounded-md border border-gray-400">
                            <option value="active">Active</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button wire:click="closeStatusModal" class="px-4 py-2 bg-gray-600 text-white rounded-md">Cancel</button>
                        <button wire:click="updateStatus" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">Save</button>
                    </div>
                </div>
            </div>
            @endif
<script>
    // For Livewire v3
    document.addEventListener('status-updated', event => {
        Swal.fire({
            icon: 'success',
            title: 'Status Updated',
            text: 'The doctor status was updated successfully.',
            confirmButtonColor: '#3085d6',
        });
    });

</script>

</div>
<script>
    // View User Modal
    function viewAdminModal(admin_id, username, email, profilePicture) {
        // Log the received profile picture URL for debugging
        console.log("Received Profile Picture URL:", profilePicture);

        fetch(`/admin-profile/${admin_id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.admin_id === "Not found") {
                    alert("Admin profile not found.");
                    return;
                }

                // Set profile picture
                const viewAdminProfilePic = document.getElementById('viewAdminProfilePic');
                
                // Prioritize the passed profilePicture, then data.profile_picture
                const picToUse = profilePicture || data.profile_picture;
                
                // Log the picture being used
                console.log("Picture being used:", picToUse);

                // Ensure the full URL is used
                viewAdminProfilePic.src = picToUse;
                
                // Set other details
                document.getElementById('viewAdminFullName').innerText = username;
                document.getElementById('viewAdminUsername').innerText = data.username;
                document.getElementById('viewAdminEmail').innerText = data.email;

                // Show the modal
                document.getElementById('viewModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching user profile:', error);
                alert('Failed to fetch user profile.');
            });
    }


    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }

    // Open Edit Admin Modal
    function openEditModal(admin_id, username, email) {
        document.getElementById('editAdminId').value = admin_id;
        document.getElementById('editAdminUsername').value = username;
        document.getElementById('editAdminEmail').value = email;
        document.getElementById('editAdminPassword').value = ''; // Always empty

        document.getElementById('editModal').classList.remove('hidden');
    }


    // Close Edit Admin Modal
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Submit Edit Form
document.getElementById('editAdminForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const admin_id = document.getElementById('editAdminId').value;
    const username = document.getElementById('editAdminUsername').value;
    const email = document.getElementById('editAdminEmail').value;
    const password = document.getElementById('editAdminPassword').value;

    fetch(`/admin-profile/update/${admin_id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ username, email, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Admin updated successfully!");
            location.reload(); // Refresh the page
        } else {
            alert("Failed to update admin!");
        }
    })
    .catch(error => {
        console.error("Error updating admin:", error);
        alert("An error occurred while updating admin.");
    });

    closeEditModal();
});


    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#adminTable tr");
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

    // Get current filter values (adjust these selectors based on your actual HTML structure)
    const searchValue = document.querySelector('input[wire\\:model="search"]') ? 
        document.querySelector('input[wire\\:model="search"]').value : '';
    const statusFilter = '{{ $statusFilter ?? "all" }}';
    const roleFilter = '{{ $roleFilter ?? "all" }}';
    
    // Build URL with parameters
    const params = new URLSearchParams();
    params.append('format', format);
    
    if (searchValue) {
        params.append('search', searchValue);
    }
    if (statusFilter && statusFilter !== 'all') {
        params.append('status', statusFilter);
    }
    if (roleFilter && roleFilter !== 'all') {
        params.append('role', roleFilter);
    }
    
    const downloadUrl = `{{ route('admins.export') }}?${params.toString()}`;

    // Use fetch for better error handling
    fetch(downloadUrl, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        
        // Check content type
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('text/csv')) {
            return response.text().then(text => {
                console.error('Unexpected response:', text);
                throw new Error('Invalid response format - expected CSV');
            });
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
        const filename = format === 'excel' ? `Admins_${dateStr}.csv` : `Admins_${dateStr}.csv`;
        a.download = filename;
        
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
        
        // Show detailed error alert
        Swal.fire({
            title: 'Download Failed',
            text: `Error: ${error.message}. Please check the console for more details.`,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}
    </script>
