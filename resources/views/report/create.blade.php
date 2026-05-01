<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit a Report
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8">

                <form method="POST" action="{{ route('report.store') }}">
                    @csrf

                    <!-- TITLE -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Brief title of your report">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="5"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe the issue in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CATEGORY -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            
                            <option value="">-- Select Category --</option>
                            <option value="environmental">Environmental</option>
                            <option value="safety">Safety</option>
                            <option value="infrastructure">Infrastructure</option>
                            <option value="health">Health</option>
                            <option value="crime">Crime</option>
                            <option value="disaster">Disaster</option>
                            <option value="traffic">Traffic</option>
                            <option value="others">Others</option>

                        </select>

                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                            Submit Report
                        </button>

                        <a href="{{ route('dashboard') }}"
                            class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>