<x-app-layout>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ auth()->user()->isAdmin() ? 'All Reports' : 'My Reports' }}
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $reports->count() }} report(s) found</p>
        </div>
        @if(auth()->user()->role === 'resident')
            <a href="{{ route('report.create') }}"
               class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                + Submit Report
            </a>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Reports --}}
    @if($reports->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <p class="text-gray-400 text-sm">No reports yet.</p>
            @if(auth()->user()->role === 'resident')
                <a href="{{ route('report.create') }}" class="mt-3 inline-block text-sm text-blue-600 hover:underline">
                    Submit your first report →
                </a>
            @endif
        </div>
    @else
        <div class="space-y-3">
            @foreach($reports as $report)
                <a href="{{ route('reports.show', $report->id) }}"
                   class="block bg-white border border-gray-100 rounded-2xl shadow-sm p-5 hover:shadow-md hover:border-gray-200 transition">

                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 truncate">{{ $report->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $report->category }}
                                · {{ $report->created_at->format('M d, Y') }}
                                @if(auth()->user()->isAdmin())
                                    · <span class="text-gray-500">{{ $report->user->name ?? 'Unknown' }}</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $report->description }}</p>
                        </div>

                        @php
                            $cls = [
                                'pending'     => 'bg-yellow-100 text-yellow-700',
                                'in_progress' => 'bg-blue-100 text-blue-700',
                                'resolved'    => 'bg-green-100 text-green-700',
                            ][$report->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="shrink-0 text-xs px-3 py-1 rounded-full font-medium {{ $cls }}">
                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span>
                    </div>

                    @if($report->image)
                        <img src="{{ asset('storage/' . $report->image) }}"
                             class="mt-3 rounded-lg border border-gray-100 w-28 h-20 object-cover"
                             onerror="this.style.display='none'">
                    @endif

                    @if($report->latitude && $report->longitude)
                        <p class="mt-2 text-xs text-gray-400">
                            📍 {{ number_format($report->latitude, 5) }}, {{ number_format($report->longitude, 5) }}
                        </p>
                    @endif

                </a>
            @endforeach
        </div>
    @endif

</div>

</x-app-layout>
