<x-app-layout>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-gray-900">Notifications</h1>
                @if(auth()->user()->unreadNotifications()->count())
                    <form method="POST" action="{{ route('notifications.markRead') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-blue-600 hover:underline font-medium">
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            @if($notifications->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <p class="text-gray-400 text-sm">No notifications yet.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($notifications as $notif)
                        <a href="{{ $notif->data['url'] ?? route('reports.index') }}"
                            class="block bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition
                                   {{ is_null($notif->read_at) ? 'border-l-4 border-blue-500' : '' }}">

                            <div class="flex items-start justify-between gap-3">
                                <p class="text-sm {{ is_null($notif->read_at) ? 'font-semibold text-gray-900' : 'text-gray-600' }}">
                                    {{ $notif->data['message'] ?? 'Notification' }}
                                </p>

                                @if(!empty($notif->data['status']))
                                    @php
                                        $badge = [
                                            'pending'     => 'bg-yellow-100 text-yellow-700',
                                            'in_progress' => 'bg-blue-100 text-blue-700',
                                            'resolved'    => 'bg-green-100 text-green-700',
                                        ][$notif->data['status']] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $notif->data['status'])) }}
                                    </span>
                                @endif
                            </div>

                            @if(!empty($notif->data['admin_notes']))
                                <p class="mt-1.5 text-sm text-blue-700">
                                    <span class="font-medium">Note:</span> {{ $notif->data['admin_notes'] }}
                                </p>
                            @endif

                            <p class="mt-2 text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
