@extends('layouts.app')

@section('head')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Calendar Modal */
    .calendar-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }

    .calendar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .calendar-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 600px;
    }

    /* FullCalendar Customization */
    .fc {
        font-family: inherit;
        background: white;
        padding: 10px;
        border-radius: 8px;
    }

    .fc .fc-toolbar {
        margin-bottom: 1.5em;
    }

    .fc .fc-toolbar-title {
        font-size: 1.2em;
        font-weight: 600;
    }

    .fc .fc-button-primary {
        background-color: #f59e0b;
        border-color: #f59e0b;
        font-weight: 500;
        text-transform: capitalize;
        padding: 0.5em 0.85em;
    }

    .fc .fc-button-primary:hover {
        background-color: #d97706;
        border-color: #d97706;
    }

    .fc .fc-button-primary:disabled {
        background-color: #fcd34d;
        border-color: #fcd34d;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background-color: #fef3c7;
    }

    .fc .fc-highlight {
        background-color: #fde68a;
    }

    .fc .fc-day-past {
        background-color: #f3f4f6;
    }

    .fc .fc-day-disabled {
        background-color: #fee2e2;
        text-decoration: line-through;
        opacity: 0.7;
    }

    .fc .fc-day {
        cursor: pointer;
    }

    .fc .fc-day:hover {
        background-color: #f3f4f6;
    }

    .fc .fc-day.selected {
        background-color: #fef3c7;
    }

    /* Calendar Actions */
    .calendar-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .calendar-actions button {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel {
        background-color: #e5e7eb;
        color: #4b5563;
        border: none;
    }

    .btn-cancel:hover {
        background-color: #d1d5db;
    }

    .btn-apply {
        background-color: #f59e0b;
        color: white;
        border: none;
    }

    .btn-apply:hover {
        background-color: #d97706;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Room Booking Content -->
    <div x-data="{
        selectedRooms: [],
        totalPrice: 0,
        addRoom(room) {
            if (!this.selectedRooms.find(r => r.id === room.id)) {
                this.selectedRooms.push(room);
                this.calculateTotal();
                // Store selected rooms in localStorage
                localStorage.setItem('selectedRooms', JSON.stringify(this.selectedRooms));
            }
        },
        removeRoom(roomId) {
            this.selectedRooms = this.selectedRooms.filter(r => r.id !== roomId);
            this.calculateTotal();
            // Update localStorage
            localStorage.setItem('selectedRooms', JSON.stringify(this.selectedRooms));
        },
        calculateTotal() {
            this.totalPrice = this.selectedRooms.reduce((total, room) => total + room.price, 0);
        },
        isRoomSelected(roomId) {
            return this.selectedRooms.some(r => r.id === roomId);
        },
        checkAuthAndShowBooking(roomIds) {
            // Fungsi ini akan memanggil window.showBooking(roomIds) jika sudah login,
            // atau menampilkan SweetAlert jika belum login. Jangan gunakan interpolasi Blade di sini.
            if (window.isAuthenticated) {
                window.showBooking(roomIds);
            } else {
                showBrutalistSwalAlert({
                    title: 'Login Required',
                    message: 'Please login first to complete your booking',
                    type: 'info',
                    confirmText: 'Login Now',
                    cancelText: 'Cancel',
                    onConfirm: function() {
                        window.location.href = '/login';
                    }
                });
            }
        },
        init() {
            // Load selected rooms from localStorage on page load
            const stored = localStorage.getItem('selectedRooms');
            if (stored) {
                this.selectedRooms = JSON.parse(stored);
                this.calculateTotal();
            }
            // Handle pagination clicks
            document.addEventListener('click', (e) => {
                const paginationLink = e.target.closest('.pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    const url = new URL(paginationLink.href);
                    // Add current search parameters
                    const checkIn = window.selectedStartDate ? formatDateForSubmit(window.selectedStartDate) : document.getElementById('search_check_in').value;
                    const checkOut = window.selectedEndDate ? formatDateForSubmit(window.selectedEndDate) : document.getElementById('search_check_out').value;
                    const guests = document.getElementById('search_guests').value;
                    url.searchParams.set('check_in', checkIn);
                    url.searchParams.set('check_out', checkOut);
                    url.searchParams.set('guests', guests);
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const roomsContainer = doc.querySelector('#rooms-container');
                            if (roomsContainer) {
                                document.getElementById('rooms-container').innerHTML = roomsContainer.innerHTML;
                                window.history.pushState({}, '', url);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching page:', error);
                            showBrutalistSwalAlert({
                                type: 'error',
                                title: 'Error',
                                message: 'Failed to load the next page. Please try again.',
                                confirmText: 'OK',
                            });
                        });
                }
            });
        }
    }" x-init="init()">

        <!-- Top Booking Bar -->
        <div class="bg-white border-b fixed top-0 left-0 w-full z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center gap-4">
                    <!-- Tombol Back di kiri -->
                    <button onclick="handleBackClick(event)" class="back-button" style="position: static; margin-right: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Back</span>
                    </button>
                    <!-- Filter Booking (Check in, Checkout, Guests, Search) -->
                    <div class="flex-1 flex gap-4">
                    <!-- Check-in -->
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border cursor-pointer" onclick="openCalendar('check_in')">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <input type="text" 
                               id="search_check_in"
                               name="search_check_in"
                               class="bg-transparent border-none focus:outline-none text-sm cursor-pointer" 
                               value="{{ request('check_in') }}"
                               placeholder="Check in"
                               readonly>
                    </div>
                    
                    <!-- Check-out -->
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border cursor-pointer" onclick="openCalendar('check_out')">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <input type="text" 
                               id="search_check_out"
                               name="search_check_out"
                               class="bg-transparent border-none focus:outline-none text-sm cursor-pointer" 
                               value="{{ request('check_out') }}"
                               placeholder="Checkout"
                               readonly>
                    </div>
                    
                    <!-- Room & Guests -->
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border">
                        <i class="fas fa-user-friends text-gray-400"></i>
                        <select id="search_guests"
                                name="search_guests"
                                class="bg-transparent border-none focus:outline-none text-sm">
                            <option value="1-2" {{ request('guests') == '1-2' ? 'selected' : '' }}>1 Room, 2 guest</option>
                            <option value="2-4" {{ request('guests') == '2-4' ? 'selected' : '' }}>2 Rooms, 4 guests</option>
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <button onclick="searchRooms()" class="bg-[#FFA040] text-white px-6 py-2 rounded-lg hover:bg-[#FFB040] transition text-sm">
                        Search
                    </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-20">
            <h2 class="text-2xl font-bold mb-8">Reservation Cahaya Resort</h2>

            <!-- Room listings -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Left side - Room listings -->
                <div class="col-span-8">
                    <div class="space-y-6 pr-2" id="rooms-container">
                        @include('partials.room-list')
                    </div>
                </div>

                <!-- Right side - Booking Details -->
                <div class="col-span-4">
                    <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 p-6 sticky top-24">
                        <h3 class="font-bold text-lg mb-4 pb-3 border-b border-gray-200">BOOKING DETAILS</h3>
                        <div class="space-y-4">
                            <!-- Selected Rooms List -->
                            <template x-if="selectedRooms.length === 0">
                                <div class="text-gray-500 text-center py-4 border-2 border-dashed border-gray-200 rounded-lg">
                                    No rooms selected
                                </div>
                            </template>
                            
                            <!-- Selected Rooms Container with Scroll -->
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                                <div class="space-y-3">
                                    <template x-for="room in selectedRooms" :key="room.id">
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <img :src="'/storage/images/' + room.image" class="w-12 h-12 object-cover rounded">
                                                <div>
                                                    <p class="font-medium" x-text="room.name"></p>
                                                    <p class="text-sm text-gray-600" x-text="'IDR ' + room.price.toLocaleString()"></p>
                                                </div>
                                            </div>
                                            <button @click="removeRoom(room.id)" class="text-red-500 hover:text-red-600">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="border-t-2 border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total</span>
                                    <span class="font-bold text-xl" x-text="'IDR ' + totalPrice.toLocaleString()"></span>
                                </div>
                            </div>

                            <div x-show="selectedRooms.length > 0">
                                <button @click="checkAuthAndShowBooking(selectedRooms.map(room => room.id))" 
                                    class="w-full bg-[#FFA040] text-white py-3 rounded-lg hover:bg-[#FFB040] transition text-sm font-semibold">
                                    COMPLETE BOOKING
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Modal -->
<div class="calendar-modal" id="calendarModal">
    <div class="calendar-overlay"></div>
    <div class="calendar-container">
        <div id="calendar"></div>
        <div class="calendar-actions">
            <button class="btn-cancel" onclick="closeCalendar()">Cancel</button>
            <button class="btn-apply" onclick="applyDates()">Apply</button>
        </div>
    </div>
</div>

@push('scripts')
<!-- FullCalendar Scripts -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
// Add authentication check function
window.checkAuthAndShowBooking = function(roomIds) {
    @auth
        showBooking(roomIds);
    @else
        showBrutalistSwalAlert({
            title: 'Login Required',
            message: 'Please login first to complete your booking',
            type: 'info',
            confirmText: 'Login Now',
            cancelText: 'Cancel',
            onConfirm: function() {
                window.location.href = '{{ route("login") }}';
            }
        });
    @endauth
};

// Define searchRooms in the global scope
window.searchRooms = async function() {
    const checkIn = window.selectedStartDate ? formatDateForSubmit(window.selectedStartDate) : document.getElementById('search_check_in').value;
    const checkOut = window.selectedEndDate ? formatDateForSubmit(window.selectedEndDate) : document.getElementById('search_check_out').value;
    const guests = document.getElementById('search_guests').value;

    if (!checkIn || !checkOut) {
        showBrutalistSwalAlert({
            icon: 'warning',
            title: 'Please Select Dates',
            text: 'You must select both check-in and check-out dates.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    try {
        const response = await fetch(`{{ route('kamar.index') }}?check_in=${checkIn}&check_out=${checkOut}&guests=${guests}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const roomsContainer = doc.querySelector('#rooms-container');
        
        if (roomsContainer) {
            document.getElementById('rooms-container').innerHTML = roomsContainer.innerHTML;
            
            // Update URL with new search parameters
            const searchParams = new URLSearchParams(window.location.search);
            searchParams.set('check_in', checkIn);
            searchParams.set('check_out', checkOut);
            searchParams.set('guests', guests);
            const newUrl = `${window.location.pathname}?${searchParams.toString()}`;
            window.history.pushState({ path: newUrl }, '', newUrl);

            // Reset selected rooms when new search is performed
            const roomsData = document.querySelector('[x-data]')?.__x.$data;
            if (roomsData) {
                roomsData.selectedRooms = [];
                roomsData.totalPrice = 0;
                localStorage.setItem('selectedRooms', JSON.stringify([]));
            }

            // Show success message
            showBrutalistSwalAlert({
                icon: 'success',
                title: 'Rooms Updated',
                text: 'Showing available rooms for selected dates',
                confirmButtonColor: '#f59e0b',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error('Rooms container not found in response');
        }
    } catch (error) {
        console.error('Error loading rooms:', error);
        showBrutalistSwalAlert({
            type: 'error',
            title: 'Error',
            text: 'Failed to load available rooms. Please try again.',
            confirmButtonColor: '#f59e0b'
        });
    }
};

document.addEventListener('DOMContentLoaded', function() {
    window.calendar = null;
    window.selectedStartDate = null;
    window.selectedEndDate = null;
    window.currentInputType = null;

    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // Initialize calendar
    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        selectMirror: true,
        unselectAuto: false,
        dateClick: handleDateClick,
        validRange: {
            start: new Date()
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        }
    });

    window.calendar.render();

    // Initialize dates if they exist in URL
    const urlParams = new URLSearchParams(window.location.search);
    const checkIn = urlParams.get('check_in');
    const checkOut = urlParams.get('check_out');
    if (checkIn) {
        window.selectedStartDate = new Date(checkIn);
        document.getElementById('search_check_in').value = formatDateForDisplay(window.selectedStartDate);
    }
    if (checkOut) {
        window.selectedEndDate = new Date(checkOut);
        document.getElementById('search_check_out').value = formatDateForDisplay(window.selectedEndDate);
    }

    // Add pagination handling
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#pagination-links a');
        if (paginationLink) {
            e.preventDefault();
            const url = new URL(paginationLink.href);
            const checkIn = window.selectedStartDate ? formatDateForSubmit(window.selectedStartDate) : document.getElementById('search_check_in').value;
            const checkOut = window.selectedEndDate ? formatDateForSubmit(window.selectedEndDate) : document.getElementById('search_check_out').value;
            const guests = document.getElementById('search_guests').value;
            
            // Add search parameters to pagination URL
            url.searchParams.set('check_in', checkIn);
            url.searchParams.set('check_out', checkOut);
            url.searchParams.set('guests', guests);
            
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const roomsContainer = doc.querySelector('#rooms-container');
                    if (roomsContainer) {
                        document.getElementById('rooms-container').innerHTML = roomsContainer.innerHTML;
                        window.history.pushState({}, '', url);
                    }
                })
                .catch(error => {
                    console.error('Error fetching page:', error);
                    showBrutalistSwalAlert({
                        type: 'error',
                        title: 'Error',
                        text: 'Failed to load the next page. Please try again.',
                        confirmButtonColor: '#f59e0b'
                    });
                });
        }
    });
});

// Calendar Functions
window.openCalendar = function(type) {
    window.currentInputType = type;
    document.querySelector('.calendar-modal').style.display = 'block';
    if (window.calendar) {
        window.calendar.render();
        
        // Set appropriate valid range based on input type
        if (type === 'check_out' && window.selectedStartDate) {
            const nextDay = new Date(window.selectedStartDate);
            nextDay.setDate(nextDay.getDate() + 1);
            window.calendar.setOption('validRange', {
                start: nextDay
            });
        } else {
            window.calendar.setOption('validRange', {
                start: new Date()
            });
        }
        
        // Highlight selected dates
        highlightDates();
    }
};

window.closeCalendar = function() {
    document.querySelector('.calendar-modal').style.display = 'none';
};

window.applyDates = function() {
    if (window.currentInputType === 'check_in' && window.selectedStartDate) {
        document.getElementById('search_check_in').value = formatDateForDisplay(window.selectedStartDate);
        // Clear check-out if it's before new check-in
        if (window.selectedEndDate && window.selectedEndDate <= window.selectedStartDate) {
            window.selectedEndDate = null;
            document.getElementById('search_check_out').value = '';
        }
        closeCalendar();
        // Automatically open check-out selection
        setTimeout(() => openCalendar('check_out'), 100);
    } else if (window.currentInputType === 'check_out' && window.selectedEndDate) {
        document.getElementById('search_check_out').value = formatDateForDisplay(window.selectedEndDate);
        closeCalendar();
        // Trigger search after selecting dates
        searchRooms();
    }
};

window.handleDateClick = function(info) {
    const clickedDate = new Date(info.dateStr);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (clickedDate < today) {
        return; // Prevent selecting past dates
    }

    if (window.currentInputType === 'check_in') {
        window.selectedStartDate = clickedDate;
        highlightDates();
    } else if (window.currentInputType === 'check_out') {
        if (!window.selectedStartDate) {
            showBrutalistSwalAlert({
                icon: 'warning',
                title: 'Select Check-in First',
                text: 'Please select a check-in date before selecting check-out date.',
                confirmButtonColor: '#f59e0b'
            });
            closeCalendar();
            openCalendar('check_in');
            return;
        }
        if (clickedDate <= window.selectedStartDate) {
            showBrutalistSwalAlert({
                icon: 'warning',
                title: 'Invalid Date',
                text: 'Check-out date must be after check-in date.',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        window.selectedEndDate = clickedDate;
        highlightDates();
    }
};

window.highlightDates = function() {
    window.calendar.getEvents().forEach(event => event.remove());
    
    if (window.selectedStartDate) {
        // Create a new date object for end date to avoid modifying the original
        let displayEndDate = window.selectedEndDate ? new Date(window.selectedEndDate) : window.selectedStartDate;
        // Subtract one day from the end date for display purposes
        displayEndDate.setDate(displayEndDate.getDate() - 1);
        
        window.calendar.addEvent({
            start: window.selectedStartDate,
            end: displayEndDate,
            display: 'background',
            backgroundColor: '#fef3c7'
        });
    }
};

window.formatDateForDisplay = function(date) {
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

window.formatDateForSubmit = function(date) {
    return date.toISOString().split('T')[0];
};

// Add event listener for calendar overlay
document.querySelector('.calendar-overlay')?.addEventListener('click', closeCalendar);

// Tambahkan fungsi handleBackClick agar tombol back sama dengan app.blade
window.handleBackClick = function(event) {
    event.preventDefault();
    event.stopPropagation();
    // Coba sembunyikan panel jika ada (untuk konsistensi)
    var roomsPanel = document.getElementById('roomsPanel');
    if (roomsPanel) {
        roomsPanel.classList.remove('show');
        // Update Alpine.js state jika ada
        var navContainer = document.querySelector('[x-data]');
        if (navContainer && navContainer.__x) {
            navContainer.__x.$data.activeTab = 'dashboard';
            localStorage.setItem('activeTab', 'dashboard');
        }
        // Update UI nav-item jika ada
        document.querySelectorAll('.nav-item').forEach(function(item) {
            var text = item.textContent.trim();
            if (text === 'Dashboard') {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    } else {
        // Fallback: kembali ke halaman sebelumnya
        window.history.back();
    }
};
</script>
@endpush

@push('styles')
<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #FFA040;
        border-radius: 4px;
        border: 2px solid #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #FFB040;
    }
    /* Sembunyikan tombol back otomatis panel jika ada di halaman kamar */
    .back-button.sticky-back {
        display: none !important;
    }
    /* Back button styling */
    .back-button {
        position: absolute;
        top: 1rem;
        left: 1rem;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        color: #4B5563;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border-radius: 0.375rem;
        background: white;
        border: 1px solid #E5E7EB;
        cursor: pointer;
    }

    .back-button:hover {
        color: #1F2937;
        transform: translateX(-2px);
    }

    .back-button svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
    }
</style>
@endpush

@endsection