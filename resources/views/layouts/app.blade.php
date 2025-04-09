<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
     @livewireStyles
     <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<body class="bg-[#111C4E] min-h-screen">

<nav class="bg-[#111C4E] p-4 text-white text-[20px] flex justify-between items-center fixed top-0 w-full z-10" style="font-family: 'Arial', sans-serif;">
    <!-- Left: UMAK Medical and Dental Clinic with an Icon -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 ml-4">
        <img src="{{ asset('images/umak_logo.png') }}" alt="Clinic Logo" class="w-8 h-8">
        UMAK Medical and Dental Clinic
    </a>

    <!-- Right: Hi, Admin with an Icon -->
        @php
            $admin = Auth::guard('admin')->user();
        @endphp

        <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 mr-3">
            <img 
                src="{{ $admin->profile_picture ? asset($admin->profile_picture) : asset('images/profile.png') }}" 
                alt="Admin Icon" 
                class="w-8 h-8 object-cover rounded-full"
            >
            Hi, {{ $admin->username }}
        </a>
</nav>

<div class="flex pt-16"> <!-- Added padding top to account for fixed navbar -->
    <!-- Sidebar - fixed position with full height and overflow scroll -->
    <aside class="bg-[#111C4E] text-white w-64 fixed h-[calc(100vh-4rem)] overflow-y-auto top-16 left-0">
        <div class="p-6">
            <h2 class="text-[17px] font-bold mb-3">Dashboard</h2>
            <nav class="flex flex-col gap-3 mb-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/dashboard_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Home
                </a>
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile Icon" class="w-5 h-5">
                    Profile
                </a>

            </nav>

            <h2 class="text-[17px] font-bold mb-3">User Management</h2>
            <nav class="flex flex-col gap-3 mb-6">
                <a href="{{ route('users.students.index') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/usermanager_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Students
                </a>
                <a href="{{ route('users.admins.index') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/inactive_user.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Admins
                </a>
                  <a href="{{ route('users.doctors.index') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/inactive_user.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Doctors
                </a>
                 <a href="{{ route('users.nurses.index') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/inactive_user.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Nurses
                </a>
            </nav>

            <h2 class="text-[16px] font-bold mb-3">Appointment Management</h2>
            <nav class="flex flex-col gap-3 mb-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/confirmed.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Confirmed
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/completed.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Completed
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/no_show.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    No Show
                </a>
            </nav>

            <h2 class="text-[17px] font-bold mb-3">Content Management</h2>
            <nav class="flex flex-col gap-3 mb-6">
                <a href="{{ route('admin.chat') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/chat_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Chat
                </a>
                <a href="{{ route('feedback.index') }}"class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/feedback.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Feedback
                </a>
                <a href="{{ route('announcement.index') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/announcement.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Announcements
                </a>
                    <a href="{{ route('trivia.index') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded">
                    <img src="{{ asset('images/trivia_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                    Trivia
                </a>
            </nav>

            <!-- Logout Button (Opens Modal) -->
            <h2 class="text-[17px] mb-3 hover:bg-[#89a0df] p-1 rounded cursor-pointer" onclick="showLogoutModal()">
                Logout
            </h2>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="ml-64 w-full p-6 min-h-screen bg-[#111C4E] pb-16">
        @yield('content')
    </div>
</div>

<!-- Logout Confirmation Modal (Initially Hidden) -->
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h3 class="text-xl font-semibold mb-4 text-black">Are you sure you want to logout?</h3>
        <div class="flex justify-center gap-4">
            <button onclick="confirmLogout()" class="bg-red-500 text-white px-4 py-2 rounded">Yes</button>
            <button onclick="closeLogoutModal()" class="bg-gray-300 px-4 py-2 rounded">No</button>
        </div>
    </div>
</div>

<!-- Hidden Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- JavaScript for Modal -->
<script>
    function showLogoutModal() {
        let modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden'); // Show modal
        modal.classList.add('flex'); // Center content
    }

    function closeLogoutModal() {
        let modal = document.getElementById('logoutModal');
        modal.classList.remove('flex'); // Remove flex
        modal.classList.add('hidden'); // Hide modal
    }

    function confirmLogout() {
        document.getElementById('logout-form').submit(); // Submit the form when "Yes" is clicked
    }
</script>
@livewireScripts
</body>
</html>

