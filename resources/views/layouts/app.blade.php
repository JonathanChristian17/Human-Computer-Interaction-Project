<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Penginapan Cahaya' }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-r from-blue-900 via-teal-800 to-indigo-900 min-h-screen flex flex-col font-sans text-white">

    <!-- Navbar - Enhanced with dark theme -->
    <header class="bg-gray-900/80 backdrop-blur-lg shadow-lg sticky top-0 z-50 transition-all duration-300 border-b border-gray-700/50">
        <nav class="max-w-7xl mx-auto flex items-center justify-between py-4 px-6">
            <a href="/" class="text-2xl font-extrabold text-white hover:text-amber-400 transition duration-300 flex items-center" aria-label="Beranda">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="bg-gradient-to-r from-amber-400 to-yellow-300 bg-clip-text text-transparent">Cahaya</span>
            </a>
            
            <ul class="hidden md:flex gap-8 font-medium text-lg">
                <li>
                    <a href="#rooms" class="relative group text-gray-300 hover:text-white transition">
                        Kamar
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="#about" class="relative group text-gray-300 hover:text-white transition">
                        Tentang
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="#contact" class="relative group text-gray-300 hover:text-white transition">
                        Kontak
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
            </ul>
            
           {{-- <a href="{{ route('rooms.create') }}" 
   class="bg-yellow-400/80 backdrop-blur-md text-indigo-900 font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-400 transition"
   role="button" aria-label="Pesan kamar sekarang">
   Pesan
</a> --}}

@auth
    <!-- User dropdown menu -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
            <span class="text-gray-300 hover:text-white">{{ Auth::user()->name }}</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div x-show="open" @click.away="open = false" 
             class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-700">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    Keluar
                </button>
            </form>
        </div>
    </div>
@else
    <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" 
           class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
           Masuk
        </a>
        <a href="{{ route('register') }}" 
           class="relative overflow-hidden bg-gradient-to-r from-amber-500 to-yellow-400 text-gray-900 font-semibold px-5 py-2.5 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 group"
           role="button" aria-label="Daftar sekarang">
           <span class="relative z-10">Daftar</span>
           <span class="absolute inset-0 bg-gradient-to-r from-amber-600 to-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
        </a>
    </div>
@endauth
        </nav>
    </header>

    <!-- Konten -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer - Enhanced -->
    <footer class="bg-gray-900/90 text-gray-400 p-8 text-center shadow-inner border-t border-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="/" class="text-xl font-bold flex items-center justify-center md:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="bg-gradient-to-r from-amber-400 to-yellow-300 bg-clip-text text-transparent">Penginapan Cahaya</span>
                    </a>
                    <p class="mt-2 text-sm">Ketemu di Samosir, Danau Toba</p>
                </div>
                
                <div class="flex space-x-6 mb-6 md:mb-0">
                    <a href="#" class="text-gray-400 hover:text-amber-400 transition" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-amber-400 transition" aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-amber-400 transition" aria-label="WhatsApp">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-6.29 3.01c-.173.075-.347.124-.52.124-.173 0-.347-.05-.495-.148-.148-.099-1.137-.563-1.938-1.712-.793-1.14-.869-2.133-.869-2.233.008-.1.05-.198.174-.273.124-.074.297-.05.446-.025.148.025.892.174 1.223.594.331.421.541.694.644.793.099.1.198.124.347.025.148-.099-.545-.421-.993-.793-.446-.372-.892-.843-1.04-1.042-.148-.198-.012-.306.112-.406.115-.093.297-.223.446-.338.148-.115.198-.198.297-.338.099-.139.05-.26-.025-.372-.075-.115-.594-1.429-.816-1.952-.223-.533-.446-.458-.614-.458-.173 0-.364.033-.545.09-.18.058-.342.145-.495.255-.446.347-1.072 1.08-1.072 2.223 0 1.144 1.074 2.555 1.223 2.719.149.174 2.112 3.22 5.15 4.487.52.223.825.297 1.105.347.446.083.853.058 1.174.033.384-.033 1.189-.434 1.356-.868.165-.434.165-.806.115-.868-.05-.066-.183-.099-.384-.198"/>
                            <path d="M12 0a12 12 0 100 24 12 12 0 000-24zm5.797 17.305a3.661 3.661 0 01-2.108 2.108c-1.56.597-7.303.537-9.947-2.108-2.643-2.643-2.704-8.388-2.108-9.946a3.661 3.661 0 012.108-2.108c1.559-.597 7.303-.537 9.947 2.108 2.644 2.644 2.705 8.388 2.108 9.946z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-800 text-sm">
                &copy; {{ date('Y') }} <span class="font-semibold text-amber-400">Penginapan Cahaya</span>. Semua hak cipta dilindungi.
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
    @yield('scripts')

</body>
</html>