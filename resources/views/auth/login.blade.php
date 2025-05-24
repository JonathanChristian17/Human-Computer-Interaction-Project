<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Akun Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, html { height: 100%; }
        .rounded-tl-3xl { border-top-left-radius: 2rem; }
        .rounded-bl-3xl { border-bottom-left-radius: 2rem; }
        .rounded-tr-3xl { border-top-right-radius: 2rem; }
        .rounded-br-3xl { border-bottom-right-radius: 2rem; }
    </style>
</head>
<body class="bg-white min-h-screen flex items-stretch">
    <div class="flex w-full min-h-screen">
        <!-- Left: Login Form -->
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
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Sign in</h2>
                <p class="text-gray-500 mb-6">Please login to continue to your account.</p>
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    @if($errors->any())
                        <div class="bg-red-100 text-red-600 p-4 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="appearance-none relative block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 transition"
                               placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none relative block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 transition"
                               placeholder="Password">
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                   class="h-4 w-4 text-orange-500 focus:ring-orange-400 border-gray-300 rounded bg-gray-100">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                Keep me logged in
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-orange-500 hover:text-orange-600 transition">
                                Lupa Password?
                            </a>
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition">
                        Sign in
                    </button>
                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="mx-2 text-gray-400">or</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>
                    <button type="button" class="w-full flex items-center justify-center py-3 px-4 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-5 w-5 mr-2">
                        Sign in with Google
                    </button>
                    <div class="text-center mt-4">
                        <span class="text-gray-500">Need an account?</span>
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition ml-1">Create one</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Right: Image -->
        <div class="hidden md:block md:w-1/2 h-screen relative">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Villa" class="object-cover w-full h-full rounded-l-3xl shadow-xl">
        </div>
    </div>
    @if(session('welcome'))
    <div id="welcome-popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm text-center shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Selamat datang!</h2>
            <p>{{ session('welcome') }}</p>
            <button onclick="document.getElementById('welcome-popup').style.display='none'"
                    class="mt-4 px-4 py-2 bg-orange-400 text-white rounded hover:bg-orange-500">
                Tutup
            </button>
        </div>
    </div>
    @endif
</body>
</html>
