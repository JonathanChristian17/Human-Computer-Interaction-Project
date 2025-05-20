@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

<style>
    /* Date picker customization */
    .flatpickr-day.flatpickr-disabled {
        color: #ef4444 !important;
        text-decoration: line-through;
        background-color: rgba(239, 68, 68, 0.1) !important;
    }

    .flatpickr-day.flatpickr-disabled:hover {
        background-color: rgba(239, 68, 68, 0.2) !important;
    }

    .flatpickr-calendar {
        background: #1f2937;
        border-color: #374151;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .flatpickr-months .flatpickr-month {
        color: #fff;
        fill: #fff;
    }

    .flatpickr-weekdays {
        color: #9ca3af;
    }

    .flatpickr-day {
        color: #fff;
    }

    .flatpickr-day.selected {
        background: #f59e0b !important;
        border-color: #f59e0b !important;
    }

    .flatpickr-day:hover {
        background: #374151;
    }

    /* Unavailable dates styling */
    .unavailable-date {
        color: #ef4444 !important;
        text-decoration: line-through;
        pointer-events: none;
        opacity: 0.7;
        background-color: rgba(239, 68, 68, 0.1);
    }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/80 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-700/60">
        <div class="p-8 sm:p-10">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-amber-400 mb-2 tracking-wide drop-shadow-md">Form Pemesanan Kamar</h2>
                <p class="text-gray-400">Silakan lengkapi data pemesanan Anda</p>
            </div>
            
            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-8" id="bookingForm">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                        <div class="font-medium text-red-400">Whoops! Ada beberapa masalah dengan input Anda.</div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                        <div class="font-medium text-red-400">Error!</div>
                        <div class="text-sm text-red-300">{{ session('error') }}</div>
                    </div>
                @endif

                <!-- Debug info in development -->
                @if(config('app.debug'))
                    <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40 mb-6">
                        <h3 class="text-lg font-medium text-white mb-2">Debug Info</h3>
                        <div class="text-sm text-gray-400">
                            <p>User ID: {{ auth()->id() }}</p>
                            <p>Route exists: {{ Route::has('bookings.store') ? 'Yes' : 'No' }}</p>
                            <p>CSRF Token: {{ csrf_token() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Kamar Yang Dipilih -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                        Kamar Yang Dipilih
                    </h3>
                    
                    <div id="selected-rooms-container" class="space-y-4">
                        <!-- Kamar akan ditampilkan di sini -->
                    </div>

                    <div class="mt-4 p-4 bg-amber-500/10 rounded-lg border border-amber-500/20">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-amber-400">Informasi Penting</h4>
                                <ul class="mt-2 text-sm text-gray-300 list-disc list-inside space-y-1">
                                    <li>Check-in mulai pukul 14:00 WIB</li>
                                    <li>Check-out maksimal pukul 12:00 WIB</li>
                                    <li>Tamu wajib menunjukkan KTP/Paspor saat check-in</li>
                                    <li>Deposit Rp300.000 per kamar (dikembalikan saat check-out)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

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
                        <div class="relative">
                            <label for="check_in_date" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Tanggal Check-in
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input id="check_in_date" name="check_in_date" type="text" required readonly
                                       class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm cursor-pointer"
                                       value="{{ old('check_in_date') }}" placeholder="Pilih tanggal check-in"
                                       autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <label for="check_out_date" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                Tanggal Check-out
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input id="check_out_date" name="check_out_date" type="text" required readonly
                                       class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm cursor-pointer"
                                       value="{{ old('check_out_date') }}" placeholder="Pilih tanggal check-out"
                                       autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ketentuan dan Kebijakan -->
                    <div class="mt-6 p-4 bg-gray-700/30 rounded-lg border border-gray-600/30">
                        <h4 class="text-sm font-medium text-white mb-3">Ketentuan dan Kebijakan:</h4>
                        <ul class="text-sm text-gray-300 list-disc list-inside space-y-2">
                            <li>Pembayaran minimal 50% dari total harga untuk mengamankan pemesanan</li>
                            <li>Pembatalan 7 hari sebelum check-in: pengembalian 100%</li>
                            <li>Pembatalan 3-6 hari sebelum check-in: pengembalian 50%</li>
                            <li>Pembatalan kurang dari 3 hari: tidak ada pengembalian</li>
                            <li>Early check-in dan late check-out tersedia dengan biaya tambahan</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 3: Permintaan Khusus -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Permintaan Khusus
                    </h3>
                    
                    <div>
                        <textarea id="special_requests" name="special_requests" rows="3"
                                  class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-amber-400 focus:ring-2 focus:border-transparent transition shadow-sm"
                                  placeholder="Contoh: Kamar lantai atas, tempat tidur tambahan, dll">{{ old('special_requests') }}</textarea>
                        <p class="mt-2 text-sm text-gray-400">Kami akan berusaha memenuhi permintaan khusus Anda sesuai ketersediaan.</p>
                    </div>
                </div>

                <!-- Summary dan Tombol Submit -->
                <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/40">
                    <div class="flex flex-col md:flex-row justify-between items-start">
                        <div class="w-full md:w-2/3 mb-6 md:mb-0">
                            <h3 class="text-xl font-semibold text-white mb-4">Ringkasan Pemesanan</h3>
                            <div id="booking_summary" class="space-y-4">
                                <!-- Ringkasan akan diisi oleh JavaScript -->
                            </div>
                        </div>
                        
                        <div class="w-full md:w-1/3 md:pl-6">
                            <div class="bg-gray-700/30 rounded-lg p-4 border border-gray-600/30">
                                <h4 class="text-lg font-semibold text-white mb-3">Total Biaya</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-400">Total:</span>
                                        <span class="text-2xl font-semibold text-amber-400" id="total_price">Rp0</span>
                                    </div>
                                    <div class="text-sm text-gray-400 mt-2 pt-2 border-t border-gray-600/30">
                                        * Pembayaran akan dilakukan di resepsionis
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-semibold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Lanjutkan ke Pembayaran</span>
                            <svg id="loadingIcon" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Hidden input untuk menyimpan data kamar -->
                <input type="hidden" name="selected_rooms" id="selected_rooms_input">
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get user ID from meta tag
    const userId = document.querySelector('meta[name="user-id"]').content;
    if (!userId) {
        console.error('User ID not found');
        window.location.href = '/login';
        return;
    }

    // Use user-specific storage key
    const cartStorageKey = `selectedRooms_${userId}`;
    const selectedRooms = JSON.parse(sessionStorage.getItem(cartStorageKey) || '[]');
    const selectedRoomsContainer = document.getElementById('selected-rooms-container');
    const bookingSummary = document.getElementById('booking_summary');
    const selectedRoomsInput = document.getElementById('selected_rooms_input');
    const form = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingIcon = document.getElementById('loadingIcon');

    // Initialize date pickers
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');

    // Common configuration for both date pickers
    const commonConfig = {
        dateFormat: "Y-m-d",
        minDate: "today",
        theme: "dark",
        disableMobile: "true",
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
            },
            months: {
                shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            }
        }
    };

    // Initialize check-in date picker
    const checkInPicker = flatpickr(checkInInput, {
        ...commonConfig,
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutPicker.set('minDate', nextDay);
                updateSummary();
            }
        }
    });

    // Initialize check-out date picker
    const checkOutPicker = flatpickr(checkOutInput, {
        ...commonConfig,
        minDate: new Date().fp_incr(1),
        onChange: function() {
            updateSummary();
        }
    });

    // Function to update booked dates
    async function updateBookedDates(roomId) {
        try {
            const response = await fetch(`/get-booked-dates/${roomId}`);
            const dates = await response.json();
            
            // Update both date pickers with disabled dates
            const disabledDates = dates.map(date => new Date(date));
            checkInPicker.set('disable', disabledDates);
            checkOutPicker.set('disable', disabledDates);
        } catch (error) {
            console.error('Error fetching booked dates:', error);
        }
    }

    // Function to update selected rooms display
    function updateSelectedRooms() {
        if (!selectedRooms || selectedRooms.length === 0) {
            selectedRoomsContainer.innerHTML = '<p class="text-gray-400 text-center">Tidak ada kamar yang dipilih</p>';
            selectedRoomsInput.value = '[]';
            return;
        }

        selectedRoomsContainer.innerHTML = '';
        selectedRooms.forEach(room => {
            const roomElement = document.createElement('div');
            roomElement.className = 'flex items-center justify-between p-4 bg-gray-700/30 rounded-lg border border-gray-600/30';
            roomElement.setAttribute('data-room-id', room.id);
            roomElement.innerHTML = `
                <div class="flex-1">
                    <h4 class="text-white font-medium">${room.name}</h4>
                    <p class="text-sm text-gray-400">${room.capacity} orang</p>
                    <p class="text-amber-400 font-semibold">Rp${parseInt(room.price_per_night).toLocaleString('id-ID')}/malam</p>
                </div>
            `;
            selectedRoomsContainer.appendChild(roomElement);
            updateBookedDates(room.id);
        });

        // Update hidden input with selected rooms data
        selectedRoomsInput.value = JSON.stringify(selectedRooms);
    }

    // Function to update booking summary
    function updateSummary() {
        if (!checkInInput.value || !checkOutInput.value || !selectedRooms || selectedRooms.length === 0) {
            bookingSummary.innerHTML = '<p class="text-gray-400">Silakan pilih tanggal check-in dan check-out</p>';
            document.getElementById('total_price').textContent = 'Rp0';
            return;
        }

        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

        let subtotal = 0;
        let summaryHTML = '';

        selectedRooms.forEach(room => {
            const roomTotal = parseInt(room.price_per_night) * nights;
            subtotal += roomTotal;

            summaryHTML += `
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h5 class="text-white font-medium">${room.name}</h5>
                        <p class="text-sm text-gray-400">${nights} malam × Rp${parseInt(room.price_per_night).toLocaleString('id-ID')}</p>
                    </div>
                    <span class="text-white">Rp${roomTotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        });

        bookingSummary.innerHTML = summaryHTML;

        const total = subtotal;

        document.getElementById('total_price').textContent = `Rp${total.toLocaleString('id-ID')}`;
    }

    // Form submission handling
    form.addEventListener('submit', function(e) {
        // Verify user is still logged in
        const currentUserId = document.querySelector('meta[name="user-id"]').content;
        if (!currentUserId || currentUserId !== userId) {
            e.preventDefault();
            alert('Sesi Anda telah berakhir. Silakan login kembali.');
            window.location.href = '/login';
            return;
        }

        if (!selectedRooms || selectedRooms.length === 0) {
            e.preventDefault();
            alert('Silakan pilih setidaknya satu kamar untuk melanjutkan pemesanan.');
            return;
        }

        if (!checkInInput.value || !checkOutInput.value) {
            e.preventDefault();
            alert('Silakan pilih tanggal check-in dan check-out.');
            return;
        }

        submitBtn.disabled = true;
        submitText.textContent = 'Memproses...';
        loadingIcon.classList.remove('hidden');
    });

    // Initialize the form
    updateSelectedRooms();
    updateSummary();
});
</script>
@endpush

@endsection