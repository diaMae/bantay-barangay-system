<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->isAdmin() ? 'All Reports' : 'My Reports' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <p class="text-sm text-gray-500">{{ $reports->count() }} report(s) found</p>
                @if(auth()->user()->role === 'resident')
                    <a href="{{ route('report.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        + New Report
                    </a>
                @endif
            </div>

            @if($reports->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center">
                    <p class="text-gray-400 text-sm">No reports yet.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reports as $report)
                        <a href="{{ route('reports.show', $report->id) }}" class="block">
                            <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">

                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $report->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $report->category }} · {{ $report->created_at->format('M d, Y') }}
                                            @if(auth()->user()->isAdmin())
                                                · by {{ $report->user->name ?? 'Unknown' }}
                                            @endif
                                        </p>
                                    </div>

                                    @php
                                        $statusClasses = [
                                            'pending'     => 'bg-yellow-100 text-yellow-700',
                                            'in_progress' => 'bg-blue-100 text-blue-700',
                                            'resolved'    => 'bg-green-100 text-green-700',
                                        ];
                                        $cls = $statusClasses[$report->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $cls }}">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $report->description }}</p>

                                @if($report->image)
                                    <img src="{{ asset('storage/' . $report->image) }}"
                                         class="mt-3 rounded-lg border w-32 h-24 object-cover"
                                         onerror="this.style.display='none'">
                                @endif

                                @if($report->latitude && $report->longitude)
                                    <div id="map-{{ $report->id }}" style="height: 180px;" class="mt-3 rounded border"></div>
                                @endif

                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($reports as $report)
                @if($report->latitude && $report->longitude)
                    (function() {
                        var m = L.map('map-{{ $report->id }}', { zoomControl: false, dragging: false, scrollWheelZoom: false })
                            .setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(m);
                        L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(m);
                    })();
                @endif
            @endforeach
        });
    </script>
</x-app-layout>
