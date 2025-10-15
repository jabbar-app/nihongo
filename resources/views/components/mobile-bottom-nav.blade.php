@props(['currentRoute' => ''])

<!-- Mobile Bottom Navigation - Only visible on mobile devices -->
<nav 
    class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-lg sm:hidden"
    style="padding-bottom: env(safe-area-inset-bottom); height: calc(64px + env(safe-area-inset-bottom));"
    role="navigation" 
    aria-label="Mobile bottom navigation"
>
    <div class="flex items-center justify-around h-16 px-2">
        <!-- Home -->
        <a 
            href="{{ route('dashboard') }}" 
            class="flex flex-col items-center justify-center flex-1 min-w-0 py-2 px-1 transition-colors duration-150 group {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}"
            aria-label="Home"
            aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}"
        >
            <svg 
                class="w-6 h-6 mb-1 transition-transform duration-150 {{ request()->routeIs('dashboard') ? 'scale-110' : 'group-hover:scale-105' }}" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs font-medium truncate {{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}">
                Home
            </span>
        </a>

        <!-- Conversations (Lessons) -->
        <a 
            href="{{ route('lessons.index') }}" 
            class="flex flex-col items-center justify-center flex-1 min-w-0 py-2 px-1 transition-colors duration-150 group {{ request()->routeIs('lessons.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}"
            aria-label="Conversations"
            aria-current="{{ request()->routeIs('lessons.*') ? 'page' : 'false' }}"
        >
            <svg 
                class="w-6 h-6 mb-1 transition-transform duration-150 {{ request()->routeIs('lessons.*') ? 'scale-110' : 'group-hover:scale-105' }}" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <span class="text-xs font-medium truncate {{ request()->routeIs('lessons.*') ? 'font-semibold' : '' }}">
                Conversations
            </span>
        </a>

        <!-- Practice (Exercises) -->
        <a 
            href="{{ route('exercises.history') }}" 
            class="flex flex-col items-center justify-center flex-1 min-w-0 py-2 px-1 transition-colors duration-150 group {{ request()->routeIs('exercises.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}"
            aria-label="Practice"
            aria-current="{{ request()->routeIs('exercises.*') ? 'page' : 'false' }}"
        >
            <svg 
                class="w-6 h-6 mb-1 transition-transform duration-150 {{ request()->routeIs('exercises.*') ? 'scale-110' : 'group-hover:scale-105' }}" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-xs font-medium truncate {{ request()->routeIs('exercises.*') ? 'font-semibold' : '' }}">
                Practice
            </span>
        </a>

        <!-- Progress -->
        <a 
            href="{{ route('progress.index') }}" 
            class="flex flex-col items-center justify-center flex-1 min-w-0 py-2 px-1 transition-colors duration-150 group {{ request()->routeIs('progress.*') || request()->routeIs('achievements.*') || request()->routeIs('streak.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}"
            aria-label="Progress"
            aria-current="{{ request()->routeIs('progress.*') || request()->routeIs('achievements.*') || request()->routeIs('streak.*') ? 'page' : 'false' }}"
        >
            <svg 
                class="w-6 h-6 mb-1 transition-transform duration-150 {{ request()->routeIs('progress.*') || request()->routeIs('achievements.*') || request()->routeIs('streak.*') ? 'scale-110' : 'group-hover:scale-105' }}" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="text-xs font-medium truncate {{ request()->routeIs('progress.*') || request()->routeIs('achievements.*') || request()->routeIs('streak.*') ? 'font-semibold' : '' }}">
                Progress
            </span>
        </a>

        <!-- Profile -->
        <a 
            href="{{ route('profile.edit') }}" 
            class="flex flex-col items-center justify-center flex-1 min-w-0 py-2 px-1 transition-colors duration-150 group {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}"
            aria-label="Profile"
            aria-current="{{ request()->routeIs('profile.*') ? 'page' : 'false' }}"
        >
            <svg 
                class="w-6 h-6 mb-1 transition-transform duration-150 {{ request()->routeIs('profile.*') ? 'scale-110' : 'group-hover:scale-105' }}" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs font-medium truncate {{ request()->routeIs('profile.*') ? 'font-semibold' : '' }}">
                Profile
            </span>
        </a>
    </div>
</nav>
