<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cahaya Resort Pangururuan') }} - Receptionist</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen" style="background:#1D1D1D;">
        <!-- Navigation -->
        <nav style="background:#2D2D2D; border-bottom:2px solid #FFA040;">
            <div class="px-4 mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex items-center flex-shrink-0">
                            <a href="{{ route('receptionist.dashboard') }}" class="text-xl font-bold text-white">
                                {{ config('app.name', 'Cahaya Resort Pangururan') }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('receptionist.dashboard')" :active="request()->routeIs('receptionist.dashboard')" class="text-gray-300 hover:text-white">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.bookings')" :active="request()->routeIs('receptionist.bookings*')" class="text-gray-300 hover:text-white">
                                Bookings
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.check-in')" :active="request()->routeIs('receptionist.check-in')" class="text-gray-300 hover:text-white">
                                Check-in
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.check-out')" :active="request()->routeIs('receptionist.check-out')" class="text-gray-300 hover:text-white">
                                Check-out
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.rooms')" :active="request()->routeIs('receptionist.rooms*')" class="text-gray-300 hover:text-white">
                                Rooms
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.guests')" :active="request()->routeIs('receptionist.guests*')" class="text-gray-300 hover:text-white">
                                Guests
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.transactions')" :active="request()->routeIs('receptionist.transactions*')" class="text-gray-300 hover:text-white">
                                Transactions
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.reports')" :active="request()->routeIs('receptionist.reports*')" class="text-gray-300 hover:text-white">
                                Reports
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-300 transition duration-150 ease-in-out hover:text-white focus:outline-none">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
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
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header style="background:#2D2D2D;box-shadow:0 2px 8px #0002;">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="py-6">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="px-4 py-2 mb-4 border rounded-lg bg-green-500/10 border-green-500/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-400">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="px-4 py-2 mb-4 border rounded-lg bg-red-500/10 border-red-500/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-400">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>
</html> 