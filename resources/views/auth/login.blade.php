@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Logo and Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <!-- Terms Modal -->
    <div id="termsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-lg p-8 max-w-2xl max-h-[80vh] overflow-y-auto custom-scrollbar border border-orange-500 shadow-xl">
            <h2 class="text-2xl font-bold mb-4 text-orange-400">Terms and Conditions</h2>
            <div class="space-y-4">
                <section>
                    <h3 class="font-bold text-orange-300 mt-10">1. General</h3>
                    <p class="text-gray-300">By using the services on this application, users are deemed to have read, understood, and agreed to all applicable terms and conditions.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">2. Room Booking</h3>
                    <p class="text-gray-300">Room bookings are made through the web application online.</p>
                    <p class="text-gray-300">Users are required to provide true and accurate data when making reservations.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">3. Check-in and Check-out Times</h3>
                    <p class="text-gray-300">Check-in starts at 3:00 PM (15:00).</p>
                    <p class="text-gray-300">Check-out is at 12:00 PM (noon) on the departure day.</p>
                    <p class="text-gray-300">Late check-out may incur additional charges.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">4. Cancellation and Refund</h3>
                    <p class="text-gray-300">No refunds are available for cancellations under any circumstances, including unilateral cancellation by the user.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">5. User Responsibilities</h3>
                    <p class="text-gray-300">Users are fully responsible for all information provided during booking.</p>
                    <p class="text-gray-300">Users must maintain order and not damage facilities during their stay.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">6. Management Rights</h3>
                    <p class="text-gray-300">Management reserves the right to cancel bookings or refuse users if there are violations of these terms and conditions.</p>
                </section>
            </div>
            <button onclick="closeModal('termsModal')" class="mt-6 px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200 shadow-lg">Close</button>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div id="privacyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-lg p-8 max-w-2xl max-h-[80vh] overflow-y-auto custom-scrollbar border border-orange-500 shadow-xl">
            <h2 class="text-2xl font-bold mb-4 text-orange-400">Privacy Policy</h2>
            <div class="space-y-4">
                <section>
                    <h3 class="font-bold text-orange-300 mt-10">1. Information Collected</h3>
                    <p class="text-gray-300">We collect personal information that you provide when making a reservation, such as:</p>
                    <ul class="list-disc ml-6 text-gray-300">
                        <li>Full name</li>
                        <li>Phone number</li>
                        <li>Email address</li>
                        <li>Booking details and room preferences</li>
                    </ul>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">2. Use of Information</h3>
                    <p class="text-gray-300">The collected information will be used to:</p>
                    <ul class="list-disc ml-6 text-gray-300">
                        <li>Process your booking</li>
                        <li>Contact you regarding booking status</li>
                        <li>Improve our application services</li>
                    </ul>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">3. Storage and Security</h3>
                    <p class="text-gray-300">Your data is stored securely and accessed only by authorized personnel.</p>
                    <p class="text-gray-300">We use appropriate technical and organizational measures to protect your data from unauthorized access.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">4. Information Sharing</h3>
                    <p class="text-gray-300">We will not sell or rent your personal information to third parties without permission, except as required by law.</p>
                </section>
                <section>
                    <h3 class="font-bold text-orange-300">5. Policy Changes</h3>
                    <p class="text-gray-300">This policy may be updated from time to time. Any changes will be communicated through the application.</p>
                </section>
            </div>
            <button onclick="closeModal('privacyModal')" class="mt-6 px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200 shadow-lg">Close</button>
        </div>
    </div>

    <h2>Login</h2>
    @if($errors->any())
        <div id="floating-alert" class="fixed top-6 right-6 z-50 bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg animate-fade-in">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div id="floating-alert" class="fixed top-6 right-6 z-50 bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg animate-fade-in">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="nebula-input">
            <input id="email" name="email" type="email" class="input" required autocomplete="email" value="{{ old('email') }}" />
            <label class="user-label">Email Address</label>
        </div>
        <div class="nebula-input">
            <input id="password" name="password" type="password" class="input" required autocomplete="current-password" />
            <label class="user-label">Password</label>
            <span class="password-toggle" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        <button type="submit" class="mt-4 w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200" style="background-color: #FFA040; font-family:'Poppins',sans-serif;" onmouseover="this.style.backgroundColor='#ff8c1a'" onmouseout="this.style.backgroundColor='#FFA040'">Login</button>
        <div class="text-center mt-4">
            <span class="text-gray-300">Don't have an account?</span>
            <a href="{{ route('register') }}" class="lost-password ml-1">Register now</a>
        </div>
        <a href="{{ route('password.request') }}" class="lost-password">Forgot Password?</a>
        <button type="button" onclick="window.location.href='{{ route('google.login') }}'" class="button">
            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262" class="svg">
                <path fill="#4285F4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" class="blue"></path>
                <path fill="#34A853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" class="green"></path>
                <path fill="#FBBC05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" class="yellow"></path>
                <path fill="#EB4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" class="red"></path>
            </svg>
            <span class="text">Login with Google</span>
        </button>
        <p class="terms">
            By clicking "Login" you agree to our <br>
            <a href="#" onclick="openModal('termsModal'); return false;">Terms and Conditions</a> | <a href="#" onclick="openModal('privacyModal'); return false;">Privacy Policy</a>
        </p>
    </form>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .animate-fade-in {
            animation: fade-in 0.5s;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }
        .password-toggle:hover {
            color: #FFD600;
        }
        .nebula-input {
            position: relative;
        }
        .modal {
            transition: opacity 0.3s ease-in-out;
        }
        .modal-content {
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
        }
        .modal.show .modal-content {
            transform: scale(1);
        }

        /* Custom Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(75, 85, 99, 0.2);
            border-radius: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
            border-radius: 5px;
            border: 2px solid rgba(31, 41, 55, 0.8);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.7);
        }

        /* Firefox Scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(75, 85, 99, 0.5) rgba(75, 85, 99, 0.2);
        }
    </style>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        setTimeout(() => {
            const alert = document.getElementById('floating-alert');
            if(alert) alert.style.display = 'none';
        }, 4000);

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
    </script>
@endsection
