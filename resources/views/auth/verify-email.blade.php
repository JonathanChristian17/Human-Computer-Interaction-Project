@extends('layouts.auth')
@section('title', 'Verify Your Email')
@section('content')
    <!-- Logo and Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <div class="text-center">
        <div class="mb-6">
            <i class="fas fa-envelope-open-text text-5xl text-[#FFA040] mb-3"></i>
        <h2 class="text-2xl font-bold mb-4 text-white">Verify Your Email Address</h2>
                </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                A new verification link has been sent to your email address.
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-6 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                {{ session('warning') }}
            </div>
        @endif

        <div class="mb-6 text-gray-300">
            Before proceeding to book a room, please check your email for a verification link.
            If you didn't receive the email, click the button below to request another.
        </div>

        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200" 
                    style="background-color: #FFA040; font-family:'Poppins',sans-serif;" 
                    onmouseover="this.style.backgroundColor='#ff8c1a'" 
                    onmouseout="this.style.backgroundColor='#FFA040'">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                Log Out
            </button>
        </form>
    </div>

    @if(session('showAlert'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertBox = document.createElement('div');
            alertBox.className = 'custom-alert warning';
            alertBox.innerHTML = `
                <div class="alert-icon">
                    <span style="color:#f59e0b;">&#33;</span>
                </div>
                <div class="alert-message">
                    {{ session('warning') }}
                </div>
            `;
            document.body.appendChild(alertBox);

            setTimeout(() => {
                alertBox.style.animation = 'fadeOutUp 0.5s';
                setTimeout(() => {
                    alertBox.remove();
                }, 500);
            }, 3000);
        });
    </script>

    <style>
        .custom-alert {
            width: 24em;
            min-height: 4.5em;
            background: #171717;
            color: white;
            border-radius: 20px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 1em;
            padding: 1.2em 2em;
            position: fixed;
            top: 2em;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            font-family: 'Poppins', sans-serif;
            font-size: 1.2em;
            animation: fadeInDown 0.5s;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px) translateX(-50%);}
            to { opacity: 1; transform: translateY(0) translateX(-50%);}
        }
        @keyframes fadeOutUp {
            from { opacity: 1; transform: translateY(0) translateX(-50%);}
            to   { opacity: 0; transform: translateY(-30px) translateX(-50%);}
        }
        .custom-alert.warning {
            border-left: 10px solid #f59e0b;
        }
        .alert-icon {
            font-size: 2.5em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @endif
@endsection
