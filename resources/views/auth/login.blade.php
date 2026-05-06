<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantay-Barangay</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center">

    <div class="flex flex-col items-center">

        <!-- LOGO + TITLE -->
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}"
                 class="h-16 w-16 md:h-20 md:w-20 object-contain mx-auto mb-2">

            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                Bantay-Barangay
            </h1>

            <p class="text-gray-500 text-sm">
                Community Reporting System
            </p>
        </div>

        <!-- LOGIN CARD -->
        <div class="bg-white p-6 rounded-2xl shadow-lg w-[320px]">

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <input type="email" name="email" placeholder="Email"
                    value="{{ old('email') }}"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <input type="password" name="password" placeholder="Password"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Sign in
                </button>

                <p class="text-sm text-center text-gray-500">
                    No account?
                    <a href="{{ route('register') }}" class="text-blue-600 font-medium">
                        Register
                    </a>
                </p>
            </form>

        </div>

    </div>

</body>
</html>