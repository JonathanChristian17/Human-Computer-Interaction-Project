<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cahaya Resort')</title>
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
    <style>
        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .custom-curve {
            position: relative;
            background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 100%);
            border-radius: 0 0 100px 100px;
        }
        .custom-curve::before,
        .custom-curve::after {
            content: '';
            position: absolute;
            top: 0;
            width: 40px;
            height: 40px;
            background-color: transparent;
        }
        .custom-curve::before {
            left: -20px;
            border-top-right-radius: 20px;
            box-shadow: 10px 0 0 0 #ffffff;
        }
        .custom-curve::after {
            right: -20px;
            border-top-left-radius: 20px;
            box-shadow: -10px 0 0 0 #ffffff;
        }
        .dropdown-menu {
            display: none;
        }
        .dropdown-menu.show {
            display: block;
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
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Navbar -->
    <nav class="fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Logo -->
            <div class="flex items-center absolute left-20 top-6">
                <button onclick="hidePanel()" class="text-white text-2xl font-semibold">
                    Cahaya Resort
                </button>
            </div>

            <!-- Center Navigation -->
            <div class="flex justify-center">
                <div class="custom-curve px-32 py-5 shadow-[0_8px_15px_-3px_rgba(0,0,0,0.4)] bg-white">
                    <div class="flex items-center space-x-16" x-data="{ activeTab: localStorage.getItem('activeTab') || 'dashboard' }">
                        <button @click="activeTab = 'dashboard'; window.location.href = '{{ route('landing') }}'; localStorage.setItem('activeTab', 'dashboard')" 
                           class="nav-item text-gray-700 hover:text-gray-900 transition font-medium"
                           :class="{ 'active': activeTab === 'dashboard' }">
                            Dashboard
                        </button>
                        <button @click="activeTab = 'rooms'; showRooms(); localStorage.setItem('activeTab', 'rooms')" 
                           class="nav-item text-gray-700 hover:text-gray-900 transition font-medium"
                           :class="{ 'active': activeTab === 'rooms' }">
                            Rooms
                        </button>
                        <button @click="activeTab = 'gallery'; window.location.href = '{{ route('galeri') }}'; localStorage.setItem('activeTab', 'gallery')"
                           class="nav-item text-gray-700 hover:text-gray-900 transition font-medium"
                           :class="{ 'active': activeTab === 'gallery' || '{{ request()->routeIs('galeri') }}' === '1' }">
                            Gallery
                        </button>
                    </div>
                </div>
            </div>

            <!-- Auth Buttons -->
            <div class="absolute right-8 top-6 flex items-center space-x-4">
@auth
                <div class="dropdown" x-data="{ open: false }">
                        <button type="button"
                            @click="open = !open"
                                class="dropdown-button text-white hover:text-gray-200 transition font-medium flex items-center">
                        <div class="relative">
                            <img 
                                src="{{ Auth::check() ? Auth::user()->profile_photo_url : asset('images/default-avatar.png') }}" 
                                alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}"
                                class="h-8 w-8 rounded-full object-cover mr-2 cursor-pointer"
                                onclick="event.stopPropagation(); showPhotoUploadDialog();">
                            <input type="file" 
                                   id="headerProfilePhoto" 
                                   class="hidden" 
                                   accept="image/*"
                                   onchange="handleQuickProfilePhotoUpload(this)">
                        </div>
                            @auth
                            <span>{{ Auth::user()->name }}</span>
                            @else
                            <span>Guest</span>
                            @endauth
                        <i class="fas fa-chevron-down text-sm ml-2"></i>
    </button>
    
                    <div class="dropdown-menu" x-show="open" @click.away="open = false">
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
                                <button type="button" 
                                        onclick="confirmLogout()" 
                                        class="dropdown-item text-red-600">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
            </button>
        </form>
    </div>
</div>
@else
                    <a href="{{ route('login') }}" class="text-white hover:text-gray-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="text-white hover:text-gray-200 transition">Register</a>
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
                <button onclick="hidePanel()" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    <span>Back</span>
                </button>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Transaction History</h2>
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
                <button onclick="hidePanel()" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    <span>Back</span>
                </button>

                @auth
                <div id="profileContent" class="mt-8">
                    <!-- Profile Header -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative h-32 bg-gradient-to-r from-orange-500 to-orange-600">
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
                                    <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                                    @else
                                    <h2 class="text-2xl font-bold text-gray-800">Guest</h2>
                                    @endauth
                                    @auth
                                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                                    @else
                                    <p class="text-gray-600">guest@example.com</p>
                                    @endauth
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="showEditProfileModal()" 
                                            class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition flex items-center">
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
                                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition flex items-center">
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                                            class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition">
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
        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        // Subscribe to the payments channel
        const channel = pusher.subscribe('payments');
        
        // Listen for payment status updates
        channel.bind('App\\Events\\PaymentStatusUpdated', function(data) {
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Processed',
                            text: 'Your payment has been processed. Please check your transaction status below.',
                            timer: 3000,
                            showConfirmButton: false
                        });
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
                Swal.fire({
                    title: 'Please Sign In',
                    text: 'You need to be logged in to view your profile',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Register',
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
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
                Swal.fire({
                    title: 'Please Sign In',
                    text: 'You need to be logged in to edit your profile',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Register',
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
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
                roomsPanel.classList.remove('show');
                bookingPanel.classList.remove('show');
                profilePanel.classList.remove('show');
                document.getElementById('transactionPanel').classList.remove('show');
                document.getElementById('paymentPanel').classList.remove('show');

                if (showPanel === 'rooms') {
                    roomsPanel.classList.add('show');
                } else if (showPanel === 'booking') {
                    bookingPanel.classList.add('show');
                }
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

            // Show rooms panel
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
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Handle profile photo upload
            window.handleProfilePhotoUpload = async function(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    
                    // Validate file type and size
                    if (!file.type.startsWith('image/')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Please select an image file'
                        });
                        input.value = '';
                        return;
                    }

                    if (file.size > 1024 * 1024) { // 1MB
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Please select an image less than 1MB'
                        });
                        input.value = '';
                        return;
                    }
                    
                    // Show loading state
                    Swal.fire({
                        title: 'Uploading...',
                        text: 'Please wait while we upload your photo',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
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
                        const profileSelector = @if(Auth::check()) 'img[alt="{{ Auth::user()->name }}"]' @else 'img[alt="Guest"]' @endif;
                        const profilePhotos = document.querySelectorAll(profileSelector);
                        if (data.profile_photo_url && profilePhotos.length > 0) {
                            profilePhotos.forEach(photo => {
                                photo.src = data.profile_photo_url + '?t=' + new Date().getTime();
                            });
                        }

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile photo updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Clear the file input
                        input.value = '';
                    } catch (error) {
                        console.error('Profile photo upload error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to upload profile photo'
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Authentication Required',
                        text: 'Please log in to update your profile.',
                        confirmButtonColor: '#f97316'
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
                        headerName: document.querySelector('.dropdown-button span')
                    };

                    // Update elements if they exist
                    if (profileElements.name) profileElements.name.textContent = data.user.name;
                    if (profileElements.email) profileElements.email.textContent = data.user.email;
                    if (profileElements.displayName) profileElements.displayName.value = data.user.name;
                    if (profileElements.displayEmail) profileElements.displayEmail.value = data.user.email;
                    if (profileElements.displayPhone) profileElements.displayPhone.value = data.user.phone;
                    if (profileElements.headerName) profileElements.headerName.textContent = data.user.name;

                    // Update profile photo if available
                    @if(Auth::check())
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Profile updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } catch (error) {
                    console.error('Profile update error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to update profile'
                    });
                }
            };

            // Handle password update
            window.showChangePasswordModal = function() {
                @auth
                Swal.fire({
                    title: 'Change Password',
                    html: `
                        <form id="passwordForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 text-left">Current Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="current_password" 
                                           name="current_password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('current_password')"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 text-left">New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('password')"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 text-left">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('password_confirmation')"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update Password',
                    confirmButtonColor: '#f97316',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        const formData = new FormData(document.getElementById('passwordForm'));
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

                            return data;
                        } catch (error) {
                            Swal.showValidationMessage(error.message);
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Password updated successfully',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
                @else
                Swal.fire({
                    title: 'Please Sign In',
                    text: 'You need to be logged in to change your password',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Register',
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
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
                Swal.fire({
                    title: 'Notification Preferences',
                    html: `
                        <form id="notificationForm" class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700">Email Notifications</label>
                                <input type="checkbox" name="email_notifications" class="rounded text-orange-500">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700">Push Notifications</label>
                                <input type="checkbox" name="push_notifications" class="rounded text-orange-500">
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Preferences',
                    confirmButtonColor: '#f97316',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
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

                            return response.json();
                        } catch (error) {
                            Swal.showValidationMessage(error.message);
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Notification preferences updated successfully'
                        });
                    }
                });
            };

            // Handle remove profile photo
            window.removeProfilePhoto = async function() {
                const result = await Swal.fire({
                    title: 'Remove Profile Photo?',
                    text: 'Are you sure you want to remove your profile photo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    try {
                        // Show loading state
                        Swal.fire({
                            title: 'Removing...',
                            text: 'Please wait while we remove your photo',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
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
                        const defaultAvatarUrl = @if(Auth::check()) 'https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF' @else 'https://ui-avatars.com/api/?name=Guest&color=7F9CF5&background=EBF4FF' @endif;

                        // Update all profile photos to default avatar
                        const profileSelector = @if(Auth::check()) 'img[alt="{{ Auth::user()->name }}"]' @else 'img[alt="Guest"]' @endif;
                        const profilePhotos = document.querySelectorAll(profileSelector);
                        profilePhotos.forEach(photo => {
                            photo.src = defaultAvatarUrl;
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile photo removed successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } catch (error) {
                        console.error('Profile photo removal error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to remove profile photo'
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Authentication Required',
                        text: 'Please log in to update your profile photo.',
                        confirmButtonColor: '#f97316'
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Profile photo updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Clear the file input
                    input.value = '';
                } catch (error) {
                    console.error('Profile photo upload error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to upload profile photo'
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to load payment panel',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
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
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Failed to load transactions');
                        }
                        
                        const data = await response.json();
                        if (data.html) {
                            document.getElementById('transactionList').innerHTML = data.html;
                        }
                    } catch (error) {
                        console.error('Error loading transactions:', error);
                        document.getElementById('transactionList').innerHTML = `
                            <div class="text-center py-4">
                                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-base font-medium text-gray-900">Error</h3>
                                <p class="mt-1 text-sm text-gray-500">Failed to load transactions. Please try again.</p>
                                <button onclick="window.transactionPanel.loadTransactions()" class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                    Retry
                                </button>
                            </div>
                        `;
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message || 'Transaction cancelled successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to cancel transaction. Please try again.'
                        });
                    }
                },
                async payTransaction(id) {
                    try {
                        const response = await fetch(`/transactions/${id}/pay`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Failed to process payment');
                        }

                        const data = await response.json();
                        
                        if (data.success && data.snap_token) {
                            // Open Midtrans Snap popup
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Payment Successful',
                                        text: 'Your payment has been processed successfully.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                },
                                onPending: function(result) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Payment Pending',
                                        text: 'Please complete your payment using the provided instructions.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                },
                                onError: function(result) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Payment Failed',
                                        text: 'An error occurred during payment. Please try again.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                },
                                onClose: function() {
                                    // Redirect to landing page with transaction panel and source
                                    window.location.href = '/?panel=transactions&source=midtrans';
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Failed to initialize payment');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to process payment. Please try again.'
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
    </script>
    @stack('scripts')
</body>
</html>