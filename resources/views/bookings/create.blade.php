@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
        <div class="p-6 sm:p-8">
            <h2 class="text-2xl font-bold text-white mb-6">Form Pemesanan Kamar</h2>
            
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="room_id" class="block text-sm font-medium text-gray-300 mb-1">Pilih Kamar</label>
                        <select id="room_id" name="room_id" required
                                class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition">
                            <option value="">-- Pilih Kamar --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->price }}">
                                    {{ $room->name }} - Rp{{ number_format($room->price, 0, ',', '.') }}/malam (Kapasitas: {{ $room->capacity }} orang)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="check_in" class="block text-sm font-medium text-gray-300 mb-1">Tanggal Check-in</label>
                        <input id="check_in" name="check_in" type="date" required min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition">
                    </div>

                    <div>
                        <label for="check_out" class="block text-sm font-medium text-gray-300 mb-1">Tanggal Check-out</label>
                        <input id="check_out" name="check_out" type="date" required
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition">
                    </div>

                    <div>
                        <label for="total_guests" class="block text-sm font-medium text-gray-300 mb-1">Jumlah Tamu</label>
                        <input id="total_guests" name="total_guests" type="number" min="1" required
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Total Harga</label>
                        <div id="total_price_display" class="px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                            Rp0
                        </div>
                        <input type="hidden" id="total_price" name="total_price" value="0">
                    </div>

                    <div class="md:col-span-2">
                        <label for="special_requests" class="block text-sm font-medium text-gray-300 mb-1">Permintaan Khusus (Opsional)</label>
                        <textarea id="special_requests" name="special_requests" rows="3"
                                  class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-white placeholder-gray-400 transition"></textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400 transition">
                        Pesan Kamar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const totalPriceInput = document.getElementById('total_price');

    function calculateTotalPrice() {
        const roomId = roomSelect.value;
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;

        if (roomId && checkIn && checkOut) {
            const roomPrice = parseFloat(roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price'));
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const totalPrice = roomPrice * nights;

            totalPriceDisplay.textContent = 'Rp' + totalPrice.toLocaleString('id-ID');
            totalPriceInput.value = totalPrice;
        } else {
            totalPriceDisplay.textContent = 'Rp0';
            totalPriceInput.value = '0';
        }
    }

    roomSelect.addEventListener('change', calculateTotalPrice);
    checkInInput.addEventListener('change', calculateTotalPrice);
    checkOutInput.addEventListener('change', calculateTotalPrice);
});
</script>
@endsection