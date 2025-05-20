@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Promo & Penawaran Spesial</h2>
            <div class="w-20 h-1 bg-amber-400 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Early Bird -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-amber-400 to-yellow-300 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Early Bird</h3>
                        <span class="bg-amber-500/10 text-amber-400 px-3 py-1 rounded-full text-sm font-medium">Hemat 25%</span>
                    </div>
                    <p class="text-gray-400 mb-4">Dapatkan diskon 25% untuk pemesanan minimal 30 hari sebelum check-in.</p>
                    <div class="text-sm text-gray-500">
                        <p>* Syarat & ketentuan berlaku</p>
                        <p>* Periode booking: Sekarang - 31 Des 2024</p>
                    </div>
                </div>
            </div>

            <!-- Long Stay -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-emerald-400 to-teal-300 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Long Stay</h3>
                        <span class="bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full text-sm font-medium">Hemat 30%</span>
                    </div>
                    <p class="text-gray-400 mb-4">Menginap lebih lama, hemat lebih banyak. Diskon 30% untuk minimum 7 malam.</p>
                    <div class="text-sm text-gray-500">
                        <p>* Minimum 7 malam</p>
                        <p>* Termasuk sarapan untuk 2 orang</p>
                    </div>
                </div>
            </div>

            <!-- Weekend Getaway -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-indigo-300 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Weekend Getaway</h3>
                        <span class="bg-purple-500/10 text-purple-400 px-3 py-1 rounded-full text-sm font-medium">Bonus Makan Malam</span>
                    </div>
                    <p class="text-gray-400 mb-4">Paket menginap akhir pekan dengan bonus makan malam romantis di tepi danau.</p>
                    <div class="text-sm text-gray-500">
                        <p>* Berlaku untuk Jumat - Minggu</p>
                        <p>* Termasuk makan malam untuk 2 orang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 