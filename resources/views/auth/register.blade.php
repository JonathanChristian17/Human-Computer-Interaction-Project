<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Akun Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8 bg-gray-800/70 backdrop-blur-sm p-10 rounded-xl shadow-xl border border-gray-700/50">
            
            <div class="mb-6 relative">
                <a href="{{ route('home') }}" 
                   class="absolute top-0 right-0 bottom-2 text-amber-400 hover:text-amber-300 transition font-semibold inline-flex items-center gap-2">
                    <span>Kembali ke Home</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 
                                 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
            </div>

            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-white">
                    Daftar Akun Baru
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-amber-400 hover:text-amber-300 transition">
                        Masuk disini
                    </a>
                </p>
            </div>
            
            <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-500/20 text-red-300 p-4 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap (full width) -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               class="appearance-none relative block w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                               placeholder="Nama Anda" value="{{ old('name') }}">
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Alamat Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="appearance-none relative block w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                               placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>
                    
                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" required
                               class="appearance-none relative block w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                               placeholder="08123456789" value="{{ old('phone') }}">
                    </div>
                    
                    <!-- Password -->
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="appearance-none relative block w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition pr-12"
                            placeholder="Password">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute inset-y-0 right-3 top-6 flex items-center text-gray-400 hover:text-white focus:outline-none">
                            <svg id="icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Konfirmasi Password -->
                    <div class="relative">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-300 mb-1">Konfirmasi Password</label>
                        <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required
                            class="appearance-none relative block w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition pr-12"
                            placeholder="Ulangi Password">
                        <button type="button" onclick="togglePassword('password-confirm')" 
                                class="absolute inset-y-0 right-3 top-6 flex items-center text-gray-400 hover:text-white focus:outline-none">
                            <svg id="icon-password-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400 transition">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-amber-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Daftar
                    </button>
                </div>
            </form>
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