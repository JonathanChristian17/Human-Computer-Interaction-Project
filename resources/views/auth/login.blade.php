@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <!-- Logo dan Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <h2>Masuk</h2>
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
        </div>
        <div class="remember">
            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-orange-500 focus:ring-orange-400 border-gray-300 rounded bg-gray-100">
            <label for="remember_me">Ingat saya</label>
        </div>
        <button type="submit" class="mt-4 w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200" style="background-color: #FFA040; font-family:'Poppins',sans-serif;" onmouseover="this.style.backgroundColor='#ff8c1a'" onmouseout="this.style.backgroundColor='#FFA040'">Masuk</button>
        <div class="text-center mt-4">
            <span class="text-gray-300">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="lost-password ml-1">Daftar sekarang</a>
        </div>
        <a href="{{ route('password.request') }}" class="lost-password">Lupa Kata Sandi?</a>
        <button type="button" onclick="window.location.href='{{ route('google.login') }}'" class="button">
            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262" class="svg">
                <path fill="#4285F4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" class="blue"></path>
                <path fill="#34A853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" class="green"></path>
                <path fill="#FBBC05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" class="yellow"></path>
                <path fill="#EB4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" class="red"></path>
            </svg>
            <span class="text">Masuk dengan Google</span>
        </button>
        <p class="terms">
            Dengan mengklik "Masuk" Anda menyetujui <br>
            <a href="#">Syarat dan Ketentuan</a> | <a href="#">Kebijakan Privasi</a>
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
    </script>
@endsection
