@extends('layouts.auth')
@section('title', 'Reset Password')
@section('content')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Logo dan Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <h2>Reset Password</h2>
    <p class="mb-4">Please enter your new password</p>
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
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('reset_email') }}">
        <input type="hidden" name="token" value="{{ session('reset_token') }}">
        <div class="nebula-input relative">
            <input id="password" name="password" type="password" class="input" required autocomplete="new-password" />
            <label class="user-label">New Password</label>
            <span class="password-toggle" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
            </span>
            <div id="password-requirements" class="password-requirements hidden">
                <h4 class="text-sm font-semibold mb-2">Password must meet:</h4>
                <ul class="text-sm space-y-1">
                    <li class="requirement" data-requirement="length">
                        <span class="check"></span> Minimum 8 characters
                    </li>
                    <li class="requirement" data-requirement="uppercase">
                        <span class="check"></span> Contains uppercase letter
                    </li>
                    <li class="requirement" data-requirement="lowercase">
                        <span class="check"></span> Contains lowercase letter
                    </li>
                    <li class="requirement" data-requirement="number">
                        <span class="check"></span> Contains number
                    </li>
                </ul>
            </div>
        </div>
        <div class="nebula-input relative">
            <input id="password_confirmation" name="password_confirmation" type="password" class="input" required autocomplete="new-password" />
            <label class="user-label">Confirm Password</label>
            <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        <button type="submit" class="mt-4 w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200" style="background-color: #FFA040; font-family:'Poppins',sans-serif;" onmouseover="this.style.backgroundColor='#ff8c1a'" onmouseout="this.style.backgroundColor='#FFA040'">Update Password</button>
    </form>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .animate-fade-in {
            animation: fade-in 0.5s;
        }

        .password-requirements {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(12px);
            padding: 20px;
            border-radius: 12px;
            margin-top: 12px;
            z-index: 10;
            border: 1px solid rgba(255, 214, 0, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        .password-requirements.show {
            opacity: 1;
            transform: translateY(0);
        }
        .password-requirements h4 {
            color: #FFD600;
            font-size: 0.95em;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .password-requirements ul {
            color: #fff;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .requirement {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease;
        }
        .requirement:last-child {
            margin-bottom: 0;
        }
        .requirement .check {
            color: #ff4444;
            font-size: 14px;
            transition: all 0.2s ease;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 68, 68, 0.1);
        }
        .requirement.valid {
            color: #fff;
        }
        .requirement.valid .check {
            color: #00C851;
            background: rgba(0, 200, 81, 0.1);
        }
        .requirement .check::before {
            content: "✕";
        }
        .requirement.valid .check::before {
            content: "✓";
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
            z-index: 2;
        }
        .password-toggle:hover {
            color: #FFD600;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const requirements = document.getElementById('password-requirements');
            const requirementItems = document.querySelectorAll('.requirement');

            function togglePassword(inputId) {
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
            }

            // Make togglePassword function globally available
            window.togglePassword = togglePassword;

            passwordInput.addEventListener('focus', function() {
                requirements.classList.remove('hidden');
                setTimeout(() => requirements.classList.add('show'), 10);
            });

            passwordInput.addEventListener('blur', function(e) {
                if (!requirements.contains(e.relatedTarget)) {
                    requirements.classList.remove('show');
                    setTimeout(() => requirements.classList.add('hidden'), 300);
                }
            });

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Check length
                const hasLength = password.length >= 8;
                updateRequirement('length', hasLength);

                // Check uppercase
                const hasUppercase = /[A-Z]/.test(password);
                updateRequirement('uppercase', hasUppercase);

                // Check lowercase
                const hasLowercase = /[a-z]/.test(password);
                updateRequirement('lowercase', hasLowercase);

                // Check number
                const hasNumber = /[0-9]/.test(password);
                updateRequirement('number', hasNumber);
            });

            function updateRequirement(type, isValid) {
                const requirement = document.querySelector(`[data-requirement="${type}"]`);
                if (isValid) {
                    requirement.classList.add('valid');
                } else {
                    requirement.classList.remove('valid');
                }
            }

            // Add form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);

                if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
                    e.preventDefault();
                    requirements.classList.remove('hidden');
                    requirements.classList.add('show');
                }
            });

            // Floating alert auto-hide
            setTimeout(() => {
                const alert = document.getElementById('floating-alert');
                if(alert) alert.style.display = 'none';
            }, 4000);
        });
    </script>
@endsection