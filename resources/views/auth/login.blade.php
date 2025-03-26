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

                    <input type="text" name="username" placeholder="Enter username" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    
                    <input type="password" name="password" placeholder="Enter password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">

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
