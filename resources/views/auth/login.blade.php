<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<body class="bg-[#111C4E] min-h-screen flex items-center justify-center">

    <div class="container flex flex-col md:flex-row w-full max-w-4xl bg-[#111C4E] rounded-lg shadow-lg p-6 md:p-10">

        <!-- Left Section (Logo) -->
        <div class="flex flex-1 flex-col items-center justify-center p-4">
            <img src="{{ asset('images/mdo_logo.png') }}" alt="UMak Logo" class="w-53 md:w-82">
        </div>

        <!-- Right Section (Login Form) -->
        <div class="flex flex-1 flex-col justify-center items-center">
            <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf <!-- Protects against CSRF attacks -->

                    {{-- Username input --}}
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" placeholder="Enter username" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    {{-- Password input with eye icon --}}
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Enter password" required
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="togglePassword()" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <!-- Eye icon (visible when password is hidden) -->
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye closed icon (visible when password is shown) -->
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    <!-- Display Error Message Below Password -->
                     @if ($errors->has('login'))
                    <p style="color: red; font-size: 16px; font-weight:bold;">{{ $errors->first('login') }}</p>
                     @endif

                    @if (session('error'))
                        <div class="text-red-500 text-sm">{{ session('error') }}</div>
                    @endif

                    <button type="submit"
                        class="w-full py-2 bg-yellow-400 text-[#03194f] font-bold rounded-md hover:bg-yellow-500 transition">
                        LOG IN
                    </button>
                </form>

                {{-- <div class="text-center mt-4 text-gray-600">
                    <a href="#" class="hover:underline">Forgot Password?</a>
                </div> --}}
            </div>
        </div>

    </div>

</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerText = "Logging in...";
        });
    });
</script>
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>