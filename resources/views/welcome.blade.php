<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Penginapan Cahaya - Tempat Nyaman untuk Istirahat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-700 min-h-screen flex flex-col font-sans">

    <!-- Navbar -->
    <header class="bg-white bg-opacity-30 backdrop-blur-md shadow-md sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto flex items-center justify-between py-4 px-6">
            <a href="/" class="text-indigo-900 font-extrabold text-3xl hover:text-yellow-400 transition" aria-label="Beranda Penginapan Cahaya">
                Penginapan Cahaya
            </a>
            <ul class="hidden md:flex space-x-8 text-white font-medium text-lg">
                <li><a href="#rooms" class="hover:text-yellow-300 focus:outline-none focus:text-yellow-300 transition" tabindex="0">Kamar</a></li>
                <li><a href="#about" class="hover:text-yellow-300 focus:outline-none focus:text-yellow-300 transition" tabindex="0">Tentang Kami</a></li>
                <li><a href="#contact" class="hover:text-yellow-300 focus:outline-none focus:text-yellow-300 transition" tabindex="0">Kontak</a></li>
            </ul>
            <a href="{{ route('rooms.create') }}" 
               class="ml-4 bg-yellow-400 text-indigo-900 font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-400 transition"
               role="button" aria-label="Pesan kamar sekarang">
               Pesan Sekarang
            </a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="flex-grow flex flex-col justify-center items-center text-center px-6 md:px-12 py-20">
        <h1 class="text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg leading-tight max-w-4xl">
            Selamat Datang di <br />
            <span class="text-yellow-300">Penginapan Cahaya</span>
        </h1>
        <p class="mt-6 max-w-3xl text-white text-xl md:text-2xl drop-shadow-md leading-relaxed">
            Tempat nyaman untuk istirahat Anda dengan pelayanan terbaik dan harga terjangkau.
        </p>
        <a href="#rooms" 
           class="mt-10 bg-yellow-400 text-indigo-900 font-semibold px-10 py-3 rounded-lg shadow-lg hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-400 transition"
           role="button" aria-label="Lihat kamar kami">
           Lihat Kamar Kami
        </a>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="bg-white rounded-t-3xl p-8 max-w-7xl mx-auto mt-16 shadow-lg">
        <h2 class="text-3xl font-bold mb-8 text-center text-indigo-900">Daftar Kamar</h2>

        @if($rooms->isEmpty())
            <p class="text-center text-gray-600 text-lg">Belum ada kamar yang tersedia saat ini.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($rooms as $room)
                    <article tabindex="0" role="region" aria-labelledby="room-{{ $room->id }}-name" 
                             class="border rounded-lg p-6 hover:shadow-xl transition cursor-pointer focus:outline-none focus:ring-4 focus:ring-yellow-400">
                        <h3 id="room-{{ $room->id }}-name" class="font-semibold text-2xl text-indigo-900 mb-3">{{ $room->name }}</h3>
                        <p class="text-gray-700 mb-5">{{ \Illuminate\Support\Str::limit($room->description, 100) }}</p>
                        <p class="font-bold text-yellow-600 mb-2 text-lg">Rp{{ number_format($room->price, 0, ',', '.') }}</p>
                        <p class="text-gray-600 text-md">Kapasitas: {{ $room->capacity }} orang</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <!-- About Section -->
    <section id="about" class="max-w-7xl mx-auto p-8 mt-20 text-center text-white px-6 md:px-0">
        <h2 class="text-3xl font-bold mb-6">Tentang Penginapan Cahaya</h2>
        <p class="max-w-4xl mx-auto leading-relaxed text-lg md:text-xl drop-shadow-md">
            Penginapan Cahaya berdiri dengan visi memberikan tempat istirahat yang nyaman dan aman untuk setiap pengunjung. Kami mengutamakan keramahan dan kenyamanan agar Anda merasa seperti di rumah sendiri.
        </p>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-white rounded-t-3xl p-8 max-w-7xl mx-auto mt-20 shadow-lg mb-20 px-6 md:px-0">
        <h2 class="text-3xl font-bold mb-8 text-indigo-900 text-center">Kontak Kami</h2>
        <form action="#" method="POST" class="max-w-2xl mx-auto space-y-6" role="form" aria-label="Formulir kontak">
            <label for="name" class="block text-indigo-900 font-semibold mb-1">Nama Anda</label>
            <input id="name" type="text" name="name" placeholder="Nama Anda" required
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            
            <label for="email" class="block text-indigo-900 font-semibold mb-1">Email Anda</label>
            <input id="email" type="email" name="email" placeholder="Email Anda" required
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            
            <label for="message" class="block text-indigo-900 font-semibold mb-1">Pesan Anda</label>
            <textarea id="message" name="message" rows="5" placeholder="Pesan Anda" required
                class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-400"></textarea>
            
            <button type="submit"
                class="bg-yellow-400 text-indigo-900 font-semibold px-8 py-3 rounded hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-400 transition w-full">
                Kirim Pesan
            </button>
        </form>
    </section>

    <footer class="bg-indigo-900 text-white p-6 text-center text-sm">
        &copy; {{ date('Y') }} Penginapan Cahaya. Semua hak cipta dilindungi.
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
