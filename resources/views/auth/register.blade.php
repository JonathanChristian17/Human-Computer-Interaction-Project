@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Logo and Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <h2 class="mb-5">Register</h2>
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
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        <div class="nebula-input">
            <input id="name" name="name" type="text" class="input" required autocomplete="name" value="{{ old('name') }}" />
            <label class="user-label">Full Name</label>
        </div>
        <div class="nebula-input">
            <input id="email" name="email" type="email" class="input" required autocomplete="email" value="{{ old('email') }}" oninput="validateEmail(this)" />
            <label class="user-label">Email</label>
            <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>
        <div class="nebula-input">
            <input id="phone" name="phone" type="tel" class="input" required autocomplete="tel" value="{{ old('phone') }}" oninput="validatePhoneNumber(this)" onkeypress="return isNumberKey(event)" maxlength="15" />
            <label class="user-label">Phone Number</label>
            <div id="phone-error" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>
        <div class="nebula-input relative">
            <input id="password" name="password" type="password" class="input" required autocomplete="new-password" />
            <label class="user-label">Password</label>
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
        <div class="nebula-input">
            <input id="password-confirm" name="password_confirmation" type="password" class="input" required autocomplete="new-password" />
            <label class="user-label">Confirm Password</label>
            <span class="password-toggle" onclick="togglePassword('password-confirm')">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        <button type="submit" id="registerButton" class="mt-4 w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center justify-center" style="background-color: #FFA040; font-family:'Poppins',sans-serif;" onmouseover="this.style.backgroundColor='#ff8c1a'" onmouseout="this.style.backgroundColor='#FFA040'">
            <span>Register</span>
            <div class="loading-spinner ml-3 hidden"></div>
        </button>
        <button type="button" onclick="window.location.href='{{ route('google.login') }}'" class="button mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262" class="svg">
                <path fill="#4285F4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" class="blue"></path>
                <path fill="#34A853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" class="green"></path>
                <path fill="#FBBC05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" class="yellow"></path>
                <path fill="#EB4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" class="red"></path>
            </svg>
            <span class="text">Register with Google</span>
        </button>
        <div class="text-center mt-4">
            <span class="text-gray-300">Already have an account?</span>
            <a href="{{ route('login') }}" class="lost-password ml-1">Login</a>
        </div>
    </form>

    <style>
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
            z-index: 2;
        }
        .password-toggle:hover {
            color: #FFD600;
        }
        
        /* Add phone error styling */
        #phone-error {
            color: #ff4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            position: absolute;
            bottom: -1.5rem;
        }
        
        .input-error {
            border-color: #ff4444 !important;
        }
        
        /* Add email error styling */
        #email-error {
            color: #ff4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            position: absolute;
            bottom: -1.5rem;
        }

        /* Add loading spinner styles */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const requirements = document.getElementById('password-requirements');
            const requirementItems = document.querySelectorAll('.requirement');
            const phoneInput = document.getElementById('phone');
            const phoneError = document.getElementById('phone-error');
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const form = document.getElementById('registerForm');
            const button = document.getElementById('registerButton');
            const spinner = button.querySelector('.loading-spinner');
            const buttonText = button.querySelector('span');

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

            // Phone number validation functions
            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }

            function validatePhoneNumber(input) {
                // Remove any non-numeric characters
                input.value = input.value.replace(/[^0-9]/g, '');
                
                // Get the error display element
                const errorElement = document.getElementById('phone-error');
                
                // Validate length
                if (input.value.length < 10 || input.value.length > 15) {
                    errorElement.textContent = 'Phone number must be between 10 and 15 digits';
                    errorElement.classList.remove('hidden');
                    input.classList.add('input-error');
                    return false;
                }
                
                // If all validations pass
                errorElement.classList.add('hidden');
                input.classList.remove('input-error');
                return true;
            }

            // Email validation function
            function validateEmail(input) {
                const email = input.value;
                const errorElement = document.getElementById('email-error');
                
                // Regular expression for email validation
                const emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                
                // Check if empty
                if (!email) {
                    showEmailError('Email is required');
                    return false;
                }
                
                // Check basic format
                if (!email.includes('@')) {
                    showEmailError('Email must contain @');
                    return false;
                }
                
                // Split email into local and domain parts
                const [localPart, domainPart] = email.split('@');
                
                // Validate local part (before @)
                if (!localPart) {
                    showEmailError('Username before @ is required');
                    return false;
                }
                
                // Check for invalid characters in local part
                if (!/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*$/.test(localPart)) {
                    showEmailError('Username can only contain letters, numbers, and . _ + -');
                    return false;
                }
                
                // Validate domain part (after @)
                if (!domainPart) {
                    showEmailError('Domain after @ is required');
                    return false;
                }
                
                // Check domain format
                if (!/^[a-zA-Z0-9][a-zA-Z0-9.-]*\.[a-zA-Z]{2,}$/.test(domainPart)) {
                    showEmailError('Invalid domain format');
                    return false;
                }
                
                // Check for consecutive special characters
                if (/[._%+-]{2,}/.test(localPart)) {
                    showEmailError('Cannot have consecutive special characters');
                    return false;
                }
                
                // If all validations pass
                errorElement.classList.add('hidden');
                input.classList.remove('input-error');
                return true;
            }
            
            function showEmailError(message) {
                const errorElement = document.getElementById('email-error');
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                emailInput.classList.add('input-error');
            }

            // Modify form submission handler
            form.addEventListener('submit', function(e) {
                const isPhoneValid = validatePhoneNumber(phoneInput);
                const isEmailValid = validateEmail(emailInput);
                
                if (!isPhoneValid || !isEmailValid) {
                    e.preventDefault(); // Prevent form submission if validation fails
                    return;
                }

                // Show loading state
                button.disabled = true;
                spinner.classList.remove('hidden');
                buttonText.textContent = 'Registering...';
            });

            // Make validation functions globally available
            window.validateEmail = validateEmail;
            window.isNumberKey = isNumberKey;
            window.validatePhoneNumber = validatePhoneNumber;

            // Floating alert auto-hide
            setTimeout(() => {
                const alert = document.getElementById('floating-alert');
                if(alert) alert.style.display = 'none';
            }, 4000);
        });

        // Add custom alert function if not already present
        function showCustomAlert(message, type = 'error') {
            const alertBox = document.createElement('div');
            alertBox.className = `custom-alert ${type}`;
            alertBox.innerHTML = `
                <div class="alert-icon">
                    <span style="color:${type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#22c55e'}">
                        ${type === 'error' ? '✕' : type === 'warning' ? '!' : '✓'}
                    </span>
                </div>
                <div class="alert-message">${message}</div>
            `;
            document.body.appendChild(alertBox);

            setTimeout(() => {
                alertBox.style.animation = 'fadeOutUp 0.5s';
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    </script>
@endsection