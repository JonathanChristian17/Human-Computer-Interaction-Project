@extends('layouts.auth')
@section('title', 'Verifikasi Email')
@section('content')
    <!-- Logo dan Title -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-3 hover:opacity-80 transition-opacity">
        <img src="{{ asset('favicon.ico') }}" alt="Cahaya Resort Logo" class="w-10 h-10">
        <h1 class="text-xl font-bold text-white">Cahaya Resort</h1>
    </a>

    <h2>Verifikasi Email</h2>
    <p class="mb-4">Sebelum mulai, silakan verifikasi email Anda dengan mengklik link yang telah kami kirim. Jika belum menerima email, Anda dapat meminta ulang.</p>
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
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="mt-4 w-full py-3 text-white font-semibold rounded-lg transition-colors duration-200" style="background-color: #FFA040; font-family:'Poppins',sans-serif;" onmouseover="this.style.backgroundColor='#ff8c1a'" onmouseout="this.style.backgroundColor='#FFA040'">Kirim Ulang Email Verifikasi</button>
    </form>
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="lost-password">Keluar</button>
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
