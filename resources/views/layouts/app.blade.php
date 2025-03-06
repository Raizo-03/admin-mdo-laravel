<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<nav class="bg-[#111C4E] p-4 text-white text-[20px] flex justify-between items-center" style="font-family: 'Arial', sans-serif;">
    <!-- Left: UMAK Medical and Dental Clinic with an Icon -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 ml-4">
        <img src="{{ asset('images/umak_logo.png') }}" alt="Clinic Logo" class="w-8 h-8">
        UMAK Medical and Dental Clinic
    </a>

    <!-- Right: Hi, Admin with an Icon -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 mr-3">
        <img src="{{ asset('images/profile.png') }}" alt="Admin Icon" class="w-8 h-8">
        Hi, Admin
    </a>
</nav>
        <!-- Sidebar -->
        <aside class="bg-[#111C4E] text-white w-66 h-screen p-6 fixed">
        <h2 class="text-[17px]  font-bold mb-3">Dashboard</h2>
            <nav class="flex flex-col gap-3 mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/dashboard_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Home
            </a>
         <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Profile
            </a>
            </nav>

        <h2 class="text-[17px] font-bold mb-3">User Management</h2>
            <nav class="flex flex-col gap-3 mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/usermanager_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Active Users
            </a>
         <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Inactive Users
            </a>
            </nav>

        <h2 class="text-[17px] font-bold mb-3">Appointment Management</h2>
            <nav class="flex flex-col gap-3 mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/appointmanager_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Requests
            </a>
         <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Confirmed
            </a>
                     <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Completed
            </a>
                           <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 No Show
            </a>
            </nav>

        <h2 class="text-[17px] font-bold mb-3">Content Manager</h2>
            <nav class="flex flex-col gap-3 mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/chat_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Chat
            </a>
         <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Feedback
            </a>
                     <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/profile.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Announcements
            </a>
                         <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#89a0df] p-1 rounded ">
             <img src="{{ asset('images/trivia_icon.png') }}" alt="Clinic Logo" class="w-5 h-5">
                 Trivia
            </a>
            </nav>
            


       <h2 class="text-[17px]  mb-3 hover:bg-[#89a0df] p-1 rounded">Logout</h2>
        </aside>




    <div class="container mx-auto mt-8">
        @yield('content')  <!-- This is where page content will be injected -->
    </div>

</body>
</html>
