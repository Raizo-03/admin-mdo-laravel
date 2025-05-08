<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Nurses</h1>
    <p class="text-gray-400 mb-4">All list of Nurses</p>

    <!-- Search & Filter -->
    <div class="flex items-center gap-4 mb-4">
        <input type="text" id="searchInput" placeholder="Search admins..." class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white">
    </div>

            <!-- Add Nurse Button -->
@php
    $cantAddNurse = $user->role === 'doctor' || $user->role === 'nurse';
    $cantManageDoctor = $user->role === 'doctor' || $user->role === 'nurse';
@endphp

<div class="mb-4 text-left">
    <button 
        wire:click="openModal"
        class="px-4 py-2 bg-blue-600 text-white rounded-md 
        @if($cantAddNurse) opacity-50 cursor-not-allowed @endif"
        @if($cantAddNurse) disabled @endif
    >
        + Add Nurse
    </button>
</div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="w-full border-collapse text-gray-900">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-left">
                        <th class="px-4 py-3">Profile</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
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
                        <td class="px-4 py-3">{{ $admin->title. ". ". $admin->name }}</td>
                        <td class="px-4 py-3">{{ $admin->username }}</td>
                        <td class="px-4 py-3">{{ $admin->email }}</td>
                        <td class="px-4 py-3 flex gap-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md"
                            onclick="viewAdminModal('{{ $admin->admin_id }}', '{{$admin->title}}', '{{$admin->name}}','{{ $admin->username }}', '{{ $admin->email }}', '{{ $admin->profile_picture }}')">
                            👁 View
                        </button>
                           <button 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md 
                            @if($cantManageDoctor) opacity-50 cursor-not-allowed @endif"
                            @if($cantManageDoctor) disabled @endif
                            onclick="openEditModal('{{ $admin->admin_id }}', '{{ $admin->username }}', '{{ $admin->email }}')"
                        >
                            ✏ Edit
                        </button>

                        <button 
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md 
                            @if($cantManageDoctor) opacity-50 cursor-not-allowed @endif"
                            @if($cantManageDoctor) disabled @endif
                            wire:click="openDeleteModal('{{ $admin->admin_id }}', '{{ $admin->username }}', '{{ $admin->email }}')"
                        >
                            X Delete
                        </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

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
                        <p><strong>Name:</strong> <span id="viewAdminName"></span></p>
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
                <h2 class="text-gray-900 text-xl font-semibold mb-4">Edit Nurse</h2>
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

          <!-- Add Nurse Modal -->
    <div class="{{ $isModalOpen ? '' : 'hidden' }} fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-gray-900 text-xl font-semibold mb-4">Add Nurse</h2>
            <form wire:submit.prevent="addNurse">
                <div class="mb-3">
                    <label for="username" class="block text-gray-700">Username:</label>
                    <input type="text" id="username" wire:model="username" class="w-full px-3 py-2 border rounded-md">
                    @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" wire:model="email" class="w-full px-3 py-2 border rounded-md">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" wire:model="password" class="w-full px-3 py-2 border rounded-md">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4 text-right">
                    <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded-md" wire:click="closeModal">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Add Nurse</button>
                </div>
            </form>
        </div>
    </div>


    @if ($isDeleteModalOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold text-red-600">Confirm Delete</h2>
            <p class="mt-2">Are you sure you want to delete <strong>{{ $deleteUsername }}</strong>'s account?</p>
            
            <div class="flex justify-end mt-4 space-x-2">
                <button wire:click="closeDeleteModal" class="bg-gray-400 px-3 py-1 rounded-md">Cancel</button>
                <button wire:click="deleteAdmin" class="bg-red-600 text-white px-3 py-1 rounded-md">Delete</button>
            </div>
        </div>
    </div>
@endif

</div>
<script>
    // View User Modal
    function viewAdminModal(admin_id, title, name, username, email, profilePicture) {
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
                 document.getElementById('viewAdminName').innerText = title + ". " + name;
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
    // Open the Add Doctor Modal
// Open Add Doctor Modal
function openAddDoctorModal() {
    // Clear the modal fields before opening it
    document.getElementById('addDoctorUsername').value = '';
    document.getElementById('addDoctorEmail').value = '';
    document.getElementById('addDoctorPassword').value = ''; // Clear password field

    // Show the modal
    document.getElementById('addDoctorModal').classList.remove('hidden');
}



    // Close the Add Doctor Modal
    function closeAddDoctorModal() {
        document.getElementById('addDoctorModal').classList.add('hidden');
    }
</script>