<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Report Details</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">

            <h3 class="text-lg font-bold">{{ $report->title }}</h3>

            <p class="text-sm text-gray-400">
                {{ $report->category }} · {{ $report->created_at->format('M d, Y') }}
            </p>

            <p class="mt-4 text-gray-700">{{ $report->description }}</p>

            <p class="mt-3 text-sm font-medium">
                Status: {{ ucfirst(str_replace('_',' ', $report->status)) }}
            </p>

            @if($report->image_url)
                <img src="{{ $report->image_url }}" class="mt-4 w-64 rounded-lg">
            @endif

            @if($report->latitude && $report->longitude)
                <div id="map" style="height:300px;" class="mt-4 rounded"></div>
            @endif

            <a href="{{ route('reports.index') }}" class="mt-6 inline-block text-blue-600">
                ← Back to Reports
            </a>

        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    @if($report->latitude && $report->longitude)
    <script>
        var map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map);
    </script>
    @endif

</x-app-layout>