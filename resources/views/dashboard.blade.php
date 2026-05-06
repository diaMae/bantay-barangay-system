<x-app-layout>

<div class="p-6 space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            {{ auth()->user()->isAdmin()
                ? 'Overview of all barangay incident reports.'
                : 'Welcome back, ' . auth()->user()->name . '!' }}
        </p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white border-l-4 border-blue-600 rounded-xl shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Reports</p>
            <p class="text-4xl font-bold text-blue-600 mt-2">{{ $totalReports }}</p>
        </div>

        <div class="bg-white border-l-4 border-yellow-500 rounded-xl shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Pending</p>
            <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $pending }}</p>
        </div>

        <div class="bg-white border-l-4 border-blue-400 rounded-xl shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">In Progress</p>
            <p class="text-4xl font-bold text-blue-500 mt-2">{{ $inProgress }}</p>
        </div>

        <div class="bg-white border-l-4 border-green-500 rounded-xl shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Resolved</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ $resolved }}</p>
        </div>

    </div>

    {{-- Resident quick action --}}
    @if(auth()->user()->role === 'resident')
        <div class="flex gap-3">
            <a href="{{ route('report.create') }}"
               class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                + Submit Report
            </a>
            <a href="{{ route('reports.index') }}"
               class="px-5 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm">
                My Reports
            </a>
        </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent Reports --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Recent Reports</h2>
                <a href="{{ route('reports.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
            </div>

            @forelse($recentReports as $r)
                <a href="{{ route('reports.show', $r->id) }}"
                   class="flex items-center justify-between py-3 border-b border-gray-50 hover:bg-gray-50 -mx-2 px-2 rounded-lg transition last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $r->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $r->category }}
                            @if(auth()->user()->isAdmin() && isset($r->user))
                                · {{ $r->user->name }}
                            @endif
                            · {{ $r->created_at->format('M d') }}
                        </p>
                    </div>
                    @php
                        $badge = [
                            'pending'     => 'bg-yellow-100 text-yellow-700',
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'resolved'    => 'bg-green-100 text-green-700',
                        ][$r->status] ?? 'bg-gray-100 text-gray-500';
                    @endphp
                    <span class="ml-3 shrink-0 text-xs px-2.5 py-1 rounded-full font-medium {{ $badge }}">
                        {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                    </span>
                </a>
            @empty
                <div class="text-center py-10">
                    <p class="text-sm text-gray-400">No reports yet.</p>
                    @if(auth()->user()->role === 'resident')
                        <a href="{{ route('report.create') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                            Submit your first report →
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Incident Map --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Incident Map</h2>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>Pending</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>In Progress</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>Resolved</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-3">
                <select id="statusFilter"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                </select>
                <select id="categoryFilter"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Categories</option>
                    @foreach(\App\Models\Report::CATEGORIES as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div id="report-map" class="w-full rounded-xl border border-gray-200" style="height: 360px;"></div>
            <p id="map-count" class="text-xs text-gray-400 mt-2"></p>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof L === 'undefined') {
        document.getElementById('report-map').innerHTML =
            '<p class="text-sm text-gray-400 text-center pt-16">Map unavailable.</p>';
        return;
    }

    var map = L.map('report-map').setView([17.5747, 120.3869], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch('{{ asset('rang-ay_boundary_files/rang-ay_boundary.geojson.json') }}')
        .then(function (res) { return res.ok ? res.json() : null; })
        .then(function (data) {
            if (!data) return;
            L.geoJSON(data, {
                style: { color: '#2563eb', weight: 2, fillColor: '#93c5fd', fillOpacity: 0.1 }
            }).addTo(map);
        })
        .catch(function () {});

    var allReports    = @json($mapReports);
    var activeMarkers = [];
    var statusColors  = { pending: '#ef4444', in_progress: '#3b82f6', resolved: '#22c55e' };

    function renderMarkers() {
        activeMarkers.forEach(function (m) { map.removeLayer(m); });
        activeMarkers = [];

        var sv = document.getElementById('statusFilter').value;
        var cv = document.getElementById('categoryFilter').value;

        var filtered = allReports.filter(function (r) {
            return (sv === 'all' || r.status === sv) && (cv === 'all' || r.category === cv);
        });

        var bounds = [];

        filtered.forEach(function (r) {
            var lat = parseFloat(r.latitude);
            var lng = parseFloat(r.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            var color = statusColors[r.status] || '#6b7280';

            var m = L.circleMarker([lat, lng], {
                radius: 9, fillColor: color, color: '#fff', weight: 2, fillOpacity: 0.9
            }).addTo(map);

            m.bindPopup(
                '<div style="min-width:150px">' +
                '<p style="font-weight:600;margin-bottom:3px">' + r.title + '</p>' +
                '<p style="font-size:12px;color:#6b7280;margin-bottom:3px">' + r.category + '</p>' +
                '<span style="font-size:11px;padding:2px 8px;border-radius:999px;background:' + color + '22;color:' + color + ';font-weight:600">' +
                r.status.replace('_', ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); }) +
                '</span></div>'
            );

            activeMarkers.push(m);
            bounds.push([lat, lng]);
        });

        if (bounds.length > 0) map.fitBounds(bounds, { padding: [40, 40] });

        document.getElementById('map-count').textContent =
            filtered.length + ' report' + (filtered.length !== 1 ? 's' : '') + ' shown';
    }

    document.getElementById('statusFilter').addEventListener('change', renderMarkers);
    document.getElementById('categoryFilter').addEventListener('change', renderMarkers);
    renderMarkers();
    setTimeout(function () { map.invalidateSize(); }, 300);
});
</script>

</x-app-layout>
