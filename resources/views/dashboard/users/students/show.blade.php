@extends('layouts.app')
@section('title', 'User Details')

@section('content')
<div class="space-y-6">


    <div class="bg-white rounded-lg shadow-lg">
        <!-- Tabs Navigation -->
        <div class="flex border-b">
            <button id="tab-basic-info" onclick="switchTab('basic-info')" class="px-6 py-4 font-medium text-gray-800 border-b-2 border-blue-600 tab-active">Basic Information</button>
            <button id="tab-patient-records" onclick="switchTab('patient-records')" class="px-6 py-4 font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent">Patient Records</button>
        </div>



        <!-- Tab Content -->
        <div class="p-6 space-y-6">

            <!-- Basic Information Tab -->
            <div id="content-basic-info" class="tab-content">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start">
                
                    <div class="flex-1 space-y-4">
                        <!-- Student Name -->
                        <div>
                            <p class="text-gray-600 text-sm">Name</p>
                            <p class="text-2xl font-bold">{{ $student->first_name }} {{ $student->last_name }}</p>
                        </div>

                        <!-- UMAK ID -->
                        <div>
                            <p class="text-gray-600 text-sm">UMAK ID</p>
                            <p class="text-lg font-semibold">{{ $student->student_id }}</p>
                        </div>

                        <!-- UMak Email -->
                        <div>
                            <p class="text-gray-600 text-sm">UMAK Email</p>
                            <p class="text-lg font-semibold">{{ $student->umak_email }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <p class="text-gray-600 text-sm">Status</p>
                            <p class="text-lg font-semibold capitalize">{{ $student->status }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Role</p>
                            <p class="text-lg font-semibold capitalize">{{ $student->role }}</p>
                        </div>
                            <button onclick="document.getElementById('editModal').classList.remove('hidden'); document.getElementById('editModal').classList.add('flex');" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors justify-content:flex-end">
                            Edit User Basic Information
                        </button>


                    </div>

                    <!-- User Photo -->
                    <div class="mt-6 md:mt-0 md:ml-8">
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-gray-300 shadow-sm">
                            @if($student->profile && $student->profile->profile_image)
                                <img src="data:image/jpeg;base64,{{ base64_encode($student->profile->profile_image) }}" class="h-full w-full rounded-full object-cover">
                            @else
                                <img src="{{ asset('images/profile.png') }}" class="h-full w-full rounded-full object-cover">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-t border-gray-300 pt-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Contact Information</h2>

                    <div class="space-y-3 text-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">Contact Number:</span>
                            <span>{{ isset($profile) && trim($profile->contact_number ?? '') !== '' ? $profile->contact_number : 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Address:</span>
                            <span>{{ isset($profile) && trim($profile->address ?? '') !== '' ? $profile->address : 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Guardian Contact Number:</span>
                            <span>{{ isset($profile) && trim($profile->guardian_contact_number ?? '') !== '' ? $profile->guardian_contact_number : 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Guardian Address:</span>
                            <span>{{ isset($profile) && trim($profile->guardian_address ?? '') !== '' ? $profile->guardian_address : 'Not Provided' }}</span>
                        </div>
                    </div>

                </div>

                <div class="border-t border-gray-300 pt-6 mt-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Medical Information</h2>

    <div class="space-y-3 text-gray-700">
        <div class="flex justify-between">
            <span class="font-medium">Sex:</span>
            <span>{{ $medical->sex ?? 'Not Provided' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium">Blood Type:</span>
            <span>{{ $medical->blood_type ?? 'Not Provided' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium">Allergies:</span>
            <span>{{ $medical->allergies ?? 'None Reported' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium">Medical Conditions:</span>
            <span>{{ $medical->medical_conditions ?? 'None Reported' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium">Medications:</span>
            <span>{{ $medical->medications ?? 'None Reported' }}</span>
        </div>
    </div>

</div>


            </div>

        <!-- Bookings History Tab (Initially Hidden) -->
            <div id="content-patient-records" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Bookings History</h2>
                
                @livewire('student-bookings-table')
            </div>
        </div>


        <!-- Back Button -->
        <div class="flex justify-start p-6 pt-0">
            <a href="{{ route('users.students.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
        </div>
    </div>


    <!-- Edit Student Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Edit Student Details</h3>
                <button onclick="document.getElementById('editModal').classList.remove('flex'); document.getElementById('editModal').classList.add('hidden');" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editStudentForm" action="{{ route('dashboard.users.students.update') }}" method="POST">
                @csrf
                @method('PUT')

                            {{-- @if(isset($student))
                <!-- Debug info - remove in production -->
                <div class="bg-gray-100 p-2 mb-4 rounded">
                    Debug: Student ID (PK): {{ $student->user_id }}<br>
                    Student ID Field: {{ $student->student_id }}
                </div> --}}
        
                <input type="hidden" name="user_id" value="{{ $student->user_id }}">
                    {{-- @else
                        <div class="bg-red-100 p-2 mb-4 rounded">
                            Error: Student variable not available!
                        </div>
                    @endif --}}

                
                <div class="space-y-4">
                    <input type="hidden" name="id" value="{{ $student->user_id }}">
                    <!-- Student ID -->
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" name="student_id" id="student_id" value="{{ $student->student_id }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ $student->first_name }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ $student->last_name }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
@endif



</div>
<!-- JavaScript for Tab Switching -->
<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Update tab button styles
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.classList.remove('border-blue-600', 'text-gray-800');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-' + tabName).classList.add('border-blue-600', 'text-gray-800');
        
        // If patient-records tab is selected, load the bookings data
        if (tabName === 'patient-records') {
            Livewire.dispatch('loadStudentBookings', {
                email: '{{ $student->umak_email }}'
            });
        }


           // Modal handling
    function openEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    });

    }
</script>
@endsection