<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verifikasi Kode - Cahaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <div class="min-h-screen flex items-stretch">
        <!-- Left: Form Section -->
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 py-12 bg-white">
            <div class="w-full max-w-md space-y-8">
                <div class="flex items-center mb-8">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                        <circle cx="16" cy="16" r="10" stroke="#3B82F6" stroke-width="2" fill="#fff"/>
                        <path d="M16 4V8" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16 24V28" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 16H8" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M24 16H28" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7.757 7.757L10.586 10.586" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21.414 21.414L24.243 24.243" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7.757 24.243L10.586 21.414" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21.414 10.586L24.243 7.757" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="text-2xl font-bold text-gray-800">Cahaya</span>
                </div>

                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Verifikasi Kode</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Masukkan kode verifikasi yang telah kami kirim ke email Anda
                    </p>
                </div>

                <form class="mt-8 space-y-6" method="POST" action="{{ route('password.verify-code') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">

                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Verifikasi</label>
                        <input id="code" name="code" type="text" required maxlength="6"
                               class="appearance-none relative block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 transition text-center tracking-widest text-2xl"
                               placeholder="000000">
                    </div>

                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition">
                        Verifikasi Kode
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="font-medium text-orange-500 hover:text-orange-600 transition text-sm">
                            Kembali ke halaman login
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Image -->
        <div class="hidden md:block md:w-1/2 h-screen relative">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
                 alt="Luxury Hotel" 
                 class="object-cover w-full h-full rounded-l-3xl shadow-xl">
        </div>
    </div>
</body>
</html>