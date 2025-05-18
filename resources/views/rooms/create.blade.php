@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/80 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-700/60">
        <div class="p-8 sm:p-10">
            <h2 class="text-3xl font-extrabold text-amber-400 mb-8 tracking-wide drop-shadow-md">Pesan Kamar</h2>
            
            <form method="POST" action="{{ route('rooms.store') }}" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="room_id" class="block text-sm font-semibold text-gray-300 mb-2">Pilih Kamar</label>
                        <select id="room_id" name="room_id" required
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm">
                            <option value="" disabled selected>-- Pilih Kamar --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->name }} - Rp{{ number_format($room->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="guests" class="block text-sm font-semibold text-gray-300 mb-2">Jumlah Tamu</label>
                        <input id="guests" name="guests" type="number" min="1" required
                               class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                               placeholder="Masukkan jumlah tamu">
                    </div>

                    <div class="relative">
                        <label for="check_in" class="block text-sm font-semibold text-gray-300 mb-2">Check-in</label>
                        <input id="check_in" name="check_in" type="date" required
                               class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm appearance-none cursor-pointer"
                               placeholder="Pilih tanggal check-in" autocomplete="off" onkeydown="return false">
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-5 4h.01M4 20h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative">
                        <label for="check_out" class="block text-sm font-semibold text-gray-300 mb-2">Check-out</label>
                        <input id="check_out" name="check_out" type="date" required
                               class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm appearance-none cursor-pointer"
                               placeholder="Pilih tanggal check-out" autocomplete="off" onkeydown="return false">
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-5 4h.01M4 20h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full py-4 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-lg transition transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-amber-400 focus:ring-offset-2">
                        Pesan Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');

        // Set minimal tanggal check-in ke hari ini
        const today = new Date().toISOString().split('T')[0];
        checkIn.min = today;

        // Saat tanggal check-in berubah, atur minimal tanggal check-out
        checkIn.addEventListener('change', function () {
            if (checkIn.value) {
                const minCheckOut = new Date(checkIn.value);
                minCheckOut.setDate(minCheckOut.getDate() + 1);
                checkOut.min = minCheckOut.toISOString().split('T')[0];

                // Reset check-out jika tanggal tidak valid
                if (checkOut.value <= checkIn.value) {
                    checkOut.value = '';
                }
            } else {
                checkOut.min = today;
            }
        });

        // Atur minimal tanggal check-out saat load halaman
        if (checkIn.value) {
            const minCheckOut = new Date(checkIn.value);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            checkOut.min = minCheckOut.toISOString().split('T')[0];
        } else {
            checkOut.min = today;
        }
    });
</script>
@endsection
