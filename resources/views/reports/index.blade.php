<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Reports
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">All Reports</h3>
                <a href="{{ route('report.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    + New Report
                </a>
            </div>

            @if($reports->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center">
                    <p class="text-gray-400 text-sm">No reports yet.</p>
                </div>
            @else
                <div class="space-y-6">

                    @foreach($reports as $report)
                        <a href="{{ route('reports.show', $report->id) }}" class="block">
                            <div class="bg-white rounded-xl shadow-sm p-6 hover:bg-gray-50 hover:shadow-md transition cursor-pointer">

                                {{-- Header --}}
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $report->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $report->category }} · {{ $report->created_at->format('M d, Y') }}
                                        </p>
                                    </div>

                                    {{-- Status --}}
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $report->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $report->status == 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $report->status == 'resolved' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ ucfirst(str_replace('_',' ', $report->status)) }}
                                    </span>
                                </div>

                                {{-- Description --}}
                                <p class="text-sm text-gray-600 mb-4">{{ $report->description }}</p>

                                {{-- Image --}}
                                @if($report->image_url)
                                    <div class="mb-4">
                                        <p class="text-xs text-gray-400 mb-1">Image Evidence</p>
                                        <img src="{{ $report->image_url }}" 
                                             class="rounded-lg border w-40"
                                             onerror="this.src='https://via.placeholder.com/150'">
                                    </div>
                                @endif

                                {{-- Map --}}
                                @if($report->latitude && $report->longitude)
                                    <div>
                                        <p class="text-xs text-gray-400 mb-1">Pinned Location</p>
                                        <div id="map-{{ $report->id }}" style="height: 200px;"></div>
                                    </div>
                                @endif

                            </div>
                        </a>
                    @endforeach

                </div>
            @endif

        </div>
    </div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($reports as $report)
                @if($report->latitude && $report->longitude)
                    var map{{ $report->id }} = L.map('map-{{ $report->id }}').setView(
                        [{{ $report->latitude }}, {{ $report->longitude }}], 15
                    );

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map{{ $report->id }});

                    L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
                        .addTo(map{{ $report->id }});
                @endif
            @endforeach
        });
    </script>

</x-app-layout>