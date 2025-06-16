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
            <i class="fas fa-envelope-open-text text-5xl text-[#FFA040]"></i>
        </div>
        <h2 class="text-2xl font-bold mb-4">Verify Your Email Address</h2>
        
        @if(isset($warning) && $warning)
            <div class="mb-6 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                {{ $message ?? 'You need to verify your email address before proceeding.' }}
            </div>
        @endif

        <p class="mb-6 text-gray-300">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="flex flex-col gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
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
                <button type="submit" class="w-full py-3 text-gray-400 font-semibold rounded-lg border border-gray-600 hover:bg-gray-700 transition-colors duration-200">
                    Log Out
                </button>
            </form>
        </div>
    </div>
@endsection 