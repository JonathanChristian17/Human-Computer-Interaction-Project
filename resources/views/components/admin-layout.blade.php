<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cahaya Resort') }} - Admin</title>

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
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#1D1D1D]">
        <!-- Admin Navigation -->
        <nav class="nav-container">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}">
                                <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort" class="h-8 w-8">
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.rooms.index')" :active="request()->routeIs('admin.rooms.*')">
                                {{ __('Rooms') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                                {{ __('Reports') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md" style="color:#fff;background:#2D2D2D;transition:all 0.2s;" onmouseover="this.style.color='#FFA040'" onmouseout="this.style.color='#fff'">
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
                </div>
            </div>
        </nav>

        <!-- Header -->
        @if (isset($header))
            <header class="bg-[#252525] shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="content-container">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-4 bg-[#252525] px-4 py-3 rounded-lg shadow-sm border border-green-500" role="alert">
                            <span class="text-green-400 font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-[#252525] px-4 py-3 rounded-lg shadow-sm border border-red-500" role="alert">
                            <span class="text-red-400 font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form.needs-confirm').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!form.dataset.confirmed) {
                    e.preventDefault();
                    const message = form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan proses ini?';
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#FFA040',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (form.reportValidity()) {
                                form.dataset.confirmed = 'true';
                                form.submit();
                            } else {
                                Swal.fire({
                                    title: 'Form Tidak Valid',
                                    text: 'Silakan lengkapi semua field yang wajib diisi atau perbaiki data yang salah.',
                                    icon: 'error',
                                    confirmButtonColor: '#FFA040',
                                });
                            }
                        }
                    });
                } else {
                    // Reset flag agar form bisa digunakan ulang tanpa reload
                    form.dataset.confirmed = '';
                }
            });
        });
        document.querySelectorAll('.needs-confirm-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (!link.dataset.confirmed) {
                    e.preventDefault();
                    const message = link.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan proses ini?';
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#FFA040',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            link.dataset.confirmed = true;
                            window.location = link.href;
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>