    @extends('layouts.app')

    @section('title', 'Profile')

    @section('content')
    <div class="space-y-5 ml-10">
        <!-- Title -->
        <div class="border-b border-gray-700 pb-3">
            <?php
                if($admin->role == 'admin') {
                    $header = 'Admin Profile';
                } elseif($admin->role == 'doctor') {
                    $header = 'Doctor Profile';
                } else if ($admin->role == 'nurse') {
                    $header = 'Nurse Profile';
                }else{
                    $header = 'Admin Profile';
                }
            ?>
            <h1 class="text-2xl font-bold text-white">{{ $header }}</h1>
        </div>

        <!-- Error Message Display -->
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Profile Container -->
        <div class="flex items-center space-x-6 bg-white shadow-lg rounded-xl p-6 w-full">
            <!-- Profile Image -->
            <div class="flex flex-col items-center space-y-3">
                <div class="w-40 h-40 rounded-full border-4 border-gray-400 overflow-hidden">
                    <img src="{{ $admin->profile_picture ?? 'https://via.placeholder.com/150' }}" alt="Profile Picture" class="w-full h-full object-cover rounded-full">
                </div>

                <!-- Upload Button -->
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_picture" id="fileInput" class="hidden" onchange="this.form.submit()">
                    <label for="fileInput" class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                        Upload Image
                    </label>
                </form>
            </div>

            <!-- Profile Info -->
            <div class="bg-gray-100 p-4 rounded-lg shadow w-2/3">
                <!-- Name and Title -->
                <p class="text-xl font-bold text-gray-800">
                    {{ $admin->title ? $admin->title . '.' : '' }} {{ $admin->name ? '' . $admin->name : 'No name provided' }}
                </p>
                <p class="text-xl font-bold text-gray-600">{{ $admin->username }}</p>
                <p class="text-md text-gray-600">{{ $admin->email ?? 'No email provided' }}</p>
                <p class="text-md text-gray-600">{{ $admin->role ?? 'admin' }}</p>

                <!-- Edit Button -->
                <button onclick="document.getElementById('editForm').classList.toggle('hidden')" 
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                    Edit Info
                </button>

                <!-- Edit Form -->
                <form id="editForm" action="{{ route('admin.profile.updateInfo') }}" method="POST" class="space-y-3 mt-4 hidden">
                    @csrf
                    <div>
                        <label class="block text-gray-700 font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium">Title</label>
                        <input type="text" name="title" value="{{ old('title', $admin->title) }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        Save Changes
                    </button>
                </form>
            </div>
            


        </div>

            <!-- Livewire Appointment Section -->
    <div class="mt-8">
        @if($admin->role == 'doctor')
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4"> Attended Appointments</h2>
                @livewire('doctor-appointments')
            </div>
        @elseif($admin->role == 'nurse').
            <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4"> Attended Appointments</h2>
            @livewire('nurse-appointments')
                        </div>

        @else
            <p>No specific table for this role.</p>
        @endif
    </div>
    
    </div>

    <!-- Success Alert -->
    @if(session('info_updated'))
        <script>
            Swal.fire({
                icon: '{{ session('info_updated')['type'] }}',
                title: '{{ session('info_updated')['title'] }}',
                text: '{{ session('info_updated')['text'] }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        </script>
    @endif

    @endsection
