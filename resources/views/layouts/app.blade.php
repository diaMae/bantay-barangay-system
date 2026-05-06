<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantay-Barangay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .leaflet-popup-content { font-size: 13px; line-height: 1.5; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen">

    @include('layouts.partials.sidebar')

    {{-- Top Header: logo + hamburger only --}}
    <header class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-4 sm:px-6">

        {{-- Left: Logo + Name --}}
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain shrink-0">
            <span class="font-bold text-lg md:text-xl text-gray-800">
                Bantay-Barangay
            </span>
        </div>

        {{-- Right: Hamburger --}}
        <button onclick="toggleSidebar()"
            class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </header>

    {{-- Page content --}}
    <main>
        {{ $slot }}
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.add('hidden');
        }
    </script>

</body>
</html>
