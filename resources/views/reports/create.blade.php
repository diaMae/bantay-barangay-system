<x-app-layout>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-5">

    {{-- Header --}}
    <div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Reports
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Submit a Report</h1>
        <p class="text-sm text-gray-500 mt-0.5">Report an incident in Barangay Rang-ay.</p>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    @foreach(\App\Models\Report::CATEGORIES as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Describe the incident in detail...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Image Evidence <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <input type="file" name="image" accept="image/*"
                    class="w-full text-sm text-gray-600 border border-gray-300 rounded-xl px-4 py-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pin Location <span class="text-gray-400 font-normal">(click inside the boundary)</span>
                </label>
                <div id="map" class="w-full rounded-xl border border-gray-200" style="height: 300px;"></div>
                <p id="coords-display" class="text-xs text-gray-400 mt-1">No location pinned yet.</p>
                <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                    Submit Report
                </button>
                <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof L === 'undefined') return;

    var map = L.map('map').setView([17.865, 120.458], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker       = null;
    var boundaryData = null;

    // Restore old pin on validation failure
    var oldLat = document.getElementById('latitude').value;
    var oldLng = document.getElementById('longitude').value;

    fetch('{{ asset('rang-ay_boundary_files/rang-ay_boundary.geojson.json') }}')
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (data) {
            boundaryData = data;

            var boundary = L.geoJSON(data, {
                style: { color: '#2563eb', weight: 2, fillColor: '#93c5fd', fillOpacity: 0.15 }
            }).addTo(map);

            map.fitBounds(boundary.getBounds());

            if (oldLat && oldLng) {
                marker = L.marker([parseFloat(oldLat), parseFloat(oldLng)]).addTo(map);
                document.getElementById('coords-display').textContent =
                    'Pinned: ' + parseFloat(oldLat).toFixed(5) + ', ' + parseFloat(oldLng).toFixed(5);
            }
        })
        .catch(function (err) {
            console.error('Boundary failed:', err);
            document.getElementById('coords-display').textContent = 'Boundary could not be loaded.';
        });

    map.on('click', function (e) {
        if (!boundaryData) return;

        var point   = turf.point([e.latlng.lng, e.latlng.lat]);
        var polygon = turf.getGeom(boundaryData.features[0]);

        if (!turf.booleanPointInPolygon(point, polygon)) {
            alert('Please pin a location inside the barangay boundary.');
            return;
        }

        if (marker) map.removeLayer(marker);
        marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);

        document.getElementById('latitude').value  = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
        document.getElementById('coords-display').textContent =
            'Pinned: ' + e.latlng.lat.toFixed(5) + ', ' + e.latlng.lng.toFixed(5);
    });

});
</script>

</x-app-layout>
