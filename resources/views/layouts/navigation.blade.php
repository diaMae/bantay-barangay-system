<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Left: Brand + Nav Links --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-bold text-lg tracking-tight">
                    Bantay-Barangay
                </a>
                <div class="hidden sm:flex items-center gap-5">
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-800' }} transition">
                        Dashboard
                    </a>
                    <a href="{{ route('reports.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('reports.index') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-800' }} transition">
                        Reports
                    </a>
                    @if(auth()->user()->role === 'resident')
                        <a href="{{ route('report.create') }}"
                           class="text-sm font-medium {{ request()->routeIs('report.create') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-800' }} transition">
                            + Submit Report
                        </a>
                    @endif
                    <a href="{{ route('notifications.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('notifications.*') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-800' }} transition">
                        Notifications
                        @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unreadCount)
                            <span class="ml-1 inline-flex items-center justify-center w-4 h-4 bg-red-500 text-white text-xs rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Right: Bell + User --}}
            <div class="flex items-center gap-3">

                {{-- Notification Bell --}}
                @php $unread = auth()->user()->unreadNotifications()->get(); @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="relative p-2 text-gray-500 hover:text-gray-800 focus:outline-none transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unread->count())
                            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center leading-none">
                                {{ $unread->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">

                        <div class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide border-b bg-gray-50">
                            Notifications
                        </div>

                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notif)
                            <a href="{{ $notif->data['url'] ?? route('reports.index') }}"
                               class="block px-4 py-3 border-b hover:bg-gray-50 transition {{ is_null($notif->read_at) ? 'bg-blue-50' : '' }}">
                                <p class="text-sm {{ is_null($notif->read_at) ? 'font-semibold text-gray-800' : 'text-gray-600' }}">
                                    {{ $notif->data['message'] ?? 'Notification' }}
                                </p>
                                @if(!empty($notif->data['admin_notes']))
                                    <p class="text-xs text-blue-600 mt-0.5 truncate">
                                        Note: {{ $notif->data['admin_notes'] }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-sm text-gray-400 text-center">No notifications yet</div>
                        @endforelse

                        <div class="flex border-t divide-x">
                            <a href="{{ route('notifications.index') }}"
                               class="flex-1 text-xs text-center text-blue-600 hover:bg-gray-50 py-2.5 transition">
                                View all
                            </a>
                            @if($unread->count())
                                <form method="POST" action="{{ route('notifications.markRead') }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-xs text-center text-gray-500 hover:bg-gray-50 py-2.5 transition">
                                        Mark all read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div class="sm:hidden border-t border-gray-100 px-4 py-3 flex flex-wrap gap-4">
        <a href="{{ route('dashboard') }}"
           class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
            Dashboard
        </a>
        <a href="{{ route('reports.index') }}"
           class="text-sm font-medium {{ request()->routeIs('reports.index') ? 'text-blue-600' : 'text-gray-500' }}">
            Reports
        </a>
        @if(auth()->user()->role === 'resident')
            <a href="{{ route('report.create') }}"
               class="text-sm font-medium {{ request()->routeIs('report.create') ? 'text-blue-600' : 'text-gray-500' }}">
                + Submit Report
            </a>
        @endif
        <a href="{{ route('notifications.index') }}"
           class="text-sm font-medium {{ request()->routeIs('notifications.*') ? 'text-blue-600' : 'text-gray-500' }}">
            Notifications
        </a>
    </div>
</nav>
