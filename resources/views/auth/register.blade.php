<html>
<head>
    <title>Bantay-Barangay</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center">

<div class="w-[320px] md:w-[360px]">

    <!-- LOGO + TITLE -->
    <div class="text-center mb-6">
        <img src="{{ asset('images/logo.png') }}" 
             class="h-16 w-16 md:h-20 md:w-20 object-contain mx-auto mb-3">

        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">
            Bantay-Barangay
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Community Reporting System
        </p>
    </div>

    <!-- REGISTER CARD -->
    <div class="bg-white p-7 md:p-10 rounded-xl shadow-md">

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- FULL NAME -->
            <div>
                <input type="text" name="name" placeholder="Full Name"
                    value="{{ old('name') }}"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <input type="email" name="email" placeholder="Email"
                    value="{{ old('email') }}"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <input type="password" name="password" placeholder="Password"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->
            <div>
                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- BUTTON -->
            <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Register
            </button>

            <!-- LOGIN LINK -->
            <p class="text-sm text-center text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 font-medium">Login</a>
            </p>

        </form>

    </div>

</div>

</body>
</html>