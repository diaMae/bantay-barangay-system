<x-app-layout>

<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-2">{{ $report->title }}</h2>

    <p class="text-sm text-gray-500 mb-2">{{ $report->category }}</p>

    <p class="mb-4">{{ $report->description }}</p>

    <span class="text-xs px-2 py-1 rounded
        {{ $report->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
        {{ $report->status == 'resolved' ? 'bg-green-100 text-green-700' : '' }}">
        {{ $report->status }}
    </span>

</div>

@if(auth()->user()->role === 'admin')
<div class="mt-6 bg-white p-5 rounded-xl shadow">

    <h3 class="font-semibold mb-3">Admin Controls</h3>

    <form method="POST" action="{{ route('reports.updateStatus', $report->id) }}">
        @csrf
        @method('PATCH')

        <select name="status" class="w-full border rounded p-2 mb-3">
            <option value="pending">Pending</option>
            <option value="resolved">Resolved</option>
        </select>

        <textarea name="admin_notes" class="w-full border rounded p-2 mb-3">
{{ $report->admin_notes }}
        </textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
        </button>
    </form>

</div>
@endif

</x-app-layout>