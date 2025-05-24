@extends('layouts.app')

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
        init() {
            // Load selected rooms from localStorage on page load
            const stored = localStorage.getItem('selectedRooms');
            if (stored) {
                this.selectedRooms = JSON.parse(stored);
                this.calculateTotal();
            }
        }
    }" x-init="init()">
        <!-- Back Button -->
        <button onclick="hideRooms()" class="absolute top-6 left-6 z-50 flex items-center text-white hover:text-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Home
        </button>

        <!-- Top Booking Bar -->
        <div class="bg-white border-b sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-4">
                    <!-- Check-in -->
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <input type="date" 
                               id="search_check_in"
                               name="search_check_in"
                               class="bg-transparent border-none focus:outline-none text-sm" 
                               placeholder="Check in">
                    </div>
                    
                    <!-- Check-out -->
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <input type="date" 
                               id="search_check_out"
                               name="search_check_out"
                               class="bg-transparent border-none focus:outline-none text-sm" 
                               placeholder="Checkout">
                    </div>
                    
                    <!-- Room & Guests -->
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border">
                        <i class="fas fa-bed text-gray-400"></i>
                        <select id="search_room_guests"
                                name="search_room_guests"
                                class="bg-transparent border-none focus:outline-none text-sm text-gray-600">
                            <option value="1-2">1 Room, 2 guest</option>
                            <option value="2-4">2 Rooms, 4 guests</option>
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <button class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition text-sm">
                        Search
                    </button>
                </div>
            </div>
        </div>

        <!-- Booking content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h3 class="font-bold text-lg mb-4">BOOKING DETAILS</h3>
                        <div class="space-y-4">
                            <!-- Selected Rooms List -->
                            <template x-if="selectedRooms.length === 0">
                                <div class="text-gray-500 text-center py-4">
                                    No rooms selected
                                </div>
                            </template>
                            
                            <!-- Selected Rooms Container with Scroll -->
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                                <div class="space-y-3">
                                    <template x-for="room in selectedRooms" :key="room.id">
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
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

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total</span>
                                    <span class="font-bold text-xl" x-text="'IDR ' + totalPrice.toLocaleString()"></span>
                                </div>
                            </div>

                            <div x-show="selectedRooms.length > 0">
                                <button @click="showBooking(selectedRooms.map(room => room.id))" 
                                    class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition text-sm font-semibold">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle pagination clicks
    $(document).on('click', '#pagination-links a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchRooms(page);
    });

    function fetchRooms(page) {
        $.ajax({
            url: '{{ route("kamar.index") }}?page=' + page,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // If it's an AJAX request, just update the rooms container
                if (response.includes('rooms-container')) {
                    $('#rooms-container').html(response);
                } else {
                    // If it's a full page response, extract just the rooms container content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(response, 'text/html');
                    const roomsContainer = doc.querySelector('#rooms-container');
                    if (roomsContainer) {
                        $('#rooms-container').html(roomsContainer.innerHTML);
                    }
                }
                // Update URL without page refresh
                window.history.pushState({}, '', '{{ route("kamar.index") }}?page=' + page);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching rooms:', error);
            }
        });
    }
});
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
        background: #f59e0b;
        border-radius: 4px;
        border: 2px solid #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #e67e22;
    }
</style>
@endpush

@endsection