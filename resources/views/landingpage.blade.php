@extends('layouts.app')

@section('content')
    <!-- Hero Section with Parallax Effect -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background with parallax effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/95 via-gray-800/90 to-gray-900/95 backdrop-blur-sm">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-30 transform scale-110 motion-safe:group-hover:scale-100 transition-transform duration-1000"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-10 text-center px-6 md:px-12 max-w-6xl">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-300">Penginapan Cahaya</span><br>
                <span class="text-xl md:text-2xl font-light text-gray-300">Ketemu di Samosir</span>
            </h1>
            
            <p class="mt-6 max-w-2xl mx-auto text-gray-300 text-lg md:text-xl leading-relaxed">
                Pengalaman menginap premium di tepian Danau Toba dengan sentuhan modern dan kearifan lokal.
            </p>
            
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('kamar.index') }}" 
                   class="px-8 py-3 bg-gradient-to-r from-amber-500 to-yellow-400 text-gray-900 font-medium rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                   aria-label="Lihat Kamar Tersedia">
                   Pesan Sekarang
                </a>
                <a href="#contact" 
                   class="px-8 py-3 border-2 border-amber-400 text-amber-400 font-medium rounded-full hover:bg-amber-400/10 transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-gray-900"
                   aria-label="Hubungi Kami">
                   Hubungi Kami
                </a>
            </div>
        </div>
         <!-- Wave SVG Bottom -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden transform rotate-180">
        <svg class="relative block w-full h-20" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" 
                  opacity=".25" 
                  class="fill-current text-gray-900"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" 
                  opacity=".5" 
                  class="fill-current text-gray-900"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" 
                  class="fill-current text-gray-900"></path>
        </svg>

        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="py-20 bg-gray-900">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-medium text-amber-400 bg-amber-400/10 rounded-full mb-4">Pilihan Kamar</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Pengalaman Menginap Eksklusif</h2>
                <div class="w-20 h-1 bg-amber-400 mx-auto"></div>
            </div>
            
            @if($rooms->isEmpty())
                <div class="text-center py-12 bg-gray-800/50 rounded-xl backdrop-blur-sm">
                    <svg class="w-12 h-12 mx-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <p class="mt-4 text-gray-400">Belum ada kamar yang tersedia saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($rooms->take(3) as $room)
                        <article 
                            class="group relative overflow-hidden rounded-xl bg-gray-800 shadow-xl transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
                            aria-labelledby="room-{{ $room->id }}-name"
                        >
                            <div class="relative h-64 overflow-hidden">
                                <img 
                                     src="{{ $room->image ? asset('storage/' . $room->image) : 'https://source.unsplash.com/random/600x400/?hotel-room,' . $loop->index }}" 
                                    alt="{{ $room->name }}" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>
                                <span class="absolute top-4 right-4 px-3 py-1 text-xs font-semibold text-white bg-amber-500 rounded-full">
                                    {{ $room->capacity }} orang
                                </span>
                            </div>
                            
                            <div class="p-6">
                                <h3 id="room-{{ $room->id }}-name" class="text-xl font-bold text-white mb-2">{{ $room->name }}</h3>
                                <p class="text-gray-400 mb-4 line-clamp-2">{{ $room->description }}</p>
                                
                                <div class="flex items-center justify-between mt-6">
                                    <span class="text-2xl font-bold text-amber-400">Rp{{ number_format($room->price_per_night, 0, ',', '.') }}</span>
                                    <a href="{{ route('kamar.index') }}" class="text-sm font-medium text-white hover:text-amber-400 transition-colors flex items-center">
                                    Pesan Sekarang
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                    </a>

                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-800/50">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-medium text-amber-400 bg-amber-400/10 rounded-full mb-4">Fasilitas</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Apa Yang Kami Tawarkan</h2>
                <div class="w-20 h-1 bg-amber-400 mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl hover:bg-gray-700/50 transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-400/10 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Lokasi Strategis</h3>
                    <p class="text-gray-400">Tepat di tepian Danau Toba dengan pemandangan langsung ke danau dan pegunungan.</p>
                </div>
                
                <div class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl hover:bg-gray-700/50 transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-400/10 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Fasilitas Lengkap</h3>
                    <p class="text-gray-400">AC, WiFi gratis, kolam renang, restoran, dan layanan kamar 24 jam.</p>
                </div>
                
                <div class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl hover:bg-gray-700/50 transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-400/10 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Harga Kompetitif</h3>
                    <p class="text-gray-400">Kualitas bintang 4 dengan harga terjangkau dan promo menarik setiap bulan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-gray-900 to-gray-800">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2 relative">
                    <div class="relative rounded-2xl overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                            alt="Penginapan Cahaya" 
                            class="w-full h-auto object-cover rounded-2xl shadow-2xl"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/70 via-transparent to-transparent rounded-2xl"></div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-amber-400/90 backdrop-blur-sm p-4 rounded-lg shadow-xl w-2/3">
                        <h4 class="text-gray-900 font-bold mb-1">Pengalaman 10 Tahun</h4>
                        <p class="text-gray-800 text-sm">Melayani ribuan tamu dengan kepuasan terbaik</p>
                    </div>
                </div>
                
                <div class="lg:w-1/2">
                    <span class="inline-block px-3 py-1 text-sm font-medium text-amber-400 bg-amber-400/10 rounded-full mb-4">Tentang Kami</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Penginapan Cahaya Samosir</h2>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        Didirikan pada tahun 2013, Penginapan Cahaya telah menjadi pilihan utama wisatawan yang mengunjungi Danau Toba. Kami menggabungkan arsitektur tradisional Batak dengan fasilitas modern untuk memberikan pengalaman menginap yang unik dan nyaman.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="text-white font-medium mb-1">Lokasi Eksklusif</h4>
                                <p class="text-gray-400 text-sm">Tepat di tepi Danau Toba dengan akses mudah ke semua objek wisata utama.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="text-white font-medium mb-1">Pelayanan Ramah</h4>
                                <p class="text-gray-400 text-sm">Staf kami yang profesional dan ramah siap melayani kebutuhan Anda 24 jam.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="text-white font-medium mb-1">Makanan Tradisional</h4>
                                <p class="text-gray-400 text-sm">Nikmati kuliner khas Batak yang otentik dengan bahan-bahan lokal segar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-900/80 backdrop-blur-sm">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-medium text-amber-400 bg-amber-400/10 rounded-full mb-4">Testimoni</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Apa Kata Tamu Kami</h2>
                <div class="w-20 h-1 bg-amber-400 mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div>
                            <h4 class="text-white font-medium">Sarah Wijaya</h4>
                            <div class="flex mt-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-400 italic">"Penginapan yang sangat nyaman dengan pemandangan danau yang menakjubkan. Pelayanan staff sangat ramah dan membantu. Pasti akan kembali lagi!"</p>
                </div>
                
                <div class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Budi" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div>
                            <h4 class="text-white font-medium">Budi Santoso</h4>
                            <div class="flex mt-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-400 italic">"Kamar bersih dan luas, sarapan enak dengan pilihan makanan lokal. Sangat cocok untuk keluarga. Fasilitas lengkap dan modern."</p>
                </div>
                
                <div class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Dewi" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div>
                            <h4 class="text-white font-medium">Dewi Anggraeni</h4>
                            <div class="flex mt-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-400 italic">"Sempurna untuk bulan madu! Kamar suite dengan pemandangan danau yang romantis. Pelayanan sangat personal dan makanan di restoran luar biasa."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-900">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row gap-12">
                <div class="lg:w-1/2">
                    <div class="text-center lg:text-left mb-12 lg:mb-0">
                        <span class="inline-block px-3 py-1 text-sm font-medium text-amber-400 bg-amber-400/10 rounded-full mb-4">Hubungi Kami</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Butuh Bantuan?</h2>
                        <p class="text-gray-400 mb-8 max-w-lg">
                            Tim kami siap membantu Anda dengan pertanyaan, reservasi, atau kebutuhan khusus selama menginap. Hubungi kami melalui formulir atau informasi kontak berikut.
                        </p>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-amber-400/10 rounded-lg flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-medium mb-1">Telepon</h4>
                                    <p class="text-gray-400">+62 812 3456 7890</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-amber-400/10 rounded-lg flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-medium mb-1">Email</h4>
                                    <p class="text-gray-400">info@penginapancahaya.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-amber-400/10 rounded-lg flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-medium mb-1">Lokasi</h4>
                                    <p class="text-gray-400">Jl. Danau Toba No. 123, Samosir, Sumatera Utara</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2">
                    <form action="#" method="POST" class="bg-gray-800/70 backdrop-blur-sm p-8 rounded-xl shadow-xl">
                        <div class="mb-6">
                            <label for="name" class="block text-white font-medium mb-2">Nama Lengkap</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Nama Anda" 
                                required
                                class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                            >
                        </div>
                        
                        <div class="mb-6">
                            <label for="email" class="block text-white font-medium mb-2">Alamat Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="email@contoh.com" 
                                required
                                class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                            >
                        </div>
                        
                        <div class="mb-6">
                            <label for="subject" class="block text-white font-medium mb-2">Subjek</label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                placeholder="Subjek pesan Anda" 
                                required
                                class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                            >
                        </div>
                        
                        <div class="mb-8">
                            <label for="message" class="block text-white font-medium mb-2">Pesan</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5" 
                                placeholder="Tulis pesan Anda di sini..." 
                                required
                                class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"
                            ></textarea>
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-amber-500 to-yellow-400 text-gray-900 font-semibold px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-gray-800"
                        >
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <div class="h-96 w-full bg-gray-900 relative overflow-hidden">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d31885.645507602447!2d98.6645881!3d2.6017065!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3031c5db4e76ffd5%3A0x3f07d452aaab4f1e!2sHotel%20Cahaya!5e0!3m2!1sid!2sid!4v1747334081541!5m2!1sid!2sid" width="1600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"
            class="absolute inset-0"
            aria-label="Peta Lokasi Penginapan Cahaya"
        ></iframe>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent pointer-events-none"></div>
    </div>
    
@endsection