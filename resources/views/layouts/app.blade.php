<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen">

        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

        @auth
            @if(auth()->user()->role === 'resident')
                <a href="{{ route('report.create') }}"
                   class="fixed bottom-6 right-6 bg-blue-600 text-white text-sm font-medium px-5 py-3 rounded-full shadow-lg hover:bg-blue-700 transition z-50">
                    + Report
                </a>
            @endif
        @endauth

    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</body>
</html>
