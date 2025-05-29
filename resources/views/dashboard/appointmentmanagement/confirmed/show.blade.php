@extends('layouts.app')
@section('title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <div class="border-b border-gray-700 pb-4">
        <h1 class="text-3xl font-bold text-white">Appointment Details</h1>
    </div>

    <!-- Unified Details Box -->
    <div class="bg-white p-6 rounded-lg shadow-lg space-y-6">
        <!-- Basic Information -->
        <div>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Basic Information</h2>
            <div class="space-y-4">
                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Name:</span>
                    <span class="text-gray-800">
                        {{ $appointment->user ? $appointment->user->first_name . ' ' . $appointment->user->last_name : 'Unknown User' }}
                    </span>
                </div>
                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Email:</span>
                    <span class="text-gray-800">{{ $appointment->umak_email }}</span>
                </div>

                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Service:</span>
                    <span class="text-gray-800">{{ $appointment->service }}</span>
                </div>

                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Service Type:</span>
                    <span class="text-gray-800">{{ $appointment->service_type }}</span>
                </div>

                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        @if($appointment->status == 'Approved') bg-green-100 text-green-800 
                        @elseif($appointment->status == 'Completed') bg-blue-100 text-blue-800 
                        @elseif($appointment->status == 'No Show') bg-red-100 text-red-800 
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $appointment->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Appointment Schedule -->
        <div>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Appointment Schedule</h2>
            <div class="space-y-4">
                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Date:</span>
                    <span class="text-gray-800">{{ \Carbon\Carbon::parse($appointment->booking_date)->format('F j, Y') }}</span>
                </div>

                <div class="flex border-b border-gray-200 pb-3">
                    <span class="font-medium text-gray-600 w-1/3">Time:</span>
                    <span class="text-gray-800">{{ \Carbon\Carbon::parse($appointment->booking_time)->format('g:i A') }}</span>
                </div>

                <div class="mt-6">
                    <h3 class="font-medium text-gray-600 mb-2">Remarks:</h3>
                    <div class="bg-gray-50 p-4 rounded border border-gray-200 min-h-[100px]">
                        {{ $appointment->remarks ?: 'No remarks provided.' }}
                    </div>
                </div>
            </div>
        </div>

      <!-- Vital Signs Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg space-y-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Vital Signs</h2>

            @if ($appointment->vitalSigns)
                <div id="vitalSignsDisplay" class="space-y-3">
                    <div class="flex justify-between items-center">
                        <p><strong>Attending Nurse:</strong> {{ $appointment->vitalSigns->attending_nurse }}</p>
                        <button onclick="document.getElementById('vitalSignsForm').classList.remove('hidden'); document.getElementById('vitalSignsDisplay').classList.add('hidden');" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-2 rounded">Edit</button>
                    </div>
                    <p><strong>Height:</strong> {{ $appointment->vitalSigns->height_cm }} cm</p>
                    <p><strong>Weight:</strong> {{ $appointment->vitalSigns->weight_kg }} kg</p>
                    <p><strong>Blood Pressure:</strong> {{ $appointment->vitalSigns->blood_pressure }}</p>
                    <p><strong>Temperature:</strong> {{ $appointment->vitalSigns->temperature_c }} °C</p>
                    <p><strong>Notes:</strong> {{ $appointment->vitalSigns->notes }}</p>
                </div>
            @else
                <p class="text-gray-600">No vital signs recorded yet.</p>
            @endif

            <form id="vitalSignsForm" method="POST" action="{{ $appointment->vitalSigns ? route('vital-signs.update', $appointment->booking_id) : route('vital-signs.store') }}" class="{{ $appointment->vitalSigns ? 'hidden' : '' }}">
                @csrf
                @if ($appointment->vitalSigns)
                    @method('PUT')
                @endif

                <input type="hidden" name="booking_id" value="{{ $appointment->booking_id }}">
                <input type="hidden" name="attending_nurse" value="{{ Auth::guard('admin')->user()->name }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-600">Height (cm)</label>
                        <input type="number" step="0.01" name="height_cm" value="{{ old('height_cm', optional($appointment->vitalSigns)->height_cm) }}" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-600">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg', optional($appointment->vitalSigns)->weight_kg) }}" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-600">Blood Pressure</label>
                        <input type="text" name="blood_pressure" value="{{ old('blood_pressure', optional($appointment->vitalSigns)->blood_pressure) }}" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-600">Temperature (°C format: 37.0)</label>
                        <input type="number" step="0.01" name="temperature_c" value="{{ old('temperature_c', optional($appointment->vitalSigns)->temperature_c) }}" class="w-full border rounded p-2" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-600">Notes</label>
                        <textarea name="notes" rows="3" class="w-full border rounded p-2">{{ old('notes', optional($appointment->vitalSigns)->notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded">
                        {{ $appointment->vitalSigns ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>


        <!-- Medical Records Section -->
<div class="bg-white p-6 rounded-lg shadow-lg space-y-6 mt-6">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Medical Records</h2>

    @if ($appointment->medicalRecord)
        <div id="medicalRecordDisplay" class="space-y-3">
            <div class="flex justify-between items-center">
                <p><strong>Attending Doctor:</strong> {{ $appointment->medicalRecord->doctor }}</p>
                <button onclick="document.getElementById('medicalRecordForm').classList.remove('hidden'); document.getElementById('medicalRecordDisplay').classList.add('hidden');" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-2 rounded">Edit</button>
            </div>
            <div>
                <p class="font-medium text-gray-600 mb-1">Complaint:</p>
                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    {!! nl2br(e($appointment->medicalRecord->complaint)) !!}
                </div>
            </div>

            <div>
                <p class="font-medium text-gray-600 mb-1">Diagnosis:</p>
                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    {!! nl2br(e($appointment->medicalRecord->diagnosis)) !!}
                </div>
            </div>
            <div>
                <p class="font-medium text-gray-600 mb-2">Prescription:</p>
                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    {!! nl2br(e($appointment->medicalRecord->prescription ?? 'No prescription provided.')) !!}
                </div>
            </div>
            <div>
                <p class="font-medium text-gray-600 mb-2">Notes:</p>
                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    {!! nl2br(e($appointment->medicalRecord->notes ?? 'No additional notes.')) !!}
                </div>
            </div>
        </div>
    @else
        <p class="text-gray-600">No medical records available yet.</p>
    @endif

    <form id="medicalRecordForm" method="POST" action="{{ $appointment->medicalRecord ? route('medical-records.update', $appointment->booking_id) : route('medical-records.store') }}" class="{{ $appointment->medicalRecord ? 'hidden' : '' }}">
        @csrf
        @if ($appointment->medicalRecord)
            @method('PUT')
        @endif

        <input type="hidden" name="booking_id" value="{{ $appointment->booking_id }}">
        <input type="hidden" name="doctor" value="{{ Auth::guard('admin')->user()->name }}">

        <div class="space-y-4">
            <div>
                <label class="block text-gray-600 mb-2">Complaint</label>
                <textarea name="complaint" rows="4" class="w-full border rounded p-2" required>{{ old('complaint', optional($appointment->medicalRecord)->complaint) }}</textarea>
            </div>
            <div>
                <label class="block text-gray-600 mb-2">Diagnosis</label>
                <textarea name="diagnosis" rows="4" class="w-full border rounded p-2" required>{{ old('diagnosis', optional($appointment->medicalRecord)->diagnosis) }}</textarea>
            </div>
            <div>
                <label class="block text-gray-600 mb-2">Prescription</label>
                <textarea name="prescription" rows="4" class="w-full border rounded p-2">{{ old('prescription', optional($appointment->medicalRecord)->prescription) }}</textarea>
            </div>
            <div>
                <label class="block text-gray-600 mb-2">Additional Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded p-2">{{ old('notes', optional($appointment->medicalRecord)->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded">
                {{ $appointment->medicalRecord ? 'Update' : 'Save' }}
            </button>
        </div>
    </form>
</div>


<!-- Add Follow-up Appointment Button -->
<div class="mt-6">
    <button id="showFollowUpModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Schedule Follow-up Appointment
    </button>
</div>

<div id="followUpDetails" class="mt-4 p-4 border rounded bg-green-50 text-red-800">
    <h4 class="font-semibold mb-2">Follow-up Appointment Scheduled</h4>
    <p><strong>Date:</strong> <span id="followUpDate"></span></p>
    <p><strong>Time:</strong> <span id="followUpTime"></span></p>
</div>

<!-- Follow-up Appointment Modal -->
<div id="followUpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Schedule Follow-up Appointment</h3>
            <button id="closeFollowUpModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="followUpForm" method="POST" action="{{ route('appointments.followup') }}">
            @csrf
            <input type="hidden" name="umak_email" value="{{ $appointment->umak_email }}">
            <input type="hidden" name="service" value="{{ $appointment->service }}">
            <input type="hidden" name="service_type" value="{{ $appointment->service_type }}">
            <input type="hidden" name="status" value="Approved">
            <input type="hidden" name="remarks" value="For Follow-up">
            
            <div class="space-y-4">
                <!-- Date Picker -->
                <div>
                    <label for="booking_date" class="block text-gray-700 mb-1">Select Date</label>
                    <input type="date" id="booking_date" name="booking_date" class="w-full border rounded p-2" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                
                <!-- Time Selection -->
                <div>
                    <label for="booking_time" class="block text-gray-700 mb-1">Select Time</label>
                    <select id="booking_time" name="booking_time" class="w-full border rounded p-2" required>
                        <option value="">Select a time</option>
                        <!-- Morning slots -->
                        <option value="08:00:00">8:00 AM - 9:00 AM</option>
                        <option value="09:00:00">9:00 AM - 10:00 AM</option>
                        <option value="10:00:00">10:00 AM - 11:00 AM</option>
                        <option value="11:00:00">11:00 AM - 12:00 PM</option>
                        <!-- Afternoon slots -->
                        <option value="13:00:00">1:00 PM-2:00 PM</option>
                        <option value="14:00:00">2:00 PM-3:00 PM</option>
                        <option value="15:00:00">3:00 PM-4:00 PM</option>
                        <option value="16:00:00">4:00 PM - 5:00 PM</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancelFollowUp" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Save Appointment
                </button>
            </div>
        </form>
    </div>
</div>
        <!-- End of Medical Records Section -->
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('appointments.confirmed') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Appointments
            </a>
        </div>
    </div>
</div>

<!-- SweetAlert script for showing success messages -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    });
</script>
@endif
@endsection

<!-- JavaScript for Follow-up Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showFollowUpModalBtn = document.getElementById('showFollowUpModal');
        const followUpModal = document.getElementById('followUpModal');
        const closeFollowUpModalBtn = document.getElementById('closeFollowUpModal');
        const cancelFollowUpBtn = document.getElementById('cancelFollowUp');
        
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('booking_date').min = tomorrow.toISOString().split('T')[0];
        
        // Open modal
        showFollowUpModalBtn.addEventListener('click', function() {
            followUpModal.classList.remove('hidden');
        });
        
        // Close modal
        function closeModal() {
            followUpModal.classList.add('hidden');
        }
        
            fetchFollowUpAppointments();

        closeFollowUpModalBtn.addEventListener('click', closeModal);
        cancelFollowUpBtn.addEventListener('click', closeModal);
        
        // Close modal if clicked outside
        followUpModal.addEventListener('click', function(event) {
            if (event.target === followUpModal) {
                closeModal();
            }
        });
    });
</script>

<script>
async function fetchFollowUpAppointments() {
    try {
        // Get the email from the current page
        const email = "{{ $appointment->umak_email }}";
        
        console.log('Fetching follow-ups for:', email);
        const response = await fetch(`/appointments/followups/${email}`);
        
        if (!response.ok) throw new Error('Network response was not ok');

        const appointments = await response.json();
        console.log('Follow-up appointments:', appointments);

        const followUpContainer = document.getElementById('followUpDetails');
        const followUpDateSpan = document.getElementById('followUpDate');
        const followUpTimeSpan = document.getElementById('followUpTime');

        if (appointments.length === 0) {
            // Hide the container if no follow-ups
            followUpContainer.classList.add('hidden');
        } else {
            // Show just the first/next follow-up appointment
            const nextAppointment = appointments[0];
            
            // Format the date (assuming it's in YYYY-MM-DD format)
            const appointmentDate = new Date(nextAppointment.booking_date);
            const formattedDate = appointmentDate.toLocaleDateString('en-US', {
                year: 'numeric', 
                month: 'long', 
                day: 'numeric'
            });
            
            // Format the time (assuming it's in HH:MM:SS format)
            let timeString = nextAppointment.booking_time;
            if (timeString.includes(':')) {
                const timeParts = timeString.split(':');
                const hour = parseInt(timeParts[0]);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                timeString = `${hour12}:00 ${ampm}`;
            }
            
            followUpDateSpan.textContent = formattedDate;
            followUpTimeSpan.textContent = timeString;
            
            // Make sure it's visible
            followUpContainer.classList.remove('hidden');
        }

    } catch (error) {
        console.error('Error fetching follow-up appointments:', error);
    }
}
</script>
