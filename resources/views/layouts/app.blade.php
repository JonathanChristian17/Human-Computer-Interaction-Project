<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- Midtrans -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @yield('head')
    <script>window.isAuthenticated = @json(Auth::check());</script>
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

        /* Navbar styles */
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

        /* Scrollbar Styling */
        * {
            scrollbar-width: thin;  /* For Firefox */
            scrollbar-color: rgba(75, 85, 99, 0.6) transparent;  /* For Firefox - dark gray with transparency */
        }

        /* Chrome, Safari, Edge Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
            background-color: transparent;
        }

        ::-webkit-scrollbar-track {
            background-color: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.6); /* Dark gray with 60% opacity */
            border-radius: 3px;
            transition: background-color 0.2s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(75, 85, 99, 0.8); /* Darker on hover */
        }

        /* Make scrollbars overlay content */
        .overlay-scrollbar {
            overflow: overlay !important;  /* Modern browsers */
            -ms-overflow-style: -ms-autohiding-scrollbar;  /* IE/Edge */
        }

        /* Fallback for browsers that don't support overlay */
        @supports not (overflow: overlay) {
            .overlay-scrollbar {
                overflow: auto !important;
            }
        }

        .dropdown-menu {
            display: none;
        }
        .dropdown-menu[x-show="open"] {
            display: block !important;
        }
        .dropdown-menu[x-cloak] {
            display: none !important;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #374151;
            transition: all 0.2s ease;
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
        }

        .slide-content-inner {
            position: relative;
            padding: 2rem;
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

        /* Custom trapezoid navbar */
        .trapezoid-navbar {
            clip-path: polygon(0 0, 100% 0, 90% 100%, 10% 100%);
            /* Polygon membentuk trapesium terbalik */
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            z-index: 10;
            position: relative;
        }
        .dark .trapezoid-navbar {
            background: #1f2937;
        }

        [x-cloak] { 
            display: none !important; 
        }

        .profile-dropdown {
            position: relative;
            min-width: 160px;
            font-family: 'Poppins', sans-serif;
        }

        .profile-dropdown-button {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            border-radius: 16px;
            background: transparent;
            color: #fff;
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
            overflow: hidden;
        }

        .profile-dropdown-button::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #FFA040;
            z-index: -1;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.48s cubic-bezier(0.23, 1, 0.32, 1);
            opacity: 0;
        }

        .profile-dropdown-button:hover::after,
        .profile-dropdown-button.active::after {
            transform: scaleX(1);
            transform-origin: right;
            opacity: 1;
        }

        .profile-dropdown-button:hover,
        .profile-dropdown-button.active {
            color: #fff;
        }

        .profile-dropdown-button i.fa-chevron-down {
            transition: transform 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .profile-dropdown-button.active i.fa-chevron-down {
            transform: rotate(-180deg);
        }

        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 180px;
            background: #18191c;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.18);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-12px);
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 10001;
            pointer-events: none;
            border: 1px solid #FFA040;
            border-top: none;
            overflow: hidden;
        }

        .profile-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .profile-dropdown-menu .dropdown-item {
            position: relative;
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
            overflow: hidden;
            border-radius: 12px;
            margin: 4px 8px;
            width: calc(100% - 16px);
        }

        .profile-dropdown-menu .dropdown-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #FFA040;
            z-index: -1;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.48s cubic-bezier(0.23, 1, 0.32, 1);
            opacity: 0;
        }

        .profile-dropdown-menu .dropdown-item:hover::before {
            transform: scaleX(1);
            transform-origin: right;
            opacity: 1;
        }

        .profile-dropdown-menu .dropdown-item:hover {
            color: #fff;
        }

        .profile-dropdown-menu .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 8px 16px;
        }

        .profile-dropdown-menu .dropdown-item.text-red-600 {
            color: #ef4444;
        }

        .profile-dropdown-menu .dropdown-item.text-red-600:hover {
            color: #fff;
        }

        .brutalist-card {
          width: 320px;
          border: 4px solid #000;
          background-color: #fff;
          padding: 1.5rem;
          box-shadow: 10px 10px 0 #000;
          font-family: "Arial", sans-serif;
          position: fixed;
          top: 50%;
          left: 50%;
          z-index: 9999;
          transform: translate(-50%, -50%);
          animation: fadeIn .2s;
        }
        @keyframes fadeIn {
          from { opacity: 0; transform: translate(-50%,-60%);}
          to   { opacity: 1; transform: translate(-50%,-50%);}
        }
        .brutalist-card__header {
          display: flex;
          align-items: center;
          gap: 1rem;
          margin-bottom: 1rem;
          border-bottom: 2px solid #000;
          padding-bottom: 1rem;
        }
        .brutalist-card__icon {
          flex-shrink: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          background-color: #000;
          padding: 0.5rem;
          border-radius: 8px;
        }
        .brutalist-card__icon svg {
          height: 1.5rem;
          width: 1.5rem;
          fill: #fff;
        }
        .brutalist-card__icon.success { background: #22c55e; }
        .brutalist-card__icon.danger  { background: #ef4444; }
        .brutalist-card__icon.warning { background: #facc15; }
        .brutalist-card__icon.success svg { fill: #fff; }
        .brutalist-card__icon.danger svg  { fill: #fff; }
        .brutalist-card__icon.warning svg { fill: #000; }
        .brutalist-card__alert {
          font-weight: 900;
          color: #000;
          font-size: 1.5rem;
          text-transform: uppercase;
        }
        .brutalist-card__message {
          margin-top: 1rem;
          color: #000;
          font-size: 0.9rem;
          line-height: 1.4;
          border-bottom: 2px solid #000;
          padding-bottom: 1rem;
          font-weight: 600;
        }
        .brutalist-card__actions {
          margin-top: 1rem;
        }
        .brutalist-card__button {
          display: block;
          width: 100%;
          padding: 0.75rem;
          text-align: center;
          font-size: 1rem;
          font-weight: 700;
          text-transform: uppercase;
          border: 3px solid #000;
          background-color: #fff;
          color: #000;
          position: relative;
          transition: all 0.2s ease;
          box-shadow: 5px 5px 0 #000;
          overflow: hidden;
          text-decoration: none;
          margin-bottom: 1rem;
          border-radius: 8px;
        }
        .brutalist-card__button--read {
          background-color: #000;
          color: #fff;
        }
        .brutalist-card__button--mark:hover {
          background-color: #296fbb;
          border-color: #296fbb;
          color: #fff;
          box-shadow: 7px 7px 0 #004280;
        }
        .brutalist-card__button--read:hover {
          background-color: #ff0000;
          border-color: #ff0000;
          color: #fff;
          box-shadow: 7px 7px 0 #800000;
        }
        .brutalist-card__button:active {
          transform: translate(5px, 5px);
          box-shadow: none;
        }
        .brutalist-card__button--danger {
          background: #ef4444;
          color: #fff;
          border-color: #ef4444;
        }
        .brutalist-card__button--danger:hover {
          background: #fff;
          color: #ef4444;
        }
        .brutalist-card__button--success {
          background: #22c55e;
          color: #fff;
          border-color: #22c55e;
        }
        .brutalist-card__button--success:hover {
          background: #fff;
          color: #22c55e;
        }
        .brutalist-card__button--warning {
          background: #facc15;
          color: #000;
          border-color: #facc15;
        }
        .brutalist-card__button--warning:hover {
          background: #fff;
          color: #facc15;
        }
        .brutalist-overlay {
          position: fixed;
          inset: 0;
          background: rgba(0,0,0,0.18);
          z-index: 9998;
        }

        .brutalist-swal-alert {
          width: 340px;
          border: 4px solid #000;
          background: #fff;
          padding: 1.5rem;
          box-shadow: 10px 10px 0 #000;
          font-family: 'Arial', sans-serif;
          position: fixed;
          top: 50%;
          left: 50%;
          z-index: 99999;
          transform: translate(-50%, -50%);
          animation: fadeIn .2s;
          border-radius: 12px;
        }
        .brutalist-swal-header {
          display: flex;
          align-items: center;
          gap: 1rem;
          margin-bottom: 1rem;
          border-bottom: 2px solid #000;
          padding-bottom: 1rem;
        }
        .brutalist-swal-icon {
          flex-shrink: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          background-color: #000;
          padding: 0.5rem;
          border-radius: 8px;
        }
        .brutalist-swal-icon.success { background: #22c55e; }
        .brutalist-swal-icon.danger  { background: #ef4444; }
        .brutalist-swal-icon.warning { background: #facc15; }
        .brutalist-swal-icon.success svg { fill: #fff; }
        .brutalist-swal-icon.danger svg  { fill: #fff; }
        .brutalist-swal-icon.warning svg { fill: #000; }
        .brutalist-swal-title {
          font-weight: 900;
          color: #000;
          font-size: 1.3rem;
          text-transform: uppercase;
        }
        .brutalist-swal-message {
          margin-top: 1rem;
          color: #000;
          font-size: 1rem;
          line-height: 1.4;
          border-bottom: 2px solid #000;
          padding-bottom: 1rem;
          font-weight: 600;
        }
        .brutalist-swal-actions {
          margin-top: 1rem;
        }
        .brutalist-swal-btn {
          display: block;
          width: 100%;
          padding: 0.75rem;
          text-align: center;
          font-size: 1rem;
          font-weight: 700;
          text-transform: uppercase;
          border: 3px solid #000;
          background-color: #fff;
          color: #000;
          position: relative;
          transition: all 0.2s ease;
          box-shadow: 5px 5px 0 #000;
          overflow: hidden;
          text-decoration: none;
          margin-bottom: 1rem;
          border-radius: 8px;
          cursor: pointer;
        }
        .brutalist-swal-btn--danger {
          background: #ef4444;
          color: #fff;
          border-color: #ef4444;
        }
        .brutalist-swal-btn--success {
          background: #22c55e;
          color: #fff;
          border-color: #22c55e;
        }
        .brutalist-swal-btn--warning {
          background: #facc15;
          color: #000;
          border-color: #facc15;
        }
        .brutalist-swal-overlay {
          position: fixed;
          inset: 0;
          background: rgba(0,0,0,0.18);
          z-index: 99998;
        }
        .sticky-back {
          position: sticky;
          top: 0;
          z-index: 100;
          background: #fff;
          margin-bottom: 1.5rem;
        }
    </style>
    @stack('styles')
    
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ open: false }">
    <!-- Navbar -->
    <nav x-data="{ scrolled: false }"
         @scroll.window="scrolled = window.pageYOffset > 50"
         class="fixed top-0 z-50 w-full bg-transparent">
        <div class="relative flex items-center justify-between px-4 mx-auto h-14 max-w-7xl sm:px-6 lg:px-8">
            <!-- Trapesium Background with Navigation -->
            <div
                class="absolute top-0 transition-all duration-500 ease-in-out flex items-center justify-center"
                :style="scrolled
                    ? 'width: 100vw; height: 56px; background: linear-gradient(to right, #3D3D3D, #2E2E2E); clip-path: polygon(0 0, 100% 0, 92% 100%, 8% 100%); left: 50%; transform: translateX(-50%);'
                    : `width: ${Math.min(window.innerWidth * 0.8, 520)}px; height: 56px; background: #fff; clip-path: polygon(0 0, 100% 0, ${100 - (window.innerWidth < 640 ? 20 : window.innerWidth < 768 ? 15 : 10)}% 100%, ${window.innerWidth < 640 ? 20 : window.innerWidth < 768 ? 15 : 10}% 100%); left: 50%; transform: translateX(-50%);`"
                style="z-index: 10;">
                <!-- Center Navigation -->
                <div class="flex items-center justify-center space-x-4 sm:space-x-8" x-data="{ activeTab: localStorage.getItem('activeTab') || 'dashboard' }">
                    <a href="/" @click="activeTab = 'dashboard'; localStorage.setItem('activeTab', 'dashboard')"
                             class="text-xs sm:text-sm md:text-base font-medium transition-all duration-300 nav-item"
                             :class="{ 'active': activeTab === 'dashboard', 'text-white': scrolled, 'text-gray-700': !scrolled }">
                         Dashboard
                    </a>
                    <button @click="activeTab = 'rooms'; showRooms(); localStorage.setItem('activeTab', 'rooms')"
                            class="text-xs sm:text-sm md:text-base font-medium transition-all duration-300 nav-item"
                            :class="{ 'active': activeTab === 'rooms', 'text-white': scrolled, 'text-gray-700': !scrolled }">
                        Rooms
                    </button>
                    <a href="{{ route('galeri') }}"
                       @click="activeTab = 'gallery'; localStorage.setItem('activeTab', 'gallery')"
                       class="text-xs sm:text-sm md:text-base font-medium transition-all duration-300 nav-item"
                       :class="{ 'active': activeTab === 'gallery' || '{{ request()->routeIs('galeri') }}' === '1', 'text-white': scrolled, 'text-gray-700': !scrolled }">
                        Gallery
                    </a>
                </div>
            </div>

            <!-- Logo -->
            <div class="z-20 flex items-center">
                <a href="/" 
                   class="text-xl sm:text-2xl font-regular text-white font-poppins drop-shadow-md transition-all duration-300">
                    Cahaya Resort
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="z-20 flex items-center space-x-2 sm:space-x-4 navbar-auth">
                @auth
                    <div class="profile-dropdown">
                        <button type="button" class="profile-dropdown-button text-white hover:text-gray-200 transition font-medium flex items-center text-sm">
                            <div class="relative">
                                <img src="{{ Auth::check() ? Auth::user()->profile_photo_url : asset('images/default-avatar.png') }}" 
                                     alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}"
                                     class="h-6 w-6 sm:h-8 sm:w-8 rounded-full object-cover mr-1 sm:mr-2 cursor-pointer"
                                     onclick="event.stopPropagation(); showPhotoUploadDialog();">
                                <input type="file" id="headerProfilePhoto" class="hidden" accept="image/*" onchange="handleQuickProfilePhotoUpload(this)">
                            </div>
                            <span class="hidden sm:inline" data-user-name>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-sm ml-1 sm:ml-2"></i>
                        </button>
                        <div class="profile-dropdown-menu">
                            <a href="#" onclick="event.preventDefault(); showProfile();" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Profile</span>
                            </a>
                            <a href="#" @click="$dispatch('open-transaction-panel')" class="dropdown-item">
                                <i class="fas fa-receipt"></i>
                                <span>Transactions</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="logoutBrutalistConfirm()" class="dropdown-item text-red-600">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-login btn-animate text-xs px-2 py-1 sm:text-sm sm:px-3 sm:py-2">Log In</a>
                    <a href="{{ route('register') }}" class="btn-signup btn-animate text-xs px-2 py-1 sm:text-sm sm:px-3 sm:py-2">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Transaction Panel -->
    <div class="slide-panel transaction-panel" id="transactionPanel" x-data="transactionPanel">
        <div class="slide-content">
            <div class="slide-content-inner">
                <!-- Back Button -->
                    <button onclick="hidePanel()" class="flex items-center px-5 py-3 bg-white rounded-xl shadow border text-gray-700 font-semibold mr-4 hover:bg-gray-100 transition" style="position: absolute; top: 1rem; left: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Back</span>
                    </button>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 mt-10">Transaction History</h2>
                    <div class="space-y-4" id="transactionList">
                        <!-- Transaction list will be loaded here -->
                        <div class="text-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Loading transactions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Panel -->
    <div class="slide-panel profile-panel" id="profilePanel">
        <div class="slide-content">
            <div class="slide-content-inner">
                <!-- Back Button -->
                <button onclick="hidePanel()" class="flex items-center px-5 py-3 bg-white rounded-xl shadow border text-gray-700 font-semibold mr-4 hover:bg-gray-100 transition" style="position: absolute; top: 1rem; left: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Back</span>
                    </button>

                @auth
                <div id="profileContent" class="mt-8">
                    <!-- Profile Header -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative h-32 bg-gradient-to-r from-[#FFA040] to-[#FFA040] mt-8">

                            <div class="absolute -bottom-12 left-8">
                                <div class="relative">
                                    <img 
                                        src="{{ Auth::check() ? Auth::user()->profile_photo_url : asset('images/default-avatar.png') }}" 
                                        alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}"
                                        class="h-24 w-24 rounded-full border-4 border-white object-cover cursor-pointer"
                                        onclick="showPhotoUploadDialog()" />
                                    <button onclick="showPhotoUploadDialog()" 
                                            class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-lg hover:bg-gray-100">
                                        <i class="fas fa-camera text-gray-600"></i>
                                    </button>
                                    <input type="file" 
                                           id="profilePhotoInput" 
                                           class="hidden" 
                                           accept="image/*"
                                           onchange="handleProfilePhotoUpload(this)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-16 pb-6 px-8">
                            <div class="flex justify-between items-start">
                                <div>
                                    @auth
                                    <h2 class="text-2xl font-bold text-gray-800" data-user-name>{{ Auth::user()->name }}</h2>
                                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                                    @else
                                    <h2 class="text-2xl font-bold text-gray-800">Guest</h2>
                                    <p class="text-gray-600">guest@example.com</p>
                                    @endauth
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="showEditProfileModal()" 
                                            class="px-4 py-2 bg-[#FFA040] text-white rounded-lg hover:bg-[#FFA040] transition flex items-center">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Navigation -->
                    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                        <nav class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Personal Information -->
                            <button onclick="showEditProfileModal()" class="flex items-center p-4 rounded-lg hover:bg-gray-50 transition group text-left w-full">
                                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-orange-100 text-orange-600 group-hover:bg-orange-200">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                                    <p class="text-sm text-gray-600">Update your personal details</p>
                                </div>
                            </button>

                            <!-- Security -->
                            <button onclick="showChangePasswordModal()" class="flex items-center p-4 rounded-lg hover:bg-gray-50 transition group text-left w-full">
                                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Security</h3>
                                    <p class="text-sm text-gray-600">Manage your password and security</p>
                                </div>
                            </button>

                            <!-- Booking History -->
                            <button onclick="showTransactionPanel()" class="flex items-center p-4 rounded-lg hover:bg-gray-50 transition group text-left w-full">
                                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 group-hover:bg-green-200">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Booking History</h3>
                                    <p class="text-sm text-gray-600">View your booking history</p>
                                </div>
                            </button>

                            <!-- Notifications -->
                            <button onclick="showNotificationPreferences()" class="flex items-center p-4 rounded-lg hover:bg-gray-50 transition group text-left w-full">
                                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-purple-100 text-purple-600 group-hover:bg-purple-200">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                                    <p class="text-sm text-gray-600">Manage your notification preferences</p>
                                </div>
                            </button>
                        </nav>
                    </div>

                    <!-- Profile Photo Actions -->
                    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile Photo</h3>
                        <div class="flex items-center space-x-4">
                            <img 
                                src="{{ Auth::check() ? Auth::user()->profile_photo_url : asset('images/default-avatar.png') }}" 
                                alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}"
                                class="h-16 w-16 rounded-full object-cover cursor-pointer"
                                onclick="showPhotoUploadDialog()">
                            <div class="flex space-x-3">
                                <button onclick="showPhotoUploadDialog()" 
                                        class="px-4 py-2 bg-[#FFA040] text-white rounded-lg hover:bg-[#FFA040] transition flex items-center">
                                    <i class="fas fa-camera mr-2"></i>
                                    <span>Change Photo</span>
                                </button>
                                <button onclick="removeProfilePhoto()" 
                                        class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition flex items-center">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    <span>Remove</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Account Actions -->
                    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Actions</h3>
                        <div class="space-y-4">
                            <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Profile Modal -->
                    <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-800">Edit Profile</h3>
                                <button onclick="hideEditProfileModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <form id="profileForm" onsubmit="handleProfileSubmit(event)" class="space-y-6">
                                @csrf
                                @method('patch')

                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" name="name" 
                                           value="{{ Auth::check() ? Auth::user()->name : 'Guest' }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" 
                                        @auth
                                        value="{{ Auth::user()->email }}"
                                        @else
                                        value="guest@example.com"
                                        @endauth
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500" readonly> 
                                </div>

                                <!-- Phone -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="tel" name="phone" 
                                        @auth
                                        value="{{ Auth::user()->phone }}"
                                        @else
                                        value=""
                                        @endauth
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>

                                <!-- Buttons -->
                                <div class="flex justify-end space-x-3 pt-4">
                                    <button type="button" onclick="hideEditProfileModal()" 
                                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-[#FFA040] text-white rounded-md hover:bg-[#FFA040] transition">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-8 text-center">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Please Sign In</h2>
                        <p class="text-gray-600 mb-6">You need to be logged in to view your profile</p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('login') }}" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-6 py-2 border border-orange-500 text-orange-500 rounded-lg hover:bg-orange-50 transition">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Rooms Panel -->
    <div class="slide-panel rooms-panel" id="roomsPanel">
        <div class="slide-content">
            <div class="slide-content-inner" id="roomsContent">
                <!-- Back Button -->
                    <button onclick="hidePanel()" class="back-button" style="position: absolute; top: 1rem; left: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Back</span>
                    </button>
                    
                <!-- Rooms content will be loaded here -->
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Loading rooms...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Panel -->
    <div class="slide-panel booking-panel" id="bookingPanel">
        <div class="slide-content">
            <div class="slide-content-inner" id="bookingContent">
                <!-- Booking form will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Payment Panel -->
    <div class="slide-panel payment-panel" id="paymentPanel">
        <div class="slide-content">
            <div id="paymentContent"></div>
        </div>
    </div>

    <!-- Updated Notification Container -->
    <div class="notification-container">
        <div id="notification" class="notification" role="alert">
            <i class="fas fa-check-circle"></i>
            <span id="notification-message" class="notification-message"></span>
        </div>
    </div>

    <!-- Base Scripts -->
    <script>
        // Initialize Pusher if not already initialized
        if (typeof window.pusherClient === 'undefined') {
            window.pusherClient = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                forceTLS: true
            });
        }

        // Subscribe to the payments channel using the global instance
        const paymentsChannel = window.pusherClient.subscribe('payments');
        
        // Listen for payment status updates
        paymentsChannel.bind('App\\Events\\PaymentStatusUpdated', function(data) {
            console.log('Payment status updated:', data);
            
            // If we're on the transactions page, reload it
            if (window.location.search.includes('panel=transactions')) {
                // Reload transactions if the panel is open
                if (window.transactionPanel && typeof window.transactionPanel.loadTransactions === 'function') {
                    window.transactionPanel.loadTransactions();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Check if we're coming from Midtrans popup close
            const urlParams = new URLSearchParams(window.location.search);
            const panel = urlParams.get('panel');
            const source = urlParams.get('source');
            
            if (panel === 'transactions' && source === 'midtrans') {
                // Only open transaction panel if coming from Midtrans
                setTimeout(() => {
                    const transactionPanel = document.getElementById('transactionPanel');
                    if (transactionPanel) {
                        // Hide all other panels first
                        hidePanel();
                        // Show transaction panel
                        transactionPanel.classList.add('show');
                        // Load transactions
                        if (window.transactionPanel && typeof window.transactionPanel.loadTransactions === 'function') {
                            window.transactionPanel.loadTransactions();
                        }
                        
                        // Remove URL parameters using History API
                        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        window.history.replaceState({}, document.title, newUrl);

                        // Show success message
                        
                    }
                }, 300);
            }
            
            const roomsPanel = document.getElementById('roomsPanel');
            const bookingPanel = document.getElementById('bookingPanel');
            const profilePanel = document.getElementById('profilePanel');
            const roomsContent = document.getElementById('roomsContent');
            const bookingContent = document.getElementById('bookingContent');
            const editProfileModal = document.getElementById('editProfileModal');

            // Initialize dropdown functionality
            const dropdownButtons = document.querySelectorAll('.dropdown-button');
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const dropdownMenu = this.nextElementSibling;
                    const isOpen = dropdownMenu.classList.contains('show');
                    
                    // Close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    document.querySelectorAll('.dropdown-button').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Toggle current dropdown
                    if (!isOpen) {
                        dropdownMenu.classList.add('show');
                        this.classList.add('active');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    document.querySelectorAll('.dropdown-button').forEach(button => {
                        button.classList.remove('active');
                    });
                }
            });

            // Show profile panel
            window.showProfile = function() {
                @auth
                hidePanel();
                profilePanel.classList.add('show');
                @else
                showBrutalistSwalAlert({
                    type: 'warning',
                    title: 'Please Sign In',
                    message: 'You need to be logged in to view your profile',
                    confirmText: 'Login',
                    cancelText: 'Register',
                    onConfirm: function() {
                        window.location.href = '{{ route('login') }}';
                    },
                    onCancel: function() {
                        window.location.href = '{{ route('register') }}';
                    }
                });
                @endauth
            };

            // Show/hide edit profile modal
            window.showEditProfileModal = function() {
                @auth
                // Set initial form values
                const initialFormData = {
                    name: '{{ Auth::user()->name }}',
                    phone: '{{ Auth::user()->phone }}'
                };
                
                editProfileModal.classList.remove('hidden');
                
                // Set form values
                const form = document.getElementById('profileForm');
                if (form) {
                    form.elements['name'].value = initialFormData.name;
                    form.elements['phone'].value = initialFormData.phone || '';
                }
                @else
                showBrutalistSwalAlert({
                    type: 'warning',
                    title: 'Please Sign In',
                    message: 'You need to be logged in to edit your profile',
                    confirmText: 'Login',
                    cancelText: 'Register',
                    onConfirm: function() {
                        window.location.href = '{{ route('login') }}';
                    },
                    onCancel: function() {
                        window.location.href = '{{ route('register') }}';
                    }
                });
                @endauth
            };

            window.hideEditProfileModal = function() {
                @auth
                // Reset form to initial values before hiding
                const form = document.getElementById('profileForm');
                if (form) {
                    form.elements['name'].value = '{{ Auth::user()->name }}';
                    form.elements['phone'].value = '{{ Auth::user()->phone }}' || '';
                }
                editProfileModal.classList.add('hidden');
                @endauth
            };

            window.hidePanel = function(showPanel = null) {
                const panels = [
                    roomsPanel,
                    bookingPanel,
                    profilePanel,
                    document.getElementById('transactionPanel'),
                    document.getElementById('paymentPanel')
                ];
                
                panels.forEach(panel => {
                    if (panel) panel.classList.remove('show');
                });

                if (!showPanel) {
                    // Update Alpine.js state
                    const navContainer = document.querySelector('[x-data]');
                    if (navContainer && navContainer.__x) {
                        navContainer.__x.$data.activeTab = 'dashboard';
                    }
                    
                    // Update localStorage
                    localStorage.setItem('activeTab', 'dashboard');
                    
                    // Force update UI
                    document.querySelectorAll('.nav-item').forEach(item => {
                        const text = item.textContent.trim();
                        if (text === 'Dashboard') {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });
                }

                if (showPanel === 'rooms') {
                    roomsPanel.classList.add('show');
                } else if (showPanel === 'booking') {
                    bookingPanel.classList.add('show');
                }
            };

            // Logout confirmation
            window.logoutBrutalistConfirm = function() {
                showBrutalistAlert({
                    type: 'danger',
                    title: 'Logout Confirmation',
                    message: 'Are you sure you want to logout?',
                    confirmText: 'Yes, logout',
                    cancelText: 'Cancel',
                    onConfirm: function() {
                        document.getElementById('logout-form').submit();
                    }
                });
            };

            // Show rooms panel
            window.showRooms = async function() {
                try {
                    // Update navigation state first
                    const navContainer = document.querySelector('[x-data]');
                    if (navContainer && navContainer.__x) {
                        navContainer.__x.$data.activeTab = 'rooms';
                        localStorage.setItem('activeTab', 'rooms');
                    }

                    // Force update UI for navigation
                    document.querySelectorAll('.nav-item').forEach(item => {
                        const text = item.textContent.trim();
                        if (text === 'Rooms') {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });

                    const response = await fetch('{{ route("kamar.index") }}');
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const mainContent = doc.querySelector('.min-h-screen');
                    
                    if (mainContent) {
                        roomsContent.innerHTML = `
                            ${mainContent.innerHTML}
                        `;
                        
                        roomsPanel.classList.add('show');
                        bindPanelPagination();
                    }
                } catch (error) {
                    console.error('Error loading rooms:', error);
                }
            };

            // Update handleBackClick function
            window.handleBackClick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                // Hide panel first
                roomsPanel.classList.remove('show');
                
                // Update Alpine.js state
                const navContainer = document.querySelector('[x-data]');
                if (navContainer && navContainer.__x) {
                    navContainer.__x.$data.activeTab = 'dashboard';
                    localStorage.setItem('activeTab', 'dashboard');
                }
                
                // Force update UI immediately
                document.querySelectorAll('.nav-item').forEach(item => {
                    const text = item.textContent.trim();
                    if (text === 'Dashboard') {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            };

            // Update navigation initialization
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize navigation state
                const activeTab = localStorage.getItem('activeTab') || 'dashboard';
                const navContainer = document.querySelector('[x-data]');
                if (navContainer && navContainer.__x) {
                    navContainer.__x.$data.activeTab = activeTab;
                }

                // Force update UI for initial state
                document.querySelectorAll('.nav-item').forEach(item => {
                    const text = item.textContent.trim();
                    if (text.toLowerCase() === activeTab) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            });

            // Update the navigation buttons
            document.addEventListener('DOMContentLoaded', function() {
                const dashboardBtn = document.querySelector('button[x-data]');
                if (dashboardBtn) {
                    dashboardBtn.addEventListener('click', function() {
                        const navContainer = document.querySelector('[x-data]');
                        if (navContainer && navContainer.__x) {
                            navContainer.__x.$data.activeTab = 'dashboard';
                            localStorage.setItem('activeTab', 'dashboard');
                        }
                    });
                }
            });

            // Show booking panel
            window.showBooking = async function(roomIds) {
                console.log('showBooking called with room IDs:', roomIds); // Log function call
                try {
                    const queryString = roomIds.map(id => `room_ids[]=${id}`).join('&');
                    console.log('Fetching booking form with query string:', queryString); // Log fetch URL
                    const response = await fetch(`{{ route("bookings.create") }}?${queryString}`);
                    
                    if (!response.ok) {
                        console.error('Failed to fetch booking form. Status:', response.status); // Log fetch error
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const html = await response.text();
                    console.log('Booking form HTML fetched successfully. HTML length:', html.length); // Log fetch success

                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const mainContent = doc.querySelector('main');
                    
                    console.log('Main content extracted:', mainContent ? 'Found' : 'Not Found'); // Log main content status

                    if (mainContent) {
                        bookingContent.innerHTML = mainContent.innerHTML;
                        console.log('Booking content inserted into panel.'); // Log insertion
                        hidePanel();
                        bookingPanel.classList.add('show');
                        console.log('Booking panel shown.'); // Log panel show

                        // Extract scripts from the loaded content
                        const scriptElements = doc.querySelectorAll('script');
                        console.log('Found script elements:', scriptElements.length);

                        // First, evaluate inline scripts that don't have src attribute
                        scriptElements.forEach(script => {
                            if (!script.src && script.textContent) {
                                try {
                                    // Create a new script element
                                    const newScript = document.createElement('script');
                                    // Copy any attributes
                                    Array.from(script.attributes).forEach(attr => {
                                        if (attr.name !== 'src') {
                                            newScript.setAttribute(attr.name, attr.value);
                                        }
                                    });
                                    newScript.textContent = script.textContent;
                                    document.body.appendChild(newScript);
                                    console.log('Inline script executed');
                                } catch (error) {
                                    console.error('Error executing inline script:', error);
                                }
                            }
                        });

                        // Then, load external scripts in sequence
                        for (const script of scriptElements) {
                            if (script.src) {
                                try {
                                    await new Promise((resolve, reject) => {
                                        const newScript = document.createElement('script');
                                        // Copy all attributes
                                        Array.from(script.attributes).forEach(attr => {
                                            newScript.setAttribute(attr.name, attr.value);
                                        });
                                        newScript.onload = resolve;
                                        newScript.onerror = reject;
                                        document.body.appendChild(newScript);
                                    });
                                    console.log('External script loaded:', script.src);
                                } catch (error) {
                                    console.error('Error loading external script:', error);
                                }
                            }
                        }

                        // Initialize room navigation after all scripts are loaded
                        if (typeof initializeRoomNavigation === 'function') {
                            console.log('Initializing room navigation');
                            initializeRoomNavigation();
                        } else {
                            console.error('initializeRoomNavigation function not found');
                        }

                        // Dispatch event when booking panel is shown
                        document.dispatchEvent(new Event('bookingPanelShown'));
                        console.log('bookingPanelShown event dispatched.');
                    }
                } catch (error) {
                    console.error('Error loading booking form:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load booking form. Please try again.'
                    });
                }
            };

            // Bind panel pagination
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
                            bindPanelPagination(); // rebind again after update
                        } catch (error) {
                            console.error('Error loading rooms:', error);
                        }
                    });
                });
            }

            // Show notifications
            @if(session('success'))
                showBrutalistSwalAlert({
                    type: 'success',
                    title: 'Success',
                    message: '{{ session("success") }}',
                    timer: 3000
                });
            @endif

            @if(session('error'))
                showBrutalistSwalAlert({
                    type: 'danger',
                    title: 'Error',
                    message: '{{ session("error") }}',
                    timer: 3000
                });
            @endif

            // Handle profile photo upload
            window.handleProfilePhotoUpload = async function(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    
                    // Validate file type and size
                    if (!file.type.startsWith('image/')) {
                        showBrutalistSwalAlert({
                            type: 'danger',
                            title: 'Invalid File Type',
                            message: 'Please select an image file'
                        });
                        input.value = '';
                        return;
                    }

                    if (file.size > 1024 * 1024) { // 1MB
                        showBrutalistSwalAlert({
                            type: 'danger',
                            title: 'File Too Large',
                            message: 'Please select an image less than 1MB'
                        });
                        input.value = '';
                        return;
                    }
                    
                    // Show loading state
                    showBrutalistSwalAlert({
                        type: 'warning',
                        title: 'Uploading...',
                        message: 'Please wait while we upload your photo'
                    });

                    const formData = new FormData();
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PATCH');

                    try {
                        const response = await fetch('{{ route("profile.photo.update") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to upload photo');
                        }

                        if (!data.profile_photo_url) {
                            throw new Error('No photo URL received from server');
                        }

                        console.log('Upload response:', data); // Debug log

                        // Update profile photo if available
                        const profileSelector = @json(Auth::check() ? 'img[alt="' . Auth::user()->name . '"]' : 'img[alt="Guest"]');
                        const profilePhotos = document.querySelectorAll(profileSelector);
                        if (data.profile_photo_url && profilePhotos.length > 0) {
                            profilePhotos.forEach(photo => {
                                photo.src = data.profile_photo_url + '?t=' + new Date().getTime();
                            });
                        }

                        // Show success message
                        showBrutalistSwalAlert({
                            type: 'success',
                            title: 'Success!',
                            message: 'Profile photo updated successfully',
                            timer: 1500
                        });

                        // Clear the file input
                        input.value = '';
                    } catch (error) {
                        console.error('Profile photo upload error:', error);
                        showBrutalistSwalAlert({
                            type: 'danger',
                            title: 'Error',
                            message: error.message || 'Failed to upload profile photo'
                        });
                        // Clear the file input on error too
                        input.value = '';
                    }
                }
            };

            // Handle profile form submission
            window.handleProfileSubmit = async function(event) {
                event.preventDefault();

                @guest
                    showBrutalistSwalAlert({
                        type: 'error',
                        title: 'Authentication Required',
                        message: 'Please log in to update your profile.',
                        confirmText: 'Login',
                        cancelText: 'Register',
                        onConfirm: function() {
                            window.location.href = '{{ route('login') }}';
                        },
                        onCancel: function() {
                            window.location.href = '{{ route('register') }}';
                        }
                    });
                    return;
                @endguest

                const form = event.target;
                const formData = new FormData(form);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PATCH');

                try {
                    const response = await fetch('{{ route("profile.update") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to update profile');
                    }

                    // Update profile information without page reload
                    const profileElements = {
                        name: document.getElementById('profile-name'),
                        email: document.getElementById('profile-email'),
                        displayName: document.getElementById('display-name'),
                        displayEmail: document.getElementById('display-email'),
                        displayPhone: document.getElementById('display-phone'),
                        headerName: document.querySelector('.dropdown-button span'),
                        allNameElements: document.querySelectorAll('[data-user-name]')
                    };

                    // Update elements if they exist
                    if (profileElements.name) profileElements.name.textContent = data.user.name;
                    if (profileElements.email) profileElements.email.textContent = data.user.email;
                    if (profileElements.displayName) profileElements.displayName.value = data.user.name;
                    if (profileElements.displayEmail) profileElements.displayEmail.value = data.user.email;
                    if (profileElements.displayPhone) profileElements.displayPhone.value = data.user.phone;
                    if (profileElements.headerName) profileElements.headerName.textContent = data.user.name;
                    
                    // Update all elements with data-user-name attribute
                    if (profileElements.allNameElements) {
                        profileElements.allNameElements.forEach(element => {
                            element.textContent = data.user.name;
                        });
                    }

                    // Update all img alt attributes that contain the old name
                    const oldName = @json(Auth::check() ? Auth::user()->name : 'Guest');
                    document.querySelectorAll('img[alt="' + oldName + '"]').forEach(img => {
                        img.alt = data.user.name;
                    });

                    // Update profile photo if available
                    @auth
                    const profilePhotos = document.querySelectorAll('img[alt="{{ Auth::user()->name }}"]');
                    if (data.profile_photo_url && profilePhotos.length > 0) {
                        profilePhotos.forEach(photo => {
                            photo.src = data.profile_photo_url + '?t=' + new Date().getTime();
                        });
                    }
                    @endif

                    // Close modal
                    hideEditProfileModal();

                    // Show success message
                    showBrutalistSwalAlert({
                      type: 'success',
                      title: 'Success!',
                      message: 'Profile updated successfully',
                      timer: 2000
                    });
                } catch (error) {
                    console.error('Profile update error:', error);
                    showBrutalistSwalAlert({
                        type: 'error',
                        title: 'Error',
                        message: error.message || 'Failed to update profile'
                    });
                }
            };

            // Handle password update
            window.showChangePasswordModal = function() {
                @auth
                // Show modal with form (previous style)
                const modalId = 'changePasswordModal';
                let modal = document.getElementById(modalId);
                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = modalId;
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                    modal.innerHTML = `
                        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-800">Change Password</h3>
                                <button onclick="document.getElementById('${modalId}').remove()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <form id="passwordForm" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div class="flex justify-end space-x-3 pt-4">
                                    <button type="button" onclick="document.getElementById('${modalId}').remove()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-[#FFA040] text-white rounded-md hover:bg-[#FFA040] transition">Update Password</button>
                                </div>
                            </form>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    document.getElementById('passwordForm').onsubmit = async function(event) {
                        event.preventDefault();
                        const formData = new FormData(this);
                        formData.append('_token', '{{ csrf_token() }}');
                        try {
                            const response = await fetch('{{ route("profile.password.update") }}', {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    current_password: formData.get('current_password'),
                                    password: formData.get('password'),
                                    password_confirmation: formData.get('password_confirmation')
                                })
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                if (data.errors) {
                                    const errorMessages = [];
                                    if (data.errors.current_password) {
                                        errorMessages.push('Current password is incorrect');
                                    }
                                    if (data.errors.password) {
                                        data.errors.password.forEach(error => {
                                            if (error.includes('confirmed')) {
                                                errorMessages.push('New password and confirmation do not match');
                                            } else if (error.includes('different')) {
                                                errorMessages.push('New password must be different from current password');
                                            } else if (error.includes('min')) {
                                                errorMessages.push('New password must be at least 8 characters');
                                            } else {
                                                errorMessages.push(error);
                                            }
                                        });
                                    }
                                    throw new Error(errorMessages.join('\n'));
                                }
                                throw new Error(data.message || 'Failed to update password');
                            }
                            document.getElementById(modalId).remove();
                            showBrutalistSwalAlert({
                                type: 'success',
                                title: 'Success',
                                message: 'Password updated successfully',
                                timer: 2000
                            });
                        } catch (error) {
                            showBrutalistSwalAlert({
                                type: 'danger',
                                title: 'Error',
                                message: error.message
                            });
                        }
                    };
                } else {
                    modal.style.display = 'flex';
                }
                @else
                showBrutalistSwalAlert({
                    type: 'warning',
                    title: 'Please Sign In',
                    message: 'You need to be logged in to change your password',
                    confirmText: 'Login',
                    cancelText: 'Register',
                    onConfirm: function() {
                        window.location.href = '{{ route('login') }}';
                    },
                    onCancel: function() {
                        window.location.href = '{{ route('register') }}';
                    }
                });
                @endauth
            };

            // Toggle password visibility
            window.togglePasswordVisibility = function(inputId) {
                const input = document.getElementById(inputId);
                const icon = input.nextElementSibling.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            };

            // Handle notification preferences
            window.showNotificationPreferences = function() {
                showBrutalistSwalAlert({
                    type: 'warning',
                    title: 'Notification Preferences',
                    message: 'Please select your notification preferences',
                    confirmText: 'Save Preferences',
                    cancelText: 'Cancel',
                    onConfirm: async function() {
                        const formData = new FormData(document.getElementById('notificationForm'));
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'PATCH');

                        try {
                            const response = await fetch('{{ route("profile.notifications.update") }}', {
                                method: 'POST',
                                body: formData
                            });

                            if (!response.ok) {
                                throw new Error('Failed to update notification preferences');
                            }

                            showBrutalistSwalAlert({
                                type: 'success',
                                title: 'Success',
                                message: 'Notification preferences updated successfully'
                            });
                        } catch (error) {
                            showBrutalistSwalAlert({
                                type: 'danger',
                                title: 'Error',
                                message: error.message
                            });
                        }
                    },
                    onCancel: function() {
                        // Cancel button logic
                    }
                });
            };

            // Handle remove profile photo
            window.removeProfilePhoto = async function() {
                const result = await showBrutalistSwalAlert({
                    type: 'warning',
                    title: 'Remove Profile Photo?',
                    message: 'Are you sure you want to remove your profile photo?',
                    confirmText: 'Yes, remove it',
                    cancelText: 'Cancel'
                });

                if (result.isConfirmed) {
                    try {
                        // Show loading state
                        showBrutalistSwalAlert({
                            type: 'warning',
                            title: 'Removing...',
                            message: 'Please wait while we remove your photo'
                        });

                        const response = await fetch('{{ route("profile.photo.destroy") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Failed to remove photo');
                        }

                        const data = await response.json();

                        // Get default avatar URL
                        const defaultAvatarUrl = @json(Auth::check() 
                            ? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF'
                            : 'https://ui-avatars.com/api/?name=Guest&color=7F9CF5&background=EBF4FF');

                        // Update all profile photos to default avatar
                        const profileSelector = @json(Auth::check() ? 'img[alt="' . Auth::user()->name . '"]' : 'img[alt="Guest"]');
                        const profilePhotos = document.querySelectorAll(profileSelector);
                        profilePhotos.forEach(photo => {
                            photo.src = defaultAvatarUrl;
                        });

                        showBrutalistSwalAlert({
                            type: 'success',
                            title: 'Success!',
                            message: 'Profile photo removed successfully',
                            timer: 1500
                        });
                    } catch (error) {
                        console.error('Profile photo removal error:', error);
                        showBrutalistSwalAlert({
                            type: 'danger',
                            title: 'Error',
                            message: error.message || 'Failed to remove profile photo'
                        });
                    }
                }
            };

            // Add these new functions
            window.showPhotoUploadDialog = function() {
                document.getElementById('profilePhotoInput').click();
            };

            // Handle quick profile photo upload
            window.handleQuickProfilePhotoUpload = async function(input) {
                @guest
                    showBrutalistSwalAlert({
                        type: 'danger',
                        title: 'Authentication Required',
                        message: 'Please log in to update your profile photo.',
                        confirmText: 'Login',
                        cancelText: 'Register',
                        onConfirm: function() {
                            window.location.href = '{{ route('login') }}';
                        },
                        onCancel: function() {
                            window.location.href = '{{ route('register') }}';
                        }
                    });
                    return;
                @endguest

                if (!input.files || !input.files[0]) return;

                const file = input.files[0];
                const formData = new FormData();
                formData.append('profile_photo', file);
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await fetch('{{ route("profile.photo.update") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to upload photo');
                    }

                    if (!data.profile_photo_url) {
                        throw new Error('No photo URL received from server');
                    }

                    console.log('Upload response:', data); // Debug log

                    // Update all profile photos on the page
                    @auth
                    const profilePhotos = document.querySelectorAll('img[alt="{{ Auth::user()->name }}"]');
                    profilePhotos.forEach(photo => {
                        photo.src = data.profile_photo_url + '?t=' + new Date().getTime();
                    });
                    @endauth

                    // Show success message
                    showBrutalistSwalAlert({
                        type: 'success',
                        title: 'Success!',
                        message: 'Profile photo updated successfully',
                        timer: 1500
                    });

                    // Clear the file input
                    input.value = '';
                } catch (error) {
                    console.error('Profile photo upload error:', error);
                    showBrutalistSwalAlert({
                        type: 'danger',
                        title: 'Error',
                        message: error.message || 'Failed to upload profile photo'
                    });
                    // Clear the file input on error too
                    input.value = '';
                }
            };

            // Make sure storage link exists
            window.addEventListener('DOMContentLoaded', async function() {
                try {
                    const response = await fetch('{{ route("profile.photo.check-storage-link") }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (!response.ok) {
                        console.warn('Storage link might not be created. Some images might not display correctly.');
                    }
                } catch (error) {
                    console.error('Failed to check storage link:', error);
                }
            });

            // Show payment panel
            window.showPaymentPanel = async function(bookingId) {
                try {
                    const response = await fetch(`/bookings/${bookingId}/payment`);
                    const data = await response.json();
                    
                    if (data.success) {
                        // Remove any existing payment panel
                        const existingPanel = document.getElementById('paymentPanel');
                        if (existingPanel) {
                            existingPanel.remove();
                        }

                        // Create payment panel container
                        const paymentPanel = document.createElement('div');
                        paymentPanel.id = 'paymentPanel';
                        paymentPanel.classList.add('slide-panel', 'payment-panel');
                        paymentPanel.innerHTML = data.html;
                        document.body.appendChild(paymentPanel);

                        // Show payment panel with animation
                        hidePanel();
                        setTimeout(() => {
                            paymentPanel.classList.add('show');
                        }, 100);
                    } else {
                        throw new Error(data.message || 'Failed to load payment panel');
                    }
                } catch (error) {
                    console.error('Error showing payment panel:', error);
                    showBrutalistSwalAlert({
                        type: 'danger',
                        title: 'Error',
                        message: error.message || 'Failed to load payment panel',
                        timer: 3000
                    });
                }
            };
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionPanel', () => ({
                init() {
                    // Make the instance available globally for event handlers
                    window.transactionPanel = this;
                    
                    // Listen for custom event to open transaction panel
                    window.addEventListener('open-transaction-panel', () => {
                        hidePanel();
                        document.getElementById('transactionPanel').classList.add('show');
                        this.loadTransactions();
                    });
                },
                async loadTransactions() {
                    try {
                        const response = await fetch('{{ route("transactions.index") }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Failed to load transactions');
                        }
                        
                        const data = await response.json();
                        if (data.html) {
                            document.getElementById('transactionList').innerHTML = data.html;
                        } else {
                            throw new Error('Invalid response format');
                        }
                    } catch (error) {
                        console.error('Error loading transactions:', error);
                        document.getElementById('transactionList').innerHTML = `
                            <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Failed to Load Transactions</h3>
                                <p class="mt-1 text-sm text-gray-500">There was an error loading your transactions. Please try again.</p>
                                <button onclick="window.transactionPanel.loadTransactions()" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Retry
                                </button>
                            </div>
                        `;
                    }
                },
                async showDetails(id) {
                    try {
                        // Show loading state
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const response = await fetch(`/transactions/${id}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Failed to load transaction details');
                        }

                        const data = await response.json();
                        
                        // Check if transaction is expired
                        const now = new Date().getTime();
                        const deadline = data.payment_deadline ? new Date(data.payment_deadline).getTime() : null;
                        const isExpired = deadline && now > deadline;
                        
                        // Update payment status if expired
                        if (data.payment_status === 'pending' && isExpired) {
                            data.payment_status = 'expired';
                        }
                        
                        // Format dates
                        const checkIn = data.booking ? new Date(data.booking.check_in_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
                        const checkOut = data.booking ? new Date(data.booking.check_out_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
                        const createdAt = new Date(data.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });

                        // Close loading state
                        Swal.close();

                        // Show the details in a SweetAlert2 modal
                        Swal.fire({
                            title: `<div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full bg-orange-100 text-orange-600 mr-4">
                                    <i class="fas fa-receipt text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900">Order #${data.order_id}</h3>
                                    <p class="text-sm text-gray-500">${createdAt}</p>
                                </div>
                            </div>`,
                            html: `
                                <div class="text-left">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        <!-- Left Column -->
                                        <div class="space-y-3">
                                            <!-- Order Information -->
                                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                                    <h3 class="font-bold text-gray-900">Order Information</h3>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2 text-sm">
                                                    <div class="text-gray-600">Transaction ID:</div>
                                                    <div class="font-medium">${data.transaction_id || '-'}</div>
                                                    <div class="text-gray-600">Status:</div>
                                                    <div class="font-medium">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(data.transaction_status)}">
                                                            ${data.transaction_status ? data.transaction_status.charAt(0).toUpperCase() + data.transaction_status.slice(1) : '-'}
                                                        </span>
                                                    </div>
                                                    <div class="text-gray-600">Payment Type:</div>
                                                    <div class="font-medium capitalize">${data.payment_type || '-'}</div>
                                                    <div class="text-gray-600">Payment Code:</div>
                                                    <div class="font-medium">${data.payment_code || '-'}</div>
                                                </div>
                                            </div>

                                            <!-- Booking Details -->
                                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                                                    <h3 class="font-bold text-gray-900">Booking Details</h3>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2 text-sm">
                                                    <div class="text-gray-600">Check-in:</div>
                                                    <div class="font-medium">${checkIn}</div>
                                                    <div class="text-gray-600">Check-out:</div>
                                                    <div class="font-medium">${checkOut}</div>
                                                    <div class="text-gray-600">Duration:</div>
                                                    <div class="font-medium">${data.booking ? data.booking.duration + ' night(s)' : '-'}</div>
                                                    <div class="text-gray-600">Guest Name:</div>
                                                    <div class="font-medium">${data.booking ? data.booking.guest_name : '-'}</div>
                                                    <div class="text-gray-600">Phone:</div>
                                                    <div class="font-medium">${data.booking ? data.booking.phone : '-'}</div>
                                                    <div class="text-gray-600">Email:</div>
                                                    <div class="font-medium">${data.booking ? data.booking.email : '-'}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="space-y-3">
                                            <!-- Room Details -->
                                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-bed text-green-500 mr-2"></i>
                                                        <h3 class="font-bold text-gray-900">Room Details</h3>
                                                    </div>
                                                    <span class="text-sm text-gray-500">${data.booking?.rooms?.length || 0} room(s)</span>
                                                </div>
                                                ${data.booking && data.booking.rooms ? `
                                                    <div class="space-y-2 max-h-[200px] overlay-scrollbar pr-2">
                                                        ${data.booking.rooms.map(room => `
                                                            <div class="p-2 bg-white rounded-lg border border-gray-100 hover:shadow-md transition-shadow">
                                                                <div class="flex items-center justify-between mb-1.5">
                                                                    <h4 class="font-semibold text-gray-900">Room ${room.room_number}</h4>
                                                                    <span class="text-sm px-2 py-0.5 bg-blue-50 text-blue-700 rounded">${room.type}</span>
                                                                </div>
                                                                <div class="grid grid-cols-2 gap-1 text-sm">
                                                                    <div class="text-gray-600">Price/Night:</div>
                                                                    <div class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(room.pivot.price_per_night)}</div>
                                                                    <div class="text-gray-600">Nights:</div>
                                                                    <div class="font-medium">${data.booking.duration} night(s)</div>
                                                                    <div class="text-gray-600">Subtotal:</div>
                                                                    <div class="font-medium text-green-600">Rp ${new Intl.NumberFormat('id-ID').format(room.pivot.subtotal)}</div>
                                                                </div>
                                                            </div>
                                                        `).join('')}
                                                    </div>
                                                ` : '<p class="text-gray-500 text-sm">No room details available</p>'}
                                            </div>

                                            <!-- Payment Details -->
                                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-credit-card text-indigo-500 mr-2"></i>
                                                    <h3 class="font-bold text-gray-900">Payment Details</h3>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2 text-sm">
                                                    <div class="text-gray-600">Amount:</div>
                                                    <div class="font-medium text-lg text-green-600">Rp ${new Intl.NumberFormat('id-ID').format(data.gross_amount)}</div>
                                                    <div class="text-gray-600">Payment Status:</div>
                                                    <div class="font-medium">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(data.payment_status)}">
                                                            ${data.payment_status ? data.payment_status.charAt(0).toUpperCase() + data.payment_status.slice(1) : '-'}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `,
                            width: '75%',
                            showCloseButton: true,
                            showConfirmButton: false,
                            customClass: {
                                container: 'transaction-detail-modal',
                                popup: 'rounded-xl',
                                closeButton: 'focus:outline-none hover:text-gray-700',
                                htmlContainer: 'overflow-visible'
                            }
                        });
                    } catch (error) {
                        console.error('Error loading transaction details:', error);
                        Swal.close(); // Close loading state
                        showBrutalistSwalAlert({
                            type: 'error',
                            title: 'Error',
                            message: error.message || 'Failed to load transaction details. Please try again.'
                        });
                    }
                },
                async cancelTransaction(id) {
                    try {
                        const result = await Swal.fire({
                            title: 'Cancel Transaction?',
                            text: 'Are you sure you want to cancel this transaction? This action cannot be undone.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, cancel it',
                            cancelButtonText: 'No, keep it'
                        });

                        if (result.isConfirmed) {
                            const response = await fetch(`/transactions/${id}/cancel`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                throw new Error(data.message || 'Failed to cancel transaction');
                            }

                            // Refresh the transaction list
                            await this.loadTransactions();

                            // Show success message
                            showBrutalistSwalAlert({
                                type: 'success',
                                title: 'Success',
                                message: data.message || 'Transaction cancelled successfully',
                                timer: 2000
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showBrutalistSwalAlert({
                            type: 'error',
                            title: 'Error',
                            message: error.message || 'Failed to cancel transaction. Please try again.'
                        });
                    }
                },
                async payTransaction(id) {
                    try {
                        // Show loading state
                        showBrutalistSwalAlert({
                            type: 'warning',
                            title: 'Processing...',
                            message: 'Please wait while we initialize your payment'
                        });

                        const response = await fetch(`/transactions/${id}/pay`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to process payment');
                        }

                        if (!data.success || !data.snap_token) {
                            throw new Error('Failed to initialize payment');
                        }

                        // Close loading dialog
                        Swal.close();
                        
                        // Open Midtrans Snap popup
                        window.snap.pay(data.snap_token, {
                            onSuccess: async function(result) {
                                try {
                                    // Show loading state
                                    showBrutalistSwalAlert({
                                        type: 'warning',
                                        title: 'Completing Payment...',
                                        message: 'Please wait while we process your payment'
                                    });

                                    // Call payment finish endpoint via AJAX
                                    const finishResponse = await fetch(`/payment/finish/ajax?order_id=${result.order_id}`, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    });

                                    let finishData;
                                    try {
                                        finishData = await finishResponse.json();
                                    } catch (parseError) {
                                        console.error('Failed to parse response as JSON:', parseError);
                                        throw new Error('Server returned an invalid response format');
                                    }

                                    if (!finishResponse.ok || !finishData.success) {
                                        throw new Error(finishData.message || 'Failed to complete payment');
                                    }

                                    // Show success message
                                    showBrutalistSwalAlert({
                                        type: 'success',
                                        title: 'Payment Successful',
                                        message: finishData.message || 'Your payment has been processed successfully',
                                        confirmText: 'OK'
                                    });

                                    // Refresh transaction list
                                    if (window.transactionPanel && typeof window.transactionPanel.loadTransactions === 'function') {
                                        window.transactionPanel.loadTransactions();
                                    }
                                } catch (error) {
                                    console.error('Payment completion error:', error);
                                    showBrutalistSwalAlert({
                                        type: 'error',
                                        title: 'Error',
                                        message: error.message || 'Failed to complete payment. Please check your transaction history.',
                                        confirmText: 'OK'
                                    });
                                }
                            },
                            onPending: function(result) {
                                // Save payment details to localStorage
                                localStorage.setItem('lastPaymentMethod', result.payment_type);
                                localStorage.setItem('lastTransactionId', id);
                                
                                // Show pending payment instructions
                                showBrutalistSwalAlert({
                                    type: 'warning',
                                    title: 'Complete Your Payment',
                                    message: 'Please complete your payment using the provided payment instructions.',
                                    confirmText: 'View Payment Instructions',
                                    onConfirm: function() {
                                        // Show transaction panel
                                        hidePanel();
                                        document.getElementById('transactionPanel').classList.add('show');
                                        if (window.transactionPanel && typeof window.transactionPanel.loadTransactions === 'function') {
                                            window.transactionPanel.loadTransactions();
                                        }
                                    }
                                });
                            },
                            onError: function(result) {
                                console.error('Midtrans payment error:', result);
                                showBrutalistSwalAlert({
                                    type: 'danger',
                                    title: 'Payment Failed',
                                    message: 'An error occurred while processing your payment. Please try again.',
                                    confirmText: 'Close'
                                });
                            },
                            onClose: function() {
                                // Check if payment method was selected
                                const lastPaymentMethod = localStorage.getItem('lastPaymentMethod');
                                const lastTransactionId = localStorage.getItem('lastTransactionId');
                                
                                if (lastPaymentMethod && lastTransactionId === id.toString()) {
                                    // If payment method was selected, show confirmation message
                                    showBrutalistSwalAlert({
                                        type: 'warning',
                                        title: 'Payment Method Selected',
                                        message: 'Your order has been confirmed. Please check your transaction history to continue the payment.',
                                        confirmText: 'View Transactions',
                                        onConfirm: function() {
                                            // Clear stored payment info
                                            localStorage.removeItem('lastPaymentMethod');
                                            localStorage.removeItem('lastTransactionId');
                                            
                                            // Redirect to landing page with transaction panel open
                                            window.location.href = '{{ route("landing") }}?panel=transactions&source=midtrans';
                                        }
                                    });
                                } else {
                                    // If no payment method was selected, just close without saving
                                    console.log('Popup closed without selecting payment method');
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Payment initialization error:', error);
                        showBrutalistSwalAlert({
                            type: 'error',
                            title: 'Error',
                            message: error.message || 'Failed to process payment. Please try again.'
                        });
                    }
                }
            }));
        });

        function showTransactionPanel() {
            // Hide profile panel first
            hidePanel();
            
            // Show transaction panel with animation
            const transactionPanel = document.getElementById('transactionPanel');
            if (transactionPanel) {
                transactionPanel.classList.add('show');
                // Load transactions if needed
                if (window.transactionPanel && typeof window.transactionPanel.loadTransactions === 'function') {
                    window.transactionPanel.loadTransactions();
                }
            }
        }

        // Initialize navigation state
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const panel = urlParams.get('panel');
            const path = window.location.pathname;
            
            if (!panel && path === '/') {
                localStorage.setItem('activeTab', 'dashboard');
            }
        });

        // Add back button handler for rooms panel
        document.querySelector('#roomsPanel .back-button').addEventListener('click', function() {
            hidePanel();
            // Reset active tab to dashboard
            localStorage.setItem('activeTab', 'dashboard');
            const navContainer = document.querySelector('[x-data]');
            if (navContainer && navContainer.__x) {
                navContainer.__x.$data.activeTab = 'dashboard';
            }
        });

        // Add resetActiveTab function
        function resetActiveTab() {
            localStorage.setItem('activeTab', 'dashboard');
            const navContainer = document.querySelector('[x-data]');
            if (navContainer && navContainer.__x) {
                navContainer.__x.$data.activeTab = 'dashboard';
            }
        }

        // Add click handler for back button
        document.addEventListener('DOMContentLoaded', function() {
            const backButtons = document.querySelectorAll('.back-button');
            backButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    hidePanel();
                    
                    // Update navigation state immediately
                    const navContainer = document.querySelector('[x-data]').__x.$data;
                    navContainer.activeTab = 'dashboard';
                    localStorage.setItem('activeTab', 'dashboard');
                    
                    // Force Alpine to update the UI
                    document.querySelectorAll('.nav-item').forEach(item => {
                        if (item.textContent.trim() === 'Dashboard') {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.querySelector('.profile-dropdown-button');
            const profileMenu = document.querySelector('.profile-dropdown-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileMenu.classList.toggle('show');
                    profileBtn.classList.toggle('active');
                });
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.profile-dropdown')) {
                        profileMenu.classList.remove('show');
                        profileBtn.classList.remove('active');
                    }
                });
            }
        });

        function showBrutalistAlert({
          type = 'warning',
          title = '',
          message = '',
          confirmText = 'OK',
          cancelText = null,
          onConfirm = null,
          onCancel = null
        }) {
          document.querySelectorAll('.brutalist-card, .brutalist-overlay').forEach(e => e.remove());
          const overlay = document.createElement('div');
          overlay.className = 'brutalist-overlay';
          document.body.appendChild(overlay);
          let iconSvg = '';
          if (type === 'success') iconSvg = `<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"></path></svg>`;
          else if (type === 'danger') iconSvg = `<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13l-1.41 1.41L12 13.41l-3.59 3.59L7 15l3.59-3.59L7 7.83 8.41 6.41 12 10.59l3.59-3.59L17 7.83l-3.59 3.59L17 15z"></path></svg>`;
          else iconSvg = `<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>`;
          const card = document.createElement('div');
          card.className = 'brutalist-card';
          card.innerHTML = `
            <div class="brutalist-card__header">
              <div class="brutalist-card__icon ${type}">${iconSvg}</div>
              <div class="brutalist-card__alert">${title}</div>
            </div>
            <div class="brutalist-card__message">${message}</div>
            <div class="brutalist-card__actions"></div>
          `;
          document.body.appendChild(card);
          const actions = card.querySelector('.brutalist-card__actions');
          if (confirmText) {
            const btn = document.createElement('a');
            btn.href = '#';
            btn.className = `brutalist-card__button brutalist-card__button--${type}`;
            btn.textContent = confirmText;
            btn.onclick = (e) => {
              e.preventDefault();
              overlay.remove(); card.remove();
              if (onConfirm) onConfirm();
            };
            actions.appendChild(btn);
          }
          if (cancelText) {
            const btn = document.createElement('a');
            btn.href = '#';
            btn.className = 'brutalist-card__button';
            btn.textContent = cancelText;
            btn.onclick = (e) => {
              e.preventDefault();
              overlay.remove(); card.remove();
              if (onCancel) onCancel();
            };
            actions.appendChild(btn);
          }
          overlay.onclick = () => {
            overlay.remove(); card.remove();
            if (onCancel) onCancel();
          };
        }

        function showBrutalistSwalAlert({
          type = 'warning',
          title = '',
          message = '',
          confirmText = 'OK',
          cancelText = null,
          onConfirm = null,
          onCancel = null,
          timer = null
        }) {
          return new Promise((resolve) => {
            document.querySelectorAll('.brutalist-swal-alert, .brutalist-swal-overlay').forEach(e => e.remove());
            const overlay = document.createElement('div');
            overlay.className = 'brutalist-swal-overlay';
            document.body.appendChild(overlay);
            let iconSvg = '';
            if (type === 'success') iconSvg = `<svg viewBox=\"0 0 24 24\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z\"></path></svg>`;
            else if (type === 'danger') iconSvg = `<svg viewBox=\"0 0 24 24\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13l-1.41 1.41L12 13.41l-3.59 3.59L7 15l3.59-3.59L7 7.83 8.41 6.41 12 10.59l3.59-3.59L17 7.83l-3.59 3.59L17 15z\"></path></svg>`;
            else iconSvg = `<svg viewBox=\"0 0 24 24\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z\"></path></svg>`;
            const card = document.createElement('div');
            card.className = 'brutalist-swal-alert';
            card.innerHTML = `
              <div class=\"brutalist-swal-header\">
                <div class=\"brutalist-swal-icon ${type}\">${iconSvg}</div>
                <div class=\"brutalist-swal-title\">${title}</div>
              </div>
              <div class=\"brutalist-swal-message\">${message}</div>
              <div class=\"brutalist-swal-actions\"></div>
            `;
            document.body.appendChild(card);
            const actions = card.querySelector('.brutalist-swal-actions');
            if (confirmText) {
              const btn = document.createElement('button');
              btn.className = `brutalist-swal-btn brutalist-swal-btn--${type}`;
              btn.textContent = confirmText;
              btn.onclick = (e) => {
                e.preventDefault();
                overlay.remove(); card.remove();
                if (onConfirm) onConfirm();
                resolve({ isConfirmed: true });
              };
              actions.appendChild(btn);
            }
            if (cancelText) {
              const btn = document.createElement('button');
              btn.className = 'brutalist-swal-btn';
              btn.textContent = cancelText;
              btn.onclick = (e) => {
                e.preventDefault();
                overlay.remove(); card.remove();
                if (onCancel) onCancel();
                resolve({ isConfirmed: false });
              };
              actions.appendChild(btn);
            }
            overlay.onclick = () => {
              overlay.remove(); card.remove();
              if (onCancel) onCancel();
              resolve({ isConfirmed: false });
            };
            if (timer) {
              setTimeout(() => {
                overlay.remove(); card.remove();
                resolve({ isConfirmed: true });
              }, timer);
            }
          });
        }

        function getStatusColor(status) {
            if (!status) return 'bg-gray-100 text-gray-800';
            
            status = status.toLowerCase();
            switch (status) {
                case 'success':
                case 'paid':
                    return 'bg-green-100 text-green-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';
                case 'failed':
                case 'expired':
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }
    </script>
    <!-- Add Pusher script -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    @stack('scripts')
</body>
</html>