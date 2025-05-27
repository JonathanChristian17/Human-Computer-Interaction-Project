<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cahaya Resort')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('head')
</head>
<body class="bg-white">
    <!-- Include Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Base Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Show welcome notification if just logged in
            @if(session('login_success'))
                @auth
                showNotification('Welcome back, {{ Auth::user()->name }}! 👋', 'success');
                @else
                showNotification('Welcome back! 👋', 'success');
                @endauth
            @endif

            // Show logout notification
            @if(session('logout_success'))
                showNotification('You have been successfully logged out!', 'success');
            @endif

            // Show any flash messages
            @if(session('success'))
                @auth
                showNotification('Welcome back, {{ Auth::user()->name }}! 👋', 'success');
                @else
                showNotification('{{ session('success') }}', 'success');
                @endauth
            @endif

            @if(session('error'))
                showNotification('{{ session("error") }}', 'error');
            @endif
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html> 