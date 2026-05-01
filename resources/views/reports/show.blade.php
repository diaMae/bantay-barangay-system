<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Details</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white p-6 rounded-xl shadow space-y-4">

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 px-4 py-3 rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $report->title }}</h3>
                        <p class="text-xs text-gray-400 mt-1">
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
                    <span class="px-3 py-1 text-xs rounded-full {{ $cls }}">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>

                <p class="text-gray-700 text-sm">{{ $report->description }}</p>

                @if($report->image)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Image Evidence</p>
                        <img src="{{ asset('storage/' . $report->image) }}"
                             class="rounded-lg border max-w-xs"
                             onerror="this.style.display='none'">
                    </div>
                @endif

                @if($report->latitude && $report->longitude)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Pinned Location</p>
                        <div id="map" style="height: 300px;" class="rounded border"></div>
                    </div>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="border-t pt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Update Status</p>
                        <form action="{{ route('reports.updateStatus', $report->id) }}" method="POST" class="flex gap-3 items-center">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="border rounded p-2 text-sm">
                                <option value="pending"     {{ $report->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved"    {{ $report->status === 'resolved'    ? 'selected' : '' }}>Resolved</option>
                            </select>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                Update
                            </button>
                        </form>
                    </div>
                @endif

                <a href="{{ route('reports.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
                    ← Back to Reports
                </a>

            </div>
        </div>
    </div>

    @if($report->latitude && $report->longitude)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map);
        });
    </script>
    @endif
</x-app-layout>
