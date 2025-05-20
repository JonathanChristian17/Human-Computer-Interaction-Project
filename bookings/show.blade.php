@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
        <div class="p-6 sm:p-8">
            <!-- Header with Booking Status -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">Detail Pemesanan</h2>
                    <p class="text-gray-400">ID Pemesanan: #{{ $booking->id }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    @if($booking->status === 'confirmed') bg-green-500/20 text-green-400
                    @elseif($booking->status === 'cancelled') bg-red-500/20 text-red-400
                    @else bg-amber-500/20 text-amber-400 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 rounded-lg">
                <p class="text-green-400">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Booking Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Guest Information -->
                <div>
                    <h3 class="text-lg font-medium text-white mb-2">Informasi Tamu</h3>
                    <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nama:</span>
                            <span class="text-white">{{ $booking->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Email:</span>
                            <span class="text-white">{{ $booking->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Telepon:</span>
                            <span class="text-white">{{ $booking->phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">No. ID:</span>
                            <span class="text-white">{{ $booking->id_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div>
                    <h3 class="text-lg font-medium text-white mb-2">Detail Pemesanan</h3>
                    <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Check-in:</span>
                            <span class="text-white">{{ $booking->check_in_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Check-out:</span>
                            <span class="text-white">{{ $booking->check_out_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jumlah Malam:</span>
                            <span class="text-white">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-white mb-2">Kamar yang Dipesan</h3>
                <div class="bg-gray-700/50 rounded-lg border border-gray-600/50 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-600">
                        <thead class="bg-gray-800/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Kamar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Harga per Malam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @foreach($booking->rooms as $room)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $room->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $room->type }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-white">Rp{{ number_format($room->pivot->price_per_night, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-white">Rp{{ number_format($room->pivot->subtotal, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-800/50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-sm font-medium text-gray-400 text-right">Total:</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-amber-400">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-white mb-2">Informasi Biaya</h3>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Total Biaya:</span>
                        <span class="text-amber-400 font-medium">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-sm text-gray-400 mt-2 pt-2 border-t border-gray-700">
                        * Pembayaran akan dilakukan di resepsionis
                    </div>
                </div>
            </div>

            <!-- Special Requests -->
            @if($booking->special_requests)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-white mb-2">Permintaan Khusus</h3>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600/50">
                    <p class="text-gray-400">{{ $booking->special_requests }}</p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('bookings.riwayat') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600/50 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-700/50">
                    ← Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we need to clear the cart
    @if(session('clearCart'))
    const userId = document.querySelector('meta[name="user-id"]').content;
    if (userId) {
        const cartStorageKey = `selectedRooms_${userId}`;
        sessionStorage.removeItem(cartStorageKey);
        console.log('Cart cleared after successful booking');
    }
    @endif
});
</script>
@endpush

@endsection