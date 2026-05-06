<style>
    #sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
    #sidebar.open { transform: translateX(0); }
</style>

<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-200 shadow-xl z-50 flex flex-col">

    {{-- Header: close button --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain shrink-0">
            <span class="font-bold text-sm text-gray-800">Bantay-Barangay</span>
        </div>
        <button onclick="closeSidebar()" class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- User info (clickable → profile) --}}
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 hover:bg-gray-50 transition">
        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
        </div>
    </a>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        @php
            $link = 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition';
            $active = $link . ' bg-blue-50 text-blue-600';
            $inactive = $link . ' text-gray-600 hover:bg-gray-100 hover:text-gray-900';
        @endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $active : $inactive }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Reports --}}
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') ? $active : $inactive }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ auth()->user()->isAdmin() ? 'All Reports' : 'My Reports' }}
        </a>

        {{-- Submit Report (resident only) --}}
        @if(auth()->user()->role === 'resident')
            <a href="{{ route('report.create') }}" class="{{ request()->routeIs('report.create') ? $active : $inactive }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Submit Report
            </a>
        @endif

        {{-- Notifications --}}
        <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? $active : $inactive }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifications
            @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
            @if($unread)
                <span class="ml-auto w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center shrink-0">
                    {{ $unread }}
                </span>
            @endif
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? $active : $inactive }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>

    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>

</aside>

{{-- Overlay --}}
<div id="overlay"
     class="fixed inset-0 bg-black/40 z-40 hidden"
     onclick="closeSidebar()">
</div>
