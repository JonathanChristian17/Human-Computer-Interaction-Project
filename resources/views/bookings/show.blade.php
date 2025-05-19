@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
        <div class="p-6 sm:p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">Detail Pemesanan</h2>
                    <p class="text-gray-400">ID: {{ $booking->id }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    @if($booking->status === 'confirmed') bg-green-500/20 text-green-400
                    @elseif($booking->status === 'cancelled') bg-red-500/20 text-red-400
                    @else bg-amber-500/20 text-amber-400 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-medium text-white mb-2">Informasi Kamar</h3>
                    <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50">
                        <h4 class="font-medium text-white">{{ $booking->room->name }}</h4>
                        <p class="text-gray-400">{{ $booking->room->description }}</p>
                        <p class="text-gray-400">Kapasitas: {{ $booking->room->capacity }} orang</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-white mb-2">Detail Pemesanan</h3>
                    <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Check-in:</span>
                            <span class="text-white">{{ $booking->check_in->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Check-out:</span>
                            <span class="text-white">{{ $booking->check_out->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jumlah Malam:</span>
                            <span class="text-white">{{ $booking->check_in->diffInDays($booking->check_out) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jumlah Tamu:</span>
                            <span class="text-white">{{ $booking->total_guests }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Harga:</span>
                            <span class="text-white">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($booking->special_requests)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-white mb-2">Permintaan Khusus</h3>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50">
                    <p class="text-white">{{ $booking->special_requests }}</p>
                </div>
            </div>
            @endif

            <div class="flex space-x-4">
                <a href="{{ route('rooms.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 transition">
                   Buat Pesanan Baru
                </a>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg font-semibold text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                   Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection