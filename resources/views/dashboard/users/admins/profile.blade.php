@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="space-y-5 ml-10">
    <!-- Title -->
    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Admin Profile</h1>
    </div>

    <!-- Profile Container -->
    <div class="flex items-center space-x-6 bg-white shadow-lg rounded-xl p-6 w-full">
        <!-- Profile Image -->
        <div class="flex flex-col items-center space-y-3">
            <div class="w-40 h-40 rounded-full border-4 border-gray-400 overflow-hidden">
                    <img src="{{ $admin->profile_picture ? $admin->profile_picture : asset('images/default-profile.png') }}" 
                        alt="Profile Picture" class="w-full h-full object-cover rounded-full">

            </div>

            <!-- Upload Button -->
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="file" name="profile_picture" id="fileInput" class="hidden" onchange="this.form.submit()">
                <label for="fileInput" class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                    Upload Image
                </label>
            </form>

        </div>

        <!-- Profile Info -->
        <div class="bg-gray-100 p-4 rounded-lg shadow w-2/3">
            <p class="text-xl font-bold text-gray-800">{{ $admin->username }}</p>
            <p class="text-md text-gray-600">{{ $admin->email ?? 'No email provided' }}</p>
        </div>
    </div>
</div>
@endsection
