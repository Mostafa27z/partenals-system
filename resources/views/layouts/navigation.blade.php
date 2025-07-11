<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-9 w-auto text-gray-800" />
                </a>

                <!-- Back Button -->
                {{-- <a href="{{ url()->previous() }}" class="hidden sm:inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded hover:bg-gray-200 transition">
                    ⬅ {{ __('messages.back') }}
                </a> --}}

                <!-- Nav Links -->
                <div class="hidden sm:flex gap-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('messages.dashboard') }}</x-nav-link>
                    <x-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')">{{ __('messages.plans') }}</x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">{{ __('messages.customers') }}</x-nav-link>
                    <x-nav-link :href="route('lines.all')" :active="request()->routeIs('lines.*')">{{ __('messages.lines') }}</x-nav-link>
                    <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">{{ __('messages.invoices') }}</x-nav-link>
                    <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">{{ __('messages.permissions') }}</x-nav-link>
                    <x-nav-link :href="route('change-logs.index')" :active="request()->routeIs('change-logs.*')">{{ __('messages.change_log') }}</x-nav-link>

                    <!-- Requests Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-1 px-3 py-2 text-gray-600 hover:text-gray-800 transition">
                            {{ __('messages.requests') }}
                            <svg class="h-4 w-4 transition" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             class="absolute mt-2 bg-white rounded shadow-lg border z-50 w-48">
                            <a href="{{ route('requests.all') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📄 {{ __('messages.all_requests') }}</a>
                            <a href="{{ route('requests.summary') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📊 {{ __('messages.summary') }}</a>
                            <a href="{{ route('requests.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🕓 {{ __('messages.history') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language Switcher + Settings -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- Language Switcher -->
                <div>
                    @php
                        $currentLocale = app()->getLocale();
                        $newLocale = $currentLocale === 'ar' ? 'en' : 'ar';
                    @endphp
                    <a href="{{ route('lang.switch', $newLocale) }}"
                       class="text-sm text-gray-600 hover:text-blue-600 transition">
                        🌐 {{ __('messages.language_' . $newLocale) }}
                    </a>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('messages.profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('messages.logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu for Mobile -->
            <div class="sm:hidden flex items-center">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden px-4 pb-4">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('messages.dashboard') }}</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')">{{ __('messages.plans') }}</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">{{ __('messages.customers') }}</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('lines.all')" :active="request()->routeIs('lines.*')">{{ __('messages.lines') }}</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">{{ __('messages.invoices') }}</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">{{ __('messages.permissions') }}</x-responsive-nav-link>

        <div class="pt-4 border-t border-gray-200">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>

        <x-responsive-nav-link :href="route('profile.edit')">{{ __('messages.profile') }}</x-responsive-nav-link>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('messages.logout') }}
            </x-responsive-nav-link>
        </form>

        <!-- Language Switch in mobile -->
        <x-responsive-nav-link :href="route('lang.switch', $newLocale)">
            🌐 {{ __('messages.language_' . $newLocale) }}
        </x-responsive-nav-link>
    </div>
</nav>
