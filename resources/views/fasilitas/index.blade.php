@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Fasilitas Hotel</h2>
            <div class="w-20 h-1 bg-amber-400 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Restaurant -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl">
                <div class="text-amber-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Restoran</h3>
                <p class="text-gray-400">Nikmati hidangan lokal dan internasional di restoran kami dengan pemandangan Danau Toba yang memukau.</p>
            </div>

            <!-- Swimming Pool -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl">
                <div class="text-amber-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Kolam Renang</h3>
                <p class="text-gray-400">Kolam renang infinity dengan pemandangan danau, sempurna untuk bersantai dan menikmati matahari terbenam.</p>
            </div>

            <!-- Spa -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl">
                <div class="text-amber-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Spa & Wellness</h3>
                <p class="text-gray-400">Manjakan diri Anda dengan perawatan spa tradisional dan modern di pusat kesehatan kami.</p>
            </div>

            <!-- Meeting Room -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl">
                <div class="text-amber-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Ruang Pertemuan</h3>
                <p class="text-gray-400">Ruang pertemuan modern dengan peralatan lengkap, cocok untuk seminar atau acara bisnis.</p>
            </div>

            <!-- Parking -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl">
                <div class="text-amber-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Parkir Gratis</h3>
                <p class="text-gray-400">Area parkir luas dan aman tersedia gratis untuk semua tamu hotel.</p>
            </div>

            <!-- WiFi -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl">
                <div class="text-amber-400 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">WiFi Gratis</h3>
                <p class="text-gray-400">Koneksi internet berkecepatan tinggi tersedia di seluruh area hotel.</p>
            </div>
        </div>
    </div>
</div>
@endsection 