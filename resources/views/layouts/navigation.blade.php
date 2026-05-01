<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left Side -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="font-bold text-lg">Bantay Barangay</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        Reports
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">

                <!-- Notification Bell -->
                @php $unread = auth()->user()->unreadNotifications()->get(); @endphp
                <div class="relative mr-4" x-data="{ open: false }">
                    <button @click="open = !open" class="relative text-xl focus:outline-none">
                        🔔
                        @if($unread->count())
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full leading-none">
                                {{ $unread->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white border rounded-lg shadow-lg z-50 overflow-hidden">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase border-b bg-gray-50">
                            Notifications
                        </div>

                        @forelse($unread as $notif)
                            <div class="px-3 py-2 border-b text-sm text-gray-700 hover:bg-gray-50">
                                {{ $notif->data['message'] ?? 'No message' }}
                                <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-3 py-4 text-sm text-gray-400 text-center">No new notifications</div>
                        @endforelse

                        @if($unread->count())
                            <form method="POST" action="{{ route('notifications.markRead') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-xs text-center text-blue-600 hover:underline py-2">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white rounded-md hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                ▼
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>
        </div>
    </div>
</nav>
