<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('receptionist.dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('receptionist.dashboard')" :active="request()->routeIs('receptionist.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.bookings')" :active="request()->routeIs('receptionist.bookings')">
                                {{ __('Bookings') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.check-in')" :active="request()->routeIs('receptionist.check-in')">
                                {{ __('Check-in') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.check-out')" :active="request()->routeIs('receptionist.check-out')">
                                {{ __('Check-out') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.rooms')" :active="request()->routeIs('receptionist.rooms')">
                                {{ __('Rooms') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.guests')" :active="request()->routeIs('receptionist.guests')">
                                {{ __('Guests') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.transactions')" :active="request()->routeIs('receptionist.transactions')">
                                {{ __('Transactions') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.reports')" :active="request()->routeIs('receptionist.reports')">
                                {{ __('Reports') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div class="flex items-center">
                                        @auth
                                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover mr-2">
                                        <div>{{ Auth::user()->name }}</div>
                                        @else
                                        <img src="{{ asset('images/default-avatar.png') }}" alt="Guest" class="h-8 w-8 rounded-full object-cover mr-2">
                                        <div>Guest</div>
                                        @endauth
                                    </div>

                                    <div class="ml-1">
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
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('receptionist.dashboard')" :active="request()->routeIs('receptionist.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.bookings')" :active="request()->routeIs('receptionist.bookings')">
                        {{ __('Bookings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.check-in')" :active="request()->routeIs('receptionist.check-in')">
                        {{ __('Check-in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.check-out')" :active="request()->routeIs('receptionist.check-out')">
                        {{ __('Check-out') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.rooms')" :active="request()->routeIs('receptionist.rooms')">
                        {{ __('Rooms') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.guests')" :active="request()->routeIs('receptionist.guests')">
                        {{ __('Guests') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.transactions')" :active="request()->routeIs('receptionist.transactions')">
                        {{ __('Transactions') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.reports')" :active="request()->routeIs('receptionist.reports')">
                        {{ __('Reports') }}
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                            @auth
                            {{ Auth::user()->name }}
                            @else
                            Guest
                            @endauth
                        </div>
                        <div class="font-medium text-sm text-gray-500">
                            @auth
                            {{ Auth::user()->email }}
                            @else
                            guest@example.com
                            @endauth
                        </div>
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

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html> 