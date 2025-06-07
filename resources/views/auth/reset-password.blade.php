<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password - Cahaya</title>
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
                    <h2 class="text-3xl font-extrabold text-gray-900">Reset Password</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Silakan masukkan password baru Anda
                    </p>
                </div>

                <form class="mt-8 space-y-6" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    <input type="hidden" name="token" value="{{ session('reset_token') }}">

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

                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                       class="appearance-none relative block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 transition pr-10"
                                       placeholder="Masukkan password baru">
                                <button type="button" onclick="togglePassword('password')" 
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg id="icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="appearance-none relative block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-gray-900 placeholder-gray-400 transition pr-10"
                                       placeholder="Konfirmasi password baru">
                                <button type="button" onclick="togglePassword('password_confirmation')" 
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg id="icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Image -->
        <div class="hidden md:block md:w-1/2 h-screen relative">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
                 alt="Luxury Hotel" 
                 class="object-cover w-full h-full rounded-l-3xl shadow-xl">
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