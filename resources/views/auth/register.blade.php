<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign up - Cahaya</title>
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
        <!-- Left: Register Form -->
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 py-12 bg-white">
            <div class="w-full max-w-sm space-y-6">
                <div class="flex items-center mb-6">
                    <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
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
                    <span class="text-xl font-bold text-gray-800">Cahaya</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Sign up</h2>
                <p class="text-xs text-gray-500 mb-4">Sign up to enjoy the feature of Revolutie</p>
                @if($errors->any())
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg text-xs">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="space-y-3" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               class="block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm transition"
                               placeholder="Nama Anda" value="{{ old('name') }}">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm transition"
                               placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" required
                               class="block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm transition"
                               placeholder="08123456789" value="{{ old('phone') }}">
                    </div>
                    <div class="relative">
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm transition pr-10"
                            placeholder="Password">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute inset-y-0 right-2 top-5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="icon-password" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative">
                        <label for="password-confirm" class="block text-xs font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required
                            class="block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm transition pr-10"
                            placeholder="Ulangi Password">
                        <button type="button" onclick="togglePassword('password-confirm')" 
                                class="absolute inset-y-0 right-2 top-5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="icon-password-confirm" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent text-xs font-medium rounded-md text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition">
                        Sign up
                    </button>
                    <div class="flex items-center my-3">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="mx-2 text-gray-400 text-xs">or</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>
                    <button type="button" class="w-full flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 text-xs font-medium transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-4 w-4 mr-2">
                        Continue with Google
                    </button>
                    <div class="text-center mt-3">
                        <span class="text-gray-500 text-xs">Already have an account??</span>
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition ml-1 text-xs">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Right: Image -->
        <div class="hidden md:block md:w-1/2 h-screen relative">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Villa" class="object-cover w-full h-full rounded-l-3xl shadow-xl">
        </div>
    </div>
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.querySelector(`#icon-${fieldId}`);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.964 
                             9.964 0 013.401-4.568m3.13-1.396A9.956 9.956 0 0112 5c4.478 0 8.268 
                             2.943 9.542 7a9.953 9.953 0 01-4.243 5.132M15 12a3 3 0 11-6 
                             0 3 3 0 016 0zM3 3l18 18"/>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                             9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>
</html>