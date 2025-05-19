@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/80 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-700/60">
        <div class="p-8 sm:p-10">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-amber-400 mb-2 tracking-wide drop-shadow-md">Form Pemesanan Kamar</h2>
                <p class="text-gray-400">Silakan lengkapi data pemesanan Anda</p>
            </div>
            
            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-8">
                @csrf

                <!-- Section 1: Data Pribadi Pemesan -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Data Pribadi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Nama Lengkap
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="full_name" name="full_name" type="text" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                   placeholder="Nama lengkap sesuai KTP" value="{{ old('full_name') }}">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Email
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="email" name="email" type="email" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                   placeholder="email@contoh.com" value="{{ old('email') }}">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Nomor HP/WhatsApp
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="phone" name="phone" type="tel" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                   placeholder="08123456789" value="{{ old('phone') }}">
                        </div>

                        <div>
                            <label for="id_number" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Nomor KTP/Paspor
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="id_number" name="id_number" type="text" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                   placeholder="Nomor identitas" value="{{ old('id_number') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Detail Pemesanan -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        Detail Pemesanan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="room_id" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Jenis Kamar
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="room_id" name="room_id" required
                                    class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm">
                                <option value="" disabled selected>-- Pilih Kamar --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" data-price="{{ $room->price }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} - Rp{{ number_format($room->price, 0, ',', '.') }}/malam
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="guests" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Jumlah Tamu
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="guests" name="guests" type="number" min="1" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                   placeholder="Jumlah tamu" value="{{ old('guests') }}">
                        </div>

                        <div class="relative">
                            <label for="check_in" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Tanggal Check-in
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="check_in" name="check_in" type="date" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm appearance-none cursor-pointer"
                                   placeholder="Pilih tanggal" value="{{ old('check_in') }}" autocomplete="off" onkeydown="return false">
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400 mt-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-5 4h.01M4 20h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <label for="check_out" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Tanggal Check-out
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="check_out" name="check_out" type="date" required
                                   class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm appearance-none cursor-pointer"
                                   placeholder="Pilih tanggal" value="{{ old('check_out') }}" autocomplete="off" onkeydown="return false">
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400 mt-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-5 4h.01M4 20h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Alamat Penagihan -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Alamat Penagihan
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="billing_address" class="block text-sm font-semibold text-gray-300 mb-2">Alamat Lengkap</label>
                            <textarea id="billing_address" name="billing_address" rows="3"
                                      class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                      placeholder="Jl. Contoh No. 123, Kota, Provinsi">{{ old('billing_address') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="billing_city" class="block text-sm font-semibold text-gray-300 mb-2">Kota</label>
                                <input id="billing_city" name="billing_city" type="text"
                                       class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                       placeholder="Nama kota" value="{{ old('billing_city') }}">
                            </div>

                            <div>
                                <label for="billing_province" class="block text-sm font-semibold text-gray-300 mb-2">Provinsi</label>
                                <input id="billing_province" name="billing_province" type="text"
                                       class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                       placeholder="Nama provinsi" value="{{ old('billing_province') }}">
                            </div>

                            <div>
                                <label for="billing_postal_code" class="block text-sm font-semibold text-gray-300 mb-2">Kode Pos</label>
                                <input id="billing_postal_code" name="billing_postal_code" type="text"
                                       class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                       placeholder="Kode pos" value="{{ old('billing_postal_code') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Informasi Tambahan -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Informasi Tambahan
                    </h3>
                    
                    <div>
                        <label for="special_requests" class="block text-sm font-semibold text-gray-300 mb-2">Permintaan Khusus</label>
                        <textarea id="special_requests" name="special_requests" rows="3"
                                  class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                  placeholder="Contoh: Kamar lantai atas, tempat tidur tambahan, dll">{{ old('special_requests') }}</textarea>
                    </div>
                </div>

                <!-- Summary dan Tombol Submit -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-6 md:mb-0">
                            <h3 class="text-xl font-semibold text-white mb-2">Ringkasan Pemesanan</h3>
                            <div id="booking_summary" class="text-gray-400">
                                <p>Silakan pilih kamar dan tanggal untuk melihat detail harga</p>
                            </div>
                        </div>
                        
                        <button type="submit"
                                class="w-full md:w-auto px-8 py-4 rounded-lg bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white font-semibold shadow-lg transition transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-amber-400 focus:ring-offset-2">
                            Lanjutkan ke Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elemen form
    const roomSelect = document.getElementById('room_id');
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const guestsInput = document.getElementById('guests');
    const summaryDiv = document.getElementById('booking_summary');

    // Set minimal tanggal check-in ke hari ini
    const today = new Date().toISOString().split('T')[0];
    checkInInput.min = today;

    // Fungsi untuk menghitung dan menampilkan ringkasan
    function updateBookingSummary() {
        const roomId = roomSelect.value;
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const guests = guestsInput.value;

        if (roomId && checkIn && checkOut) {
            const roomPrice = parseFloat(roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price'));
            const roomName = roomSelect.options[roomSelect.selectedIndex].text.split(' - ')[0];
            
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const totalPrice = roomPrice * nights;

            summaryDiv.innerHTML = `
                <div class="space-y-2">
                    <p class="flex justify-between">
                        <span>${roomName}</span>
                        <span>Rp${roomPrice.toLocaleString('id-ID')}/malam</span>
                    </p>
                    <p class="flex justify-between">
                        <span>${nights} Malam (${formatDate(checkIn)} - ${formatDate(checkOut)})</span>
                        <span>Rp${(roomPrice * nights).toLocaleString('id-ID')}</span>
                    </p>
                    <p class="flex justify-between">
                        <span>Jumlah Tamu:</span>
                        <span>${guests || '0'} Orang</span>
                    </p>
                    <div class="border-t border-gray-700 pt-2 mt-2">
                        <p class="flex justify-between font-semibold text-white">
                            <span>Total:</span>
                            <span>Rp${totalPrice.toLocaleString('id-ID')}</span>
                        </p>
                    </div>
                </div>
            `;
        } else {
            summaryDiv.innerHTML = '<p class="text-gray-400">Silakan pilih kamar dan tanggal untuk melihat detail harga</p>';
        }
    }

    // Fungsi untuk memformat tanggal
    function formatDate(dateString) {
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    // Event listeners
    roomSelect.addEventListener('change', updateBookingSummary);
    checkInInput.addEventListener('change', function() {
        if (checkInInput.value) {
            const minCheckOut = new Date(checkInInput.value);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            checkOutInput.min = minCheckOut.toISOString().split('T')[0];
            
            if (checkOutInput.value && new Date(checkOutInput.value) <= minCheckOut) {
                checkOutInput.value = '';
            }
        }
        updateBookingSummary();
    });
    checkOutInput.addEventListener('change', updateBookingSummary);
    guestsInput.addEventListener('input', updateBookingSummary);

    // Inisialisasi ringkasan
    updateBookingSummary();
});
</script>
@endsection