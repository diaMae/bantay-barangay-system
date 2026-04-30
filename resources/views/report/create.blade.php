<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit a Report
        </h2>
    </x-slot>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8">

                <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Category --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Category --</option>
                            <option value="Infrastructure" {{ old('category') === 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                            <option value="Public Safety" {{ old('category') === 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                            <option value="Sanitation" {{ old('category') === 'Sanitation' ? 'selected' : '' }}>Sanitation</option>
                            <option value="Noise Complaint" {{ old('category') === 'Noise Complaint' ? 'selected' : '' }}>Noise Complaint</option>
                            <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Brief title of your report">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe the issue in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image Evidence <span class="text-gray-400">(optional)</span></label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-4 py-2 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Leaflet Location Picker --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pin Location <span class="text-gray-400">(optional — click map to pin)</span>
                        </label>
                        <div id="map" style="height: 300px; width: 100%; border-radius: 10px;"></div>
                        <p id="map-hint" class="text-xs text-gray-400 mt-1">No location pinned yet.</p>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                            Submit Report
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="text-sm text-gray-500 hover:text-gray-700 transition">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var map = L.map('map').setView([17.5747, 120.3869], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker;

            // Restore old pinned location on validation failure
            var oldLat = document.getElementById('latitude').value;
            var oldLng = document.getElementById('longitude').value;
            if (oldLat && oldLng) {
                marker = L.marker([parseFloat(oldLat), parseFloat(oldLng)]).addTo(map);
                map.setView([parseFloat(oldLat), parseFloat(oldLng)], 15);
                document.getElementById('map-hint').textContent = 'Pinned: ' + parseFloat(oldLat).toFixed(5) + ', ' + parseFloat(oldLng).toFixed(5);
            }

            map.on('click', function (e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('map-hint').textContent = 'Pinned: ' + lat.toFixed(5) + ', ' + lng.toFixed(5);
            });
        });
    </script>

</x-app-layout>
