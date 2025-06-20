<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Cahaya Resort Pangururan')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #1D1D1D;
        }
        .nav-container {
            background: #1D1D1D;
            border-bottom: 2px solid #FFA040;
        }
        .nav-link {
            color: #fff;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 0 2px;
        }
        .nav-link:hover {
            color: #FFA040;
            background: rgba(255, 160, 64, 0.1);
            transform: translateY(-2px);
        }
        .nav-link.active {
            color: #FFA040;
            background: rgba(255, 160, 64, 0.18);
            box-shadow: 0 2px 8px rgba(255, 160, 64, 0.10);
        }
        .nav-link, .nav-link.active {
            border-bottom: none !important;
            box-shadow: none !important;
        }
        .nav-link.active::after {
            content: '';
            display: block;
            position: absolute;
            left: 20%;
            right: 20%;
            bottom: 6px;
            height: 3px;
            background: #FFA040;
            border-radius: 2px;
        }
        .nav-link.active:hover {
            background: rgba(255, 160, 64, 0.22);
            transform: translateY(-2px);
        }
        .content-container {
            background: #1D1D1D;
            color: #fff;
        }
        .card {
            background: #252525;
            border: 1px solid #FFA040;
            border-radius: 12px;
        }
        .btn-primary {
            background: #FFA040;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #ff8c1a;
        }
        .table-container {
            background: #252525;
            border-radius: 12px;
            border: 1px solid #FFA040;
        }
        .table-header {
            background: #FFA040;
            color: #fff;
        }
        .table-row {
            border-bottom: 1px solid #FFA040;
        }
        .table-row:last-child {
            border-bottom: none;
        }
        /* Responsive nav link styles */
        .responsive-nav-link {
            color: #fff;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 2px 0;
        }
        .responsive-nav-link:hover {
            color: #FFA040;
            background: rgba(255, 160, 64, 0.1);
            transform: translateX(4px);
        }
        .responsive-nav-link.active {
            color: #FFA040;
            background: rgba(255, 160, 64, 0.15);
            box-shadow: 0 2px 4px rgba(255, 160, 64, 0.2);
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#1D1D1D]">
        <nav class="nav-container">
            <!-- Primary Navigation Menu -->
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex items-center shrink-0">
                            <a href="{{ route('receptionist.dashboard') }}">
                                <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort" class="w-8 h-8">
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
                            <x-nav-link :href="route('receptionist.offline-booking')" :active="request()->routeIs('receptionist.offline-booking')">
                                {{ __('Offline Booking') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.check-in')" :active="request()->routeIs('receptionist.check-in')">
                                {{ __('Check-in') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.check-out')" :active="request()->routeIs('receptionist.check-out')">
                                {{ __('Check-out') }}
                            </x-nav-link>
                            <x-nav-link :href="route('receptionist.bookings.completed')" :active="request()->routeIs('receptionist.bookings.completed')">
                                {{ __('Riwayat Selesai') }}
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
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 border border-transparent rounded-md" style="color:#fff;background:#2D2D2D;transition:all 0.2s;" onmouseover="this.style.color='#FFA040'" onmouseout="this.style.color='#fff'">
                                    <div class="flex items-center">
                                        @auth
                                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="object-cover w-8 h-8 mr-2 rounded-full">
                                        <div>{{ Auth::user()->name }}</div>
                                        @else
                                        <img src="{{ asset('images/default-avatar.png') }}" alt="Guest" class="object-cover w-8 h-8 mr-2 rounded-full">
                                        <div>Guest</div>
                                        @endauth
                                    </div>

                                    <div class="ml-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">

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
                    <div class="flex items-center -mr-2 sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
                            <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
                    <x-responsive-nav-link :href="route('receptionist.offline-booking')" :active="request()->routeIs('receptionist.offline-booking')">
                        {{ __('Offline Booking') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.check-in')" :active="request()->routeIs('receptionist.check-in')">
                        {{ __('Check-in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.check-out')" :active="request()->routeIs('receptionist.check-out')">
                        {{ __('Check-out') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('receptionist.bookings.completed')" :active="request()->routeIs('receptionist.bookings.completed')">
                        {{ __('Riwayat Selesai') }}
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
                        @auth
                            <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        @endauth
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
            <header class="bg-[#252525] shadow">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="content-container">
            {{ $slot }}
        </main>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html> 