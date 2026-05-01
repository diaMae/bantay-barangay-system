<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Submit Report</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white p-6 rounded-xl shadow">

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold text-sm">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border rounded p-2 text-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold text-sm">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full border rounded p-2 text-sm" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold text-sm">Category</label>
                        <select name="category" class="w-full border rounded p-2 text-sm">
                            <option value="">Select Category</option>
                            <option value="Public Safety" {{ old('category') == 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                            <option value="Noise Complaint" {{ old('category') == 'Noise Complaint' ? 'selected' : '' }}>Noise Complaint</option>
                            <option value="Road Issue" {{ old('category') == 'Road Issue' ? 'selected' : '' }}>Road Issue</option>
                            <option value="Environmental" {{ old('category') == 'Environmental' ? 'selected' : '' }}>Environmental</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold text-sm">Upload Image (optional)</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border rounded p-2 text-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold text-sm">Pin Location on Map</label>
                        <p class="text-xs text-gray-400 mb-2">Click inside the map to pin your location within Barangay Rang-ay, Sinait, Ilocos Sur.</p>
                        <div id="map" style="height: 300px;" class="rounded border"></div>
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div id="coords-display" class="text-xs text-gray-400 mb-4 hidden">
                        Pinned: <span id="coords-text"></span>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition">
                        Submit Report
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // GeoJSON polygon for Barangay Rang-ay, Sinait, Ilocos Sur
            var rangayGeoJSON = {
                "type": "Feature",
                "geometry": {
                    "type": "Polygon",
                    "coordinates": [[
                        [120.4450, 17.8600],
                        [120.4480, 17.8595],
                        [120.4510, 17.8598],
                        [120.4535, 17.8608],
                        [120.4558, 17.8620],
                        [120.4575, 17.8638],
                        [120.4585, 17.8655],
                        [120.4590, 17.8672],
                        [120.4582, 17.8690],
                        [120.4568, 17.8705],
                        [120.4548, 17.8715],
                        [120.4525, 17.8720],
                        [120.4500, 17.8718],
                        [120.4475, 17.8710],
                        [120.4458, 17.8698],
                        [120.4445, 17.8680],
                        [120.4440, 17.8660],
                        [120.4442, 17.8640],
                        [120.4448, 17.8620],
                        [120.4450, 17.8600]
                    ]]
                }
            };

            var map = L.map('map', {
                zoom:               16,
                minZoom:            15,
                maxZoom:            18
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var polygonLayer = L.geoJSON(rangayGeoJSON, {
                style: {
                    color:       '#2563eb',
                    weight:      2,
                    fill:        true,
                    fillColor:   '#2563eb',
                    fillOpacity: 0.08
                }
            }).addTo(map);

            // Fit map view to polygon bounds
            map.fitBounds(polygonLayer.getBounds());

            // ── Point-in-polygon (ray casting) ──────────────────────────────
            function pointInPolygon(latlng, geojson) {
                var coords = geojson.geometry.coordinates[0];
                var x = latlng.lng, y = latlng.lat;
                var inside = false;
                for (var i = 0, j = coords.length - 1; i < coords.length; j = i++) {
                    var xi = coords[i][0], yi = coords[i][1];
                    var xj = coords[j][0], yj = coords[j][1];
                    var intersect = ((yi > y) !== (yj > y))
                        && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                    if (intersect) inside = !inside;
                }
                return inside;
            }

            var marker;

            map.on('click', function (e) {
                if (!pointInPolygon(e.latlng, rangayGeoJSON)) {
                    alert('Please pin a location inside Barangay Rang-ay, Sinait, Ilocos Sur only.');
                    return;
                }

                if (marker) map.removeLayer(marker);
                marker = L.marker(e.latlng).addTo(map)
                    .bindPopup('Pinned location in Brgy. Rang-ay')
                    .openPopup();

                document.getElementById('latitude').value  = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;

                document.getElementById('coords-display').classList.remove('hidden');
                document.getElementById('coords-text').textContent =
                    e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);
            });
        });
    </script>
</x-app-layout>
