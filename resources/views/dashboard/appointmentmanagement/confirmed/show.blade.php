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
                    <span class="text-gray-800">{{ $appointment->booking_time }}</span>
                </div>

                <div class="mt-6">
                    <h3 class="font-medium text-gray-600 mb-2">Remarks:</h3>
                    <div class="bg-gray-50 p-4 rounded border border-gray-200 min-h-[100px]">
                        {{ $appointment->remarks ?: 'No remarks provided.' }}
                    </div>
                </div>
            </div>
        </div>

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
@endsection
