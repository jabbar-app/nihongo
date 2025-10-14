<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-b border-gray-100" role="navigation" aria-label="Main navigation">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" aria-label="Go to dashboard home">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex" role="menubar" style="margin-top: auto; margin-bottom: auto;">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('lessons.index')" :active="request()->routeIs('lessons.*')">
                        {{ __('Lessons') }}
                    </x-nav-link>
                    <x-nav-link :href="route('flashcards.index')" :active="request()->routeIs('flashcards.*')">
                        {{ __('Flashcards') }}
                    </x-nav-link>

                    <!-- More (Overflow) -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" aria-haspopup="true" aria-expanded="false">
                                {{ __('More') }}
                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('exercises.history')">
                                {{ __('Exercises') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('progress.index')">
                                {{ __('Progress') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('achievements.index')">
                                {{ __('Achievements') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('study-plan.show')">
                                {{ __('Study Plan') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('streak.index')">
                                {{ __('Streak') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('bookmarks.index')">
                                {{ __('Bookmarks') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="hidden sm:flex sm:items-center sm:flex-1 sm:ms-6 sm:me-6">
                <form action="{{ route('search.index') }}" method="GET" class="w-full max-w-md" role="search" aria-label="Site search">
                    <div class="relative">
                        <label for="desktop-search" class="sr-only">Search phrases, dialogues, and drills</label>
                        <input 
                            type="text" 
                            id="desktop-search"
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Search phrases, dialogues, drills…" 
                            aria-label="Search content"
                            class="w-full px-4 py-2 pl-10 pr-20 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm hover:shadow"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" aria-hidden="true">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <kbd class="hidden lg:inline-block px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-300 rounded">
                                <span class="text-xs">⌘K</span>
                            </kbd>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button 
                    @click="open = ! open" 
                    :aria-expanded="open.toString()"
                    :aria-label="open ? 'Close navigation menu' : 'Open navigation menu'"
                    class="inline-flex items-center justify-center p-3 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out min-w-[44px] min-h-[44px]">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" aria-label="Mobile navigation menu">
        <!-- Mobile Search Bar -->
        <div class="pt-2 pb-3 px-4">
            <form action="{{ route('search.index') }}" method="GET" role="search" aria-label="Site search (mobile)">
                <div class="relative">
                    <label for="mobile-search" class="sr-only">Search phrases, dialogues, and drills</label>
                    <input 
                        type="text" 
                        id="mobile-search"
                        name="q" 
                        value="{{ request('q') }}"
                        placeholder="Search…" 
                        aria-label="Search content"
                        class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" aria-hidden="true">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <div class="px-4 pt-2 pb-1 text-xs font-semibold text-gray-500">Learn</div>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lessons.index')" :active="request()->routeIs('lessons.*')">
                {{ __('Lessons') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('flashcards.index')" :active="request()->routeIs('flashcards.*')">
                {{ __('Flashcards') }}
            </x-responsive-nav-link>

            <div class="px-4 pt-3 pb-1 text-xs font-semibold text-gray-500">Practice</div>
            <x-responsive-nav-link :href="route('exercises.history')" :active="request()->routeIs('exercises.history')">
                {{ __('Exercises') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('study-plan.show')" :active="request()->routeIs('study-plan.*')">
                {{ __('Study Plan') }}
            </x-responsive-nav-link>

            <div class="px-4 pt-3 pb-1 text-xs font-semibold text-gray-500">Track</div>
            <x-responsive-nav-link :href="route('progress.index')" :active="request()->routeIs('progress.*')">
                {{ __('Progress') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('achievements.index')" :active="request()->routeIs('achievements.*')">
                {{ __('Achievements') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('streak.index')" :active="request()->routeIs('streak.*')">
                {{ __('Streak') }}
            </x-responsive-nav-link>

            <div class="px-4 pt-3 pb-1 text-xs font-semibold text-gray-500">Saved</div>
            <x-responsive-nav-link :href="route('bookmarks.index')" :active="request()->routeIs('bookmarks.*')">
                {{ __('Bookmarks') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
document.addEventListener('keydown', function (e) {
    const isK = (e.key === 'k' || e.key === 'K');
    const metaOrCtrl = e.metaKey || e.ctrlKey;
    if (isK && metaOrCtrl) {
        e.preventDefault();
        const desktop = document.getElementById('desktop-search');
        const mobile = document.getElementById('mobile-search');
        const target = window.innerWidth >= 640 ? desktop : mobile;
        if (target) {
            target.focus();
            try { target.select(); } catch (_) { /* ignore */ }
        }
    }
});
</script>
@endpush
