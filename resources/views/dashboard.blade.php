<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Welcome Card --}}
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <p class="text-2xl font-semibold text-gray-800">Welcome, {{ auth()->user()->name }}!</p>
                <p class="text-gray-500 text-sm mt-1">
                    {{ auth()->user()->isAdmin() ? 'Admin Dashboard — managing all barangay reports.' : "Here's an overview of your reports." }}
                </p>

                @if(auth()->user()->role === 'resident')
                    <div class="mt-5 flex justify-center gap-3">
                        <a href="{{ route('report.create') }}"
                            class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                            + Submit Report
                        </a>
                        <a href="{{ route('reports.index') }}"
                            class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                            My Reports
                        </a>
                    </div>
                @else
                    <div class="mt-5 flex justify-center">
                        <a href="{{ route('reports.index') }}"
                            class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                            View All Reports
                        </a>
                    </div>
                @endif
            </div>

            {{-- Summary Cards (both roles) --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $totalReports }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total</p>
                </div>
                <div class="bg-yellow-50 rounded-xl shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $pending }}</p>
                    <p class="text-xs text-yellow-500 mt-1 uppercase tracking-wide">Pending</p>
                </div>
                <div class="bg-blue-50 rounded-xl shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $inProgress }}</p>
                    <p class="text-xs text-blue-500 mt-1 uppercase tracking-wide">In Progress</p>
                </div>
                <div class="bg-green-50 rounded-xl shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $resolved }}</p>
                    <p class="text-xs text-green-500 mt-1 uppercase tracking-wide">Resolved</p>
                </div>
            </div>

            {{-- Recent Reports --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Recent Reports</h3>

                @if($recentReports->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-6">No reports yet.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase border-b">
                                <th class="pb-2 pr-4">Title</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="pb-2 pr-4">Reported By</th>
                                @endif
                                <th class="pb-2 pr-4">Category</th>
                                <th class="pb-2 pr-4">Status</th>
                                <th class="pb-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentReports as $report)
                                <tr>
                                    <td class="py-3 pr-4">
                                        <a href="{{ route('reports.show', $report->id) }}"
                                            class="text-blue-600 hover:underline font-medium">
                                            {{ $report->title }}
                                        </a>
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="py-3 pr-4 text-gray-600">{{ $report->user->name ?? 'Unknown' }}</td>
                                    @endif
                                    <td class="py-3 pr-4 text-gray-500">{{ $report->category }}</td>
                                    <td class="py-3 pr-4">
                                        @php
                                            $sc = [
                                                'pending'     => 'bg-yellow-100 text-yellow-700',
                                                'in_progress' => 'bg-blue-100 text-blue-700',
                                                'resolved'    => 'bg-green-100 text-green-700',
                                            ];
                                            $scls = $sc[$report->status] ?? 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs {{ $scls }}">
                                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-gray-400">{{ $report->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
