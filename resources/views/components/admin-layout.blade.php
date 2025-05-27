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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navbar -->
        <nav x-data="{ scrolled: false }"
             @scroll.window="scrolled = window.pageYOffset > 50"
             class="fixed top-0 z-50 w-full bg-transparent">
            <div class="relative flex items-center justify-between px-4 mx-auto h-14 max-w-7xl sm:px-6 lg:px-8">
                <!-- Trapesium Background -->
                <div class="absolute top-0 transition-all duration-500 ease-in-out"
                     :style="scrolled
                         ? 'width: 100vw; height: 56px; background: linear-gradient(to right, #3D3D3D, #2E2E2E); clip-path: polygon(0 0, 100% 0, 92% 100%, 8% 100%); left: 50%; transform: translateX(-50%);'
                         : 'width: 100vw; height: 56px; background: linear-gradient(to right, #FFA040, #ff8c1a); clip-path: polygon(0 0, 100% 0, 92% 100%, 8% 100%); left: 50%; transform: translateX(-50%);'"
                     style="z-index: 10;">
                </div>

                <!-- Logo -->
                <div class="z-20 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="text-2xl font-regular font-poppins drop-shadow-md transition-all duration-300"
                       :class="{ 'text-white': scrolled, 'text-gray-800': !scrolled }">
                        Cahaya Resort
                    </a>
                </div>

                <!-- Center Navigation -->
                <div class="z-20 flex justify-center flex-1">
                    <div class="flex items-center space-x-16">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-base font-medium transition-all duration-300 nav-item"
                           :class="{ 'active': '{{ request()->routeIs('admin.dashboard') }}' === '1', 'text-white': scrolled, 'text-gray-800': !scrolled }">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.rooms.index') }}" 
                           class="text-base font-medium transition-all duration-300 nav-item"
                           :class="{ 'active': '{{ request()->routeIs('admin.rooms.*') }}' === '1', 'text-white': scrolled, 'text-gray-800': !scrolled }">
                            Rooms
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-base font-medium transition-all duration-300 nav-item"
                           :class="{ 'active': '{{ request()->routeIs('admin.users.*') }}' === '1', 'text-white': scrolled, 'text-gray-800': !scrolled }">
                            Users
                        </a>
                        <a href="{{ route('admin.reports.index') }}" 
                           class="text-base font-medium transition-all duration-300 nav-item"
                           :class="{ 'active': '{{ request()->routeIs('admin.reports.*') }}' === '1', 'text-white': scrolled, 'text-gray-800': !scrolled }">
                            Reports
                        </a>
                    </div>
                </div>

                <!-- Auth Buttons -->
                <div class="z-20 flex items-center space-x-4 navbar-auth">
                    <div class="dropdown" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center font-medium transition-all duration-300 dropdown-button"
                                :class="{ 'text-white': scrolled, 'text-gray-800': !scrolled }">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="ml-1 text-sm fas fa-chevron-down" :class="{ 'transform rotate-180': open }"></i>
                        </button>
                        <div class="dropdown-menu" x-show="open" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="confirmLogout()" class="text-red-600 dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-16">
            {{ $slot }}
        </main>
    </div>

    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            margin-top: 8px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #374151;
            transition: background-color 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 16px;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 4px 0;
        }

        .nav-item {
            position: relative;
            font-family: 'Poppins', sans-serif;
        }

        .nav-item::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: currentColor;
            transition: width 0.3s ease;
        }

        .nav-item:hover::after {
            width: 100%;
        }

        .nav-item.active {
            font-weight: 600;
        }

        .nav-item.active::after {
            width: 100%;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        /* Mobile menu styles */
        @media (max-width: 768px) {
            .nav-item {
                display: none;
            }
        }
    </style>

    <script>
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
</body>
</html>