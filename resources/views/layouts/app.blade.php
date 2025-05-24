<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cahaya Resort')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .custom-curve {
            position: relative;
            background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 100%);
            padding: 20px 40px;
            clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%);
            box-shadow: 0 8px 15px -3px rgba(0,0,0,0.4);
            transition: all 0.5s ease;
            width: auto;
            display: inline-block;
            animation: narrow 0.5s ease forwards;
            font-family: 'Poppins', sans-serif;
        }
        .custom-curve.scrolled {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
            background: linear-gradient(to bottom, #FFD700 0%, #FFC300 100%);
            width: 100%;
            padding: 20px 0;
            animation: widen 0.5s ease forwards;
        }
        .nav-container {
            display: flex;
            justify-content: center;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }
        @keyframes widen {
            from {
                clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%);
                background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 100%);
            }
            to {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
                background: linear-gradient(to bottom, #FFD700 0%, #FFC300 100%);
            }
        }
        @keyframes narrow {
            from {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
                background: linear-gradient(to bottom, #FFD700 0%, #FFC300 100%);
            }
            to {
                clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%);
                background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 100%);
            }
        }
        .dropdown-menu {
            display: none;
            font-family: 'Poppins', sans-serif;
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #374151;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }
        .dropdown-item:hover {
            background-color: #F3F4F6;
        }
        .dropdown-item i {
            margin-right: 0.5rem;
            width: 1.25rem;
            text-align: center;
        }
        .dropdown-button.active {
            color: #F3F4F6;
        }
        .dropdown-button i {
            transition: transform 0.2s ease;
        }
        .dropdown-button.active i {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        /* Shared Panel Styles */
        .slide-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 30;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.4s ease;
            font-family: 'Poppins', sans-serif;
        }

        .slide-content {
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            height: calc(100% - 80px);
            background: white;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            pointer-events: auto;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
            font-family: 'Poppins', sans-serif;
        }

        .slide-content-inner {
            padding: 2rem;
            font-family: 'Poppins', sans-serif;
        }

        .slide-panel.show {
            pointer-events: auto;
            opacity: 1;
        }

        .slide-panel.show .slide-content {
            transform: translateY(0);
        }

        /* Back button styling */
        .back-button {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            color: #4B5563;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 0.375rem;
            background: white;
            border: 1px solid #E5E7EB;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .back-button:hover {
            color: #1F2937;
            transform: translateX(-2px);
        }

        .back-button svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }

        /* Active nav item */
        .nav-item {
            position: relative;
            padding-bottom: 2px;
            font-family: 'Poppins', sans-serif;
        }

        .nav-item::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #4B5563;
            transition: width 0.3s ease;
        }

        .nav-item.active::after {
            width: 100%;
        }

        .nav-item:hover::after {
            width: 100%;
        }

        /* Updated Notification styles */
        .notification-container {
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            top: 80px; /* Position below navbar */
            width: 100%;
            max-width: 600px;
            z-index: 49;
            display: flex;
            justify-content: center;
            pointer-events: none;
            font-family: 'Poppins', sans-serif;
        }

        .notification {
            background: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease-in-out;
            pointer-events: auto;
            font-family: 'Poppins', sans-serif;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification.success {
            background: #10B981;
            color: white;
        }

        .notification.error {
            background: #EF4444;
            color: white;
        }

        .notification i {
            font-size: 1.25rem;
        }

        .notification-message {
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
        }

        /* Animation for notifications */
        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .notification.animate {
            animation: slideDown 0.3s ease-out forwards;
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
            font-family: 'Poppins', sans-serif;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 50;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #374151;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: 'Poppins', sans-serif;
        }

        .dropdown-item:hover {
            background-color: #F3F4F6;
        }

        .dropdown-item i {
            margin-right: 0.75rem;
            width: 1rem;
            text-align: center;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #E5E7EB;
            margin: 0.25rem 0;
        }

        /* Active state for dropdown button */
        .dropdown-button.active {
            color: #F3F4F6;
        }

        .dropdown-button i {
            transition: transform 0.2s;
            margin-left: 0.5rem;
        }

        .dropdown-button.active i {
            transform: rotate(180deg);
        }

        .trapezoid-small {
            clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%);
            box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);
            border-radius: 0 0 32px 32px;
            transition: width 0.5s cubic-bezier(.4,0,.2,1), height 0.5s cubic-bezier(.4,0,.2,1), background 0.5s, clip-path 0.5s;
        }
        .trapezoid-scroll {
            clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);
            box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);
            border-radius: 0 0 32px 32px;
            transition: width 0.5s cubic-bezier(.4,0,.2,1), height 0.5s cubic-bezier(.4,0,.2,1), background 0.5s, clip-path 0.5s;
        }

        /* Button styles */
        .btn-signup {
            color: #FFA040;
            text-transform: uppercase;
            text-decoration: none;
            border: 2px solid #FFA040;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            font-weight: bolder;
            background: transparent;
            position: relative;
            transition: all 1s;
            overflow: hidden;
            z-index: 1;
            border-radius: 9999px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-signup:hover {
            color: #fff;
        }

        .btn-signup::before {
            content: "";
            position: absolute;
            height: 100%;
            width: 0%;
            top: 0;
            left: -40px;
            transform: skewX(45deg);
            background-color: #FFA040;
            z-index: -1;
            transition: all 1s;
        }

        .btn-signup:hover::before {
            width: 160%;
        }

        .btn-login {
            color: #FFA040;
            text-transform: uppercase;
            text-decoration: none;
            border: 2px solid #FFA040;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            font-weight: bolder;
            background: transparent;
            position: relative;
            transition: all 1s;
            overflow: hidden;
            z-index: 1;
            border-radius: 9999px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-login:hover {
            color: #fff;
        }

        .btn-login::before {
            content: "";
            position: absolute;
            height: 100%;
            width: 0%;
            top: 0;
            left: -40px;
            transform: skewX(45deg);
            background-color: #FFA040;
            z-index: -1;
            transition: all 1s;
        }

        .btn-login:hover::before {
            width: 160%;
        }

        @keyframes slide-in-right {
            0% {
                opacity: 0;
                transform: translateX(60px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .btn-animate {
            opacity: 0;
            animation: slide-in-right 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        .btn-login.btn-animate {
            animation-delay: 1.5s;
        }

        .btn-signup.btn-animate {
            animation-delay: 1.7s;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav x-data="{ scrolled: false }"
     @scroll.window="scrolled = window.pageYOffset > 50"
     class="fixed top-0 z-50 w-full bg-transparent">
    <div class="relative flex items-center justify-between px-4 mx-auto h-14 max-w-7xl sm:px-6 lg:px-8">
            <!-- Trapesium Background -->
            <div
            class="absolute top-0 transition-all duration-500 ease-in-out"
            :style="scrolled
                ? 'width: 100vw; height: 56px; background: linear-gradient(to right, #3D3D3D, #2E2E2E); clip-path: polygon(0 0, 100% 0, 92% 100%, 8% 100%); left: 50%; transform: translateX(-50%);'
                : 'width: 520px; height: 56px; background: #fff; clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%); left: calc(50% + -7px); transform: translateX(-50%);'"
            style="z-index: 10;">
        </div>
            <!-- Logo -->
            <div class="z-20 flex items-center">
                <a href="/" 
                class="text-2xl font-regular text-white font-poppins drop-shadow-md transition-all duration-300">
                    Cahaya Resort
                </a>
            </div>
            <!-- Center Navigation -->
            <div class="z-20 flex justify-center flex-1">
                <div class="flex items-center space-x-16" x-data="{ activeTab: localStorage.getItem('activeTab') || 'dashboard' }">
                    <button @click="activeTab = 'dashboard'; hidePanel(); localStorage.setItem('activeTab', 'dashboard')"
                       class="text-base font-medium transition-all duration-300 nav-item"
                       :class="{ 'active': activeTab === 'dashboard', 'text-white': scrolled, 'text-gray-700': !scrolled }">
                        Dashboard
                    </button>
                    <button @click="activeTab = 'rooms'; showRooms(); localStorage.setItem('activeTab', 'rooms')"
                       class="text-base font-medium transition-all duration-300 nav-item"
                       :class="{ 'active': activeTab === 'rooms', 'text-white': scrolled, 'text-gray-700': !scrolled }">
                        Rooms
                    </button>
                    <a href="{{ route('galeri') }}"
                       @click="activeTab = 'gallery'; localStorage.setItem('activeTab', 'gallery')"
                       class="text-base font-medium transition-all duration-300 nav-item"
                       :class="{ 'active': activeTab === 'gallery' || '{{ request()->routeIs('galeri') }}' === '1', 'text-white': scrolled, 'text-gray-700': !scrolled }">
                        Gallery
                    </a>
                </div>
            </div>
            <!-- Auth Buttons -->
            <div class="z-20 flex items-center space-x-4 navbar-auth">
                @auth
                    <div class="dropdown">
                        <button type="button"
                                class="flex items-center font-medium text-gray-700 transition-all duration-300 dropdown-button hover:text-gray-900">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="ml-1 text-sm fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
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
                @else
                    <a href="{{ route('login') }}" class="btn-login btn-animate">Log In</a>
                    <a href="{{ route('register') }}" class="btn-signup btn-animate">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Updated Notification Container -->
    <div class="notification-container">
        <div id="notification" class="notification" role="alert">
            <i class="fas fa-check-circle"></i>
            <span id="notification-message" class="notification-message"></span>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Rooms Panel -->
    <div class="slide-panel rooms-panel" id="roomsPanel">
        <div class="slide-content">
            <div class="slide-content-inner">
                <!-- Back Button -->
                <button onclick="hidePanel()" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    Back
                </button>
                <div id="roomsContent"></div>
                </div>
                </div>
            </div>
            
    <!-- Booking Panel -->
    <div class="slide-panel booking-panel" id="bookingPanel">
        <div class="slide-content">
            <div class="slide-content-inner">
                <!-- Back Button -->
                <button onclick="hidePanel('rooms')" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Rooms
                </button>
                <div id="bookingContent"></div>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomsPanel = document.getElementById('roomsPanel');
        const bookingPanel = document.getElementById('bookingPanel');
        const roomsContent = document.getElementById('roomsContent');
        const bookingContent = document.getElementById('bookingContent');

        // Set initial active tab based on URL
        if (window.location.pathname.includes('/rooms')) {
            localStorage.setItem('activeTab', 'rooms');
        } else if (window.location.pathname.includes('/galeri')) {
            localStorage.setItem('activeTab', 'gallery');
        } else {
            localStorage.setItem('activeTab', 'dashboard');
        }

        window.showRooms = async function() {
            try {
                const response = await fetch('{{ route("kamar.index") }}');
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const mainContent = doc.querySelector('.min-h-screen');
                
                if (mainContent) {
                    roomsContent.innerHTML = mainContent.innerHTML;
                    hidePanel();
                    roomsPanel.classList.add('show');
                    bindPanelPagination();
                }
            } catch (error) {
                console.error('Error loading rooms:', error);
            }
        };

        // Fungsi untuk rebind pagination di panel
        function bindPanelPagination() {
            document.querySelectorAll('#roomsPanel #pagination-links a').forEach(link => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const page = this.href.split('page=')[1];
                    const roomsContainer = document.querySelector('#roomsPanel #rooms-container');
                    try {
                        const response = await fetch(`{{ route('kamar.index') }}?page=${page}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        roomsContainer.innerHTML = html;
                        bindPanelPagination(); // rebind lagi setelah update
                    } catch (error) {
                        console.error('Error loading rooms:', error);
                    }
                });
            });
        }

        window.showBooking = async function(roomIds) {
            try {
                const queryString = roomIds.map(id => `room_ids[]=${id}`).join('&');
                const response = await fetch(`{{ route("bookings.create") }}?${queryString}`);
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const mainContent = doc.querySelector('main');
                
                if (mainContent) {
                    bookingContent.innerHTML = mainContent.innerHTML;
                    hidePanel();
                    bookingPanel.classList.add('show');
                    // Dispatch event when booking panel is shown
                    document.dispatchEvent(new Event('bookingPanelShown'));
                }
            } catch (error) {
                console.error('Error loading booking form:', error);
            }
        };

        window.hidePanel = function(showPanel = null) {
            roomsPanel.classList.remove('show');
            bookingPanel.classList.remove('show');

            if (showPanel === 'rooms') {
                roomsPanel.classList.add('show');
            }
        };

        // Initialize any existing panels
        if (window.location.pathname.includes('/rooms')) {
            showRooms();
        }

        // Updated showNotification function
        window.showNotification = function(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            
            // Reset classes
            notification.className = 'notification';
            
            // Set message and type
            messageEl.textContent = message;
            notification.classList.add(type);
            
            // Force a reflow to restart animation
            notification.offsetHeight;
            
            // Add show and animate classes
            notification.classList.add('show', 'animate');
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show', 'animate');
                setTimeout(() => {
                    notification.className = 'notification';
                }, 300);
            }, 3000);
        };

        // Logout confirmation
        window.confirmLogout = function() {
            Swal.fire({
                title: 'Logout Confirmation',
                text: "Are you sure you want to logout?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        };

        // Show welcome notification if just logged in
        @if(session('login_success'))
            showNotification('Welcome back, {{ Auth::user()->name }}! 👋', 'success');
        @endif

        // Show logout notification
        @if(session('logout_success'))
            showNotification('You have been successfully logged out!', 'success');
        @endif

        // Show any flash messages
        @if(session('success'))
            showNotification('{{ session("success") }}', 'success');
        @endif

        @if(session('error'))
            showNotification('{{ session("error") }}', 'error');
        @endif

        // Dropdown functionality
        function initializeDropdown() {
            const dropdownButton = document.querySelector('.dropdown-button');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            let isOpen = false;

            if (!dropdownButton || !dropdownMenu) return;

            function toggleDropdown(event) {
                event.stopPropagation();
                isOpen = !isOpen;
                dropdownButton.classList.toggle('active');
                dropdownMenu.classList.toggle('show');
            }

            function closeDropdown() {
                if (!isOpen) return;
                isOpen = false;
                dropdownButton.classList.remove('active');
                dropdownMenu.classList.remove('show');
            }

            // Toggle dropdown on button click
            dropdownButton.addEventListener('click', toggleDropdown);

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInside = dropdownButton.contains(event.target) || 
                                    dropdownMenu.contains(event.target);
                if (!isClickInside) {
                    closeDropdown();
                }
            });

            // Close dropdown when pressing Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeDropdown();
                }
            });
        }

        // Initialize dropdown
        initializeDropdown();

        const customCurve = document.querySelector('.custom-curve.scroll-trigger');
        // Set initial state
        let isScrolled = false;
        window.addEventListener('scroll', function() {
            const scrollThreshold = 100;
            if (window.scrollY > scrollThreshold && !isScrolled) {
                customCurve.classList.add('scrolled');
                isScrolled = true;
            } else if (window.scrollY <= scrollThreshold && isScrolled) {
                customCurve.classList.remove('scrolled');
                isScrolled = false;
            }
        });
        function handleResize() {
            if (window.innerWidth < 768) {
                customCurve.classList.add('scrolled');
            } else {
                if (window.scrollY > 100) {
                    customCurve.classList.add('scrolled');
                } else {
                    customCurve.classList.remove('scrolled');
                }
            }
        }
        handleResize();
        window.addEventListener('resize', handleResize);
    });
    </script>
</body>
</html>