@extends('layouts.app')
@section('title', 'Student Details')

@section('content')
<div class="space-y-6">
    <div class="border-b border-gray-700 pb-4">
        <h1 class="text-3xl font-bold text-white">Student Details</h1>
    </div>

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
                            <p class="text-gray-600 text-sm">Student Name</p>
                            <p class="text-2xl font-bold">{{ $student->first_name }} {{ $student->last_name }}</p>
                        </div>

                        <!-- Student ID -->
                        <div>
                            <p class="text-gray-600 text-sm">Student ID</p>
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
                            <span>{{ $profile->contact_number ?? 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Address:</span>
                            <span>{{ $profile->address ?? 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Guardian Contact Number:</span>
                            <span>{{ $profile->guardian_contact_number ?? 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Guardian Address:</span>
                            <span>{{ $profile->guardian_address ?? 'Not Provided' }}</span>
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
                Back to Students
            </a>
        </div>
    </div>
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
    }
</script>
@endsection