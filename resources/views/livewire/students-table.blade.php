<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-4">Students</h1>
    <p class="text-gray-400 mb-4">All list of students</p>

    <!-- Search & Filter -->
    <div class="flex items-center gap-4 mb-4">
        <input type="text" id="searchInput" placeholder="Search students..." class="w-full px-4 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-900 text-white">
        <select id="statusFilter" class="px-4 py-2 border border-gray-600 rounded-md bg-gray-900 text-white">
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <!-- Table Box -->
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="overflow-x-auto p-4">
        <table class="w-full border-collapse text-gray-900">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-left">
                    <th class="px-4 py-3">Student ID</th>
                    <th class="px-4 py-3">First Name</th>
                    <th class="px-4 py-3">Last Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="studentTable">
                @foreach($students as $student)
                <tr class="border-b border-gray-300 hover:bg-gray-100 transition duration-200">
                    <td class="px-4 py-3">{{ $student->student_id }}</td>
                    <td class="px-4 py-3">{{ $student->first_name }}</td>
                    <td class="px-4 py-3">{{ $student->last_name }}</td>
                    <td class="px-4 py-3">{{ $student->umak_email }}</td>
                    <td class="px-4 py-3">
                        @if($student->status == 'active')
                            <span class="px-3 py-1 text-green-700 bg-green-200 rounded-full">Active</span>
                        @else
                            <span class="px-3 py-1 text-red-700 bg-red-200 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md"
                            onclick="viewUserModal('{{ $student->id }}', '{{ $student->student_id }}', '{{ $student->first_name }}', '{{ $student->last_name }}', '{{ $student->umak_email }}', '{{ $student->status }}')">
                            👁 View
                        </button>
                        <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md"
                            onclick="openStatusModal('{{ $student->id }}', '{{ $student->first_name }}', '{{ $student->status }}', '{{ $student->student_id }}')">
                            ⚡ Change Status
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination inside Livewire Component -->
<div class="flex justify-between items-center p-4">
    <span class="text-sm text-gray-600">
        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} rows
    </span>
    <div class="flex items-center space-x-2">
        <button wire:click="previousPage" class="px-4 py-2 border rounded-md hover:bg-gray-200" @if($students->onFirstPage()) disabled @endif>
            «
        </button>
        @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
            <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 border rounded-md hover:bg-gray-200 @if($students->currentPage() == $page) bg-blue-500 text-white @endif">
                {{ $page }}
            </button>
        @endforeach
        <button wire:click="nextPage" class="px-3 py-2 border rounded-md hover:bg-gray-200" @if(!$students->hasMorePages()) disabled @endif>
            »
        </button>
    </div>
</div>


    </div>
</div>


    <!-- View User Modal -->
    <div id="viewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-gray-900 text-xl font-semibold mb-4">Student Details</h2>
            <p><strong>Student ID:</strong> <span id="viewStudentId"></span></p>
            <p><strong>Name:</strong> <span id="viewStudentName"></span></p>
            <p><strong>Email:</strong> <span id="viewStudentEmail"></span></p>
            <p><strong>Status:</strong> <span id="viewStudentStatus"></span></p>
            <div class="mt-4 text-right">
                <button class="px-4 py-2 bg-gray-600 text-white rounded-md" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div id="statusModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-gray-900 text-xl font-semibold mb-4">Change User Status</h2>
            <p class="text-gray-700 mb-4">Are you sure you want to change the status of <span id="statusUserName" class="font-bold"></span>?</p>
                <form action="{{ route('users.updateStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" id="statusStudentId" name="student_id">
                    <select id="statusSelect" name="status" class="w-full px-3 py-2 bg-gray-200 text-gray-900 rounded-md border border-gray-400">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded-md" onclick="closeStatusModal()">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">Save</button>
                    </div>
                </form>


        </div>
    </div>
</div>



<!-- JavaScript -->
<script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#studentTable tr");
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    // Filter Functionality
    document.getElementById('statusFilter').addEventListener('change', function () {
        let filter = this.value;
        let rows = document.querySelectorAll("#studentTable tr");
        rows.forEach(row => {
            let status = row.querySelector("td:nth-child(5)").innerText.toLowerCase();
            if (filter === "all" || status.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // View User Modal
 function viewUserModal(id, studentId, firstName, lastName, email, status) {
    document.getElementById('viewStudentId').innerText = studentId;
    document.getElementById('viewStudentName').innerText = firstName + " " + lastName;
    document.getElementById('viewStudentEmail').innerText = email;
    
    // Ensure correct status is displayed
    document.getElementById('viewStudentStatus').innerText = status == "active" ? "Active" : "Inactive";
    
    document.getElementById('viewModal').classList.remove('hidden');
}

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }

    // Change Status Modal
    function openStatusModal(userId, name, status, studentId) {
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('statusUserName').innerText = name;
        document.getElementById('statusSelect').value = status;
        document.getElementById('statusStudentId').value = studentId; // Set student_id correctly
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>
