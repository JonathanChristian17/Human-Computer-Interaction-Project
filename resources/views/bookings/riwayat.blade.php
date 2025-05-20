@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 mt-10">
    <div class="bg-gray-900/60 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50 overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Pemesanan
            </h2>

            @if($riwayat->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-400 text-lg">Belum ada riwayat pemesanan.</p>
                    <a href="{{ route('kamar.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">
                        Pesan Kamar Sekarang
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($riwayat as $booking)
                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700/50 hover:border-amber-500/50 transition-all duration-300">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-amber-400">Booking #{{ $booking->id }}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($booking->status === 'confirmed') bg-green-500/20 text-green-400
                                            @elseif($booking->status === 'cancelled') bg-red-500/20 text-red-400
                                            @else bg-amber-500/20 text-amber-400 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                        <div class="text-gray-400">
                                            Check-in: <span class="text-white">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
                                        </div>
                                        <div class="text-gray-400">
                                            Check-out: <span class="text-white">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
                                        </div>
                                        <div class="text-gray-400">
                                            Total: <span class="text-white">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-gray-400">
                                            Status Pembayaran: 
                                            <span class="@if($booking->payment_status === 'paid') text-green-400 @else text-amber-400 @endif">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button 
                                    onclick="showBookingDetail('{{ $booking->id }}')"
                                    class="px-4 py-2 bg-amber-500/10 text-amber-400 rounded-lg hover:bg-amber-500/20 transition-colors flex items-center gap-2">
                                    <span>Detail</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals Container (Outside main content) -->
@if(!$riwayat->isEmpty())
    @foreach($riwayat as $booking)
        <div id="modal-{{ $booking->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-gray-900 rounded-xl shadow-2xl w-full max-w-4xl border border-gray-700/50 relative max-h-[90vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-white">Detail Pemesanan #{{ $booking->id }}</h3>
                            <button onclick="hideBookingDetail('{{ $booking->id }}')" class="text-gray-400 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-4 sm:p-6 space-y-6 overflow-y-auto flex-1">
                        <!-- Guest Information -->
                        <div>
                            <h4 class="text-lg font-medium text-white mb-3">Informasi Tamu</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div class="text-gray-400">Nama: <span class="text-white">{{ $booking->full_name }}</span></div>
                                <div class="text-gray-400">Email: <span class="text-white">{{ $booking->email }}</span></div>
                                <div class="text-gray-400">Telepon: <span class="text-white">{{ $booking->phone }}</span></div>
                                <div class="text-gray-400">No. ID: <span class="text-white">{{ $booking->id_number }}</span></div>
                            </div>
                        </div>

                        <!-- Rooms Booked -->
                        <div>
                            <h4 class="text-lg font-medium text-white mb-3">Kamar yang Dipesan</h4>
                            <div class="space-y-3">
                                @foreach($booking->rooms as $room)
                                    <div class="bg-gray-800/50 p-3 rounded-lg border border-gray-700/50">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                            <div>
                                                <h5 class="text-white font-medium">{{ $room->name }}</h5>
                                                <p class="text-sm text-gray-400">{{ $room->capacity }} orang</p>
                                            </div>
                                            <div class="text-left sm:text-right">
                                                <p class="text-amber-400 font-medium">Rp{{ number_format($room->pivot->price_per_night, 0, ',', '.') }}/malam</p>
                                                <p class="text-sm text-gray-400">Subtotal: Rp{{ number_format($room->pivot->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div>
                            <h4 class="text-lg font-medium text-white mb-3">Detail Pembayaran</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700/50">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Subtotal:</span>
                                        <span class="text-white">Rp{{ number_format($booking->total_price - $booking->tax - $booking->deposit, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Pajak (10%):</span>
                                        <span class="text-white">Rp{{ number_format($booking->tax, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Deposit:</span>
                                        <span class="text-white">Rp{{ number_format($booking->deposit, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="pt-2 border-t border-gray-700">
                                        <div class="flex justify-between font-medium">
                                            <span class="text-white">Total:</span>
                                            <span class="text-amber-400">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($booking->special_requests)
                            <div>
                                <h4 class="text-lg font-medium text-white mb-3">Permintaan Khusus</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700/50">
                                    <p class="text-gray-400">{{ $booking->special_requests }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 sm:p-6 border-t border-gray-700">
                        @if($booking->payment_status === 'pending')
                            <button class="w-full bg-amber-500 text-white rounded-lg py-2 hover:bg-amber-600 transition-colors">
                                Lanjutkan Pembayaran
                            </button>
                        @else
                            <button onclick="hideBookingDetail('{{ $booking->id }}')" class="w-full bg-gray-700 text-white rounded-lg py-2 hover:bg-gray-600 transition-colors">
                                Tutup
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script>
function showBookingDetail(bookingId) {
    const modal = document.getElementById(`modal-${bookingId}`);
    modal.classList.remove('hidden');
}

function hideBookingDetail(bookingId) {
    const modal = document.getElementById(`modal-${bookingId}`);
    modal.classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('fixed')) {
        const bookingId = event.target.id.replace('modal-', '');
        hideBookingDetail(bookingId);
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const visibleModal = document.querySelector('[id^="modal-"]:not(.hidden)');
        if (visibleModal) {
            const bookingId = visibleModal.id.replace('modal-', '');
            hideBookingDetail(bookingId);
        }
    }
});
</script>
@endpush

@endsection
