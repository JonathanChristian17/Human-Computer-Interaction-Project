@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('content')
    <!-- Logo dan Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <h2>Lupa Password?</h2>
    <p class="mb-4">Masukkan email Anda untuk menerima kode verifikasi</p>
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
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="nebula-input">
            <input id="email" name="email" type="email" class="input" required autocomplete="email" value="{{ old('email') }}" />
            <label class="user-label">Email</label>
        </div>
        <button type="submit" class="mt-4 w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200" style="background-color: #FFA040; font-family:'Poppins',sans-serif;" onmouseover="this.style.backgroundColor='#ff8c1a'" onmouseout="this.style.backgroundColor='#FFA040'">Kirim Kode Verifikasi</button>
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="lost-password">Kembali ke halaman login</a>
        </div>
    </form>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .animate-fade-in {
            animation: fade-in 0.5s;
        }
    </style>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('floating-alert');
            if(alert) alert.style.display = 'none';
        }, 4000);
    </script>
@endsection