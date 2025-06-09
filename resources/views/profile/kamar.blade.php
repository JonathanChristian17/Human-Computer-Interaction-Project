@extends('layouts.app')

@section('content')
<div class="relative min-h-screen">
    <!-- Main Content -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Semua Kamar</h2>
                <div class="w-20 h-1 bg-amber-400 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
                @foreach($rooms as $room)
                    @php
                        $imageUrl = $room->image ? asset('storage/' . $room->image) : 'https://source.unsplash.com/random/600x400/?hotel-room,' . $loop->index;
                        
                        // Define status badge styles
                        $statusClasses = match($room->status) {
                            'available' => 'bg-green-500/20 text-green-500',
                            'maintenance' => 'bg-yellow-500/20 text-yellow-500',
                            default => 'bg-red-500/20 text-red-500'
                        };
                        
                        // Define status text for customers
                        $statusText = match($room->status) {
                            'available' => 'Tersedia',
                            'maintenance' => 'Dalam Perbaikan',
                            default => 'Tidak Tersedia'
                        };

                        // Define button state based on room status
                        $isBookable = $room->status === 'available';
                        $buttonClasses = $isBookable 
                            ? 'bg-amber-500 hover:bg-amber-600 text-white cursor-pointer'
                            : 'bg-gray-500 text-gray-300 cursor-not-allowed';
                        $buttonText = $isBookable ? 'Tambahkan' : $statusText;
                    @endphp
                    <article class="group relative overflow-hidden rounded-xl bg-gray-800 shadow-xl transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        <div class="relative h-64 overflow-hidden">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="{{ $room->name }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>
                            <div class="absolute top-4 right-4 flex flex-col gap-2 items-end">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-amber-500 rounded-full">
                                    {{ $room->capacity }} orang
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $room->name }}</h3>
                            <p class="text-gray-400 text-sm mb-4">{{ $room->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-amber-500 font-bold">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}/malam</span>
                                <button 
                                    @if($isBookable)
                                        onclick="addToBooking({{ json_encode([
                                            'id' => $room->id,
                                            'name' => $room->name,
                                            'price' => $room->price_per_night,
                                            'capacity' => $room->capacity,
                                            'image' => $imageUrl
                                        ]) }})"
                                    @endif
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 {{ $buttonClasses }}"
                                    {{ $isBookable ? '' : 'disabled' }}
                                >
                                    {{ $buttonText }}
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $rooms->links() }}
            </div>
        </div>
    </section>

    <!-- Cart Button in Fixed Position -->
    <button id="cart-button" class="fixed bottom-6 right-6 bg-amber-500 text-gray-900 p-4 rounded-full shadow-lg hover:bg-amber-400 transition-colors z-50">
        <div class="relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
        </div>
    </button>

    <!-- Floating Sidebar -->
    <div id="cart-sidebar" class="fixed top-24 right-4 w-80 bg-gray-800 rounded-xl shadow-2xl border border-gray-700/50 transform transition-transform duration-300 translate-x-full">
        <div class="p-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white flex items-center justify-between">
                Kamar Dipilih
                <button id="toggle-sidebar" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </h3>
        </div>
        <div id="selected-rooms" class="p-4 space-y-4 max-h-[calc(2*5.5rem+1rem)] overflow-y-auto scrollbar-thin scrollbar-thumb-amber-500 scrollbar-track-gray-700/30">
            <!-- Selected rooms will be dynamically added here -->
        </div>
        <div class="p-4 border-t border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <span class="text-white font-medium">Total:</span>
                <span id="total-price" class="text-xl font-bold text-amber-400">Rp0</span>
            </div>
            <button id="proceed-booking" class="w-full bg-amber-500 text-gray-900 font-semibold py-2 px-4 rounded-lg hover:bg-amber-400 transition-colors">
                Lanjutkan Pemesanan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<style>
/* Custom scrollbar styling */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: rgba(55, 65, 81, 0.3);
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgb(245, 158, 11);
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgb(217, 119, 6);
}
</style>
<script>
// Define showLoginNotification outside DOMContentLoaded
function showLoginNotification() {
    Swal.fire({
        title: 'Login Diperlukan',
        text: 'Anda harus login terlebih dahulu untuk memesan kamar',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Login Sekarang',
        cancelButtonText: 'Nanti Saja',
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#6B7280',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route('login') }}';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Room listing script loaded');
    
    // Get current user ID from meta tag if user is logged in
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    const userId = userIdMeta ? userIdMeta.content : null;
    
    // Only proceed with cart functionality if user is logged in
    if (userId) {
        // Use user-specific storage key
        const cartStorageKey = `selectedRooms_${userId}`;
        let selectedRooms = JSON.parse(sessionStorage.getItem(cartStorageKey) || '[]');
        console.log('Initial selected rooms:', selectedRooms);
        
        const sidebar = document.getElementById('cart-sidebar');
        const selectedRoomsContainer = document.getElementById('selected-rooms');
        const totalPriceElement = document.getElementById('total-price');
        const proceedButton = document.getElementById('proceed-booking');
        const cartButton = document.getElementById('cart-button');
        const cartCount = document.getElementById('cart-count');

        // Initialize the UI
        updateSidebar();
        updateButtonStates();
        updateCartCount();

        // Toggle sidebar with cart button
        cartButton.addEventListener('click', function() {
            sidebar.classList.toggle('translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        });

        // Toggle sidebar with close button
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            console.log('Toggle sidebar clicked');
            sidebar.classList.add('translate-x-full');
            sidebar.classList.remove('translate-x-0');
        });

        // Add room to selection
        window.addToSelection = function(roomDataString) {
            // Verify user is still logged in
            const currentUserId = document.querySelector('meta[name="user-id"]').content;
            if (!currentUserId || currentUserId !== userId) {
                alert('Sesi Anda telah berakhir. Silakan login kembali.');
                window.location.href = '/login';
                return;
            }

            console.log('Adding room:', roomDataString);
            try {
                const roomData = JSON.parse(roomDataString);
                console.log('Parsed room data:', roomData);
                
                // Check if room is already selected
                if (selectedRooms.some(room => room.id === roomData.id)) {
                    alert('Kamar ini sudah dipilih!');
                    return;
                }

                selectedRooms.push(roomData);
                sessionStorage.setItem(cartStorageKey, JSON.stringify(selectedRooms));
                console.log('Updated selected rooms:', selectedRooms);
                
                updateSidebar();
                updateButtonStates();
                updateCartCount();
                
                // Show sidebar
                sidebar.classList.remove('translate-x-full');
                sidebar.classList.add('translate-x-0');
                
            } catch (error) {
                console.error('Error adding room:', error);
                alert('Terjadi kesalahan saat menambahkan kamar.');
            }
        }

        // Update button states
        function updateButtonStates() {
            const allButtons = document.querySelectorAll('[id^="add-btn-"]');
            allButtons.forEach(button => {
                const roomId = parseInt(button.id.replace('add-btn-', ''));
                const isSelected = selectedRooms.some(room => room.id === roomId);
                
                if (isSelected) {
                    button.classList.remove('bg-[#FFA040]/10', 'hover:bg-[#FFA040]/20', 'text-[#FFA040]');
                    button.classList.add('bg-gray-500/10', 'text-gray-400', 'cursor-not-allowed');
                    button.innerHTML = `
                        <span>Sudah Dipilih</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    `;
                    button.disabled = true;
                } else {
                    button.classList.remove('bg-gray-500/10', 'text-gray-400', 'cursor-not-allowed');
                    button.classList.add('bg-[#FFA040]/10', 'hover:bg-[#FFA040]/20', 'text-[#FFA040]');
                    button.innerHTML = `
                        <span>Tambahkan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    `;
                    button.disabled = false;
                }
            });
        }

        // Update cart count
        function updateCartCount() {
            cartCount.textContent = selectedRooms.length;
        }

        // Update sidebar content
        function updateSidebar() {
            console.log('Updating sidebar with rooms:', selectedRooms);
            selectedRoomsContainer.innerHTML = '';
            
            if (selectedRooms.length === 0) {
                selectedRoomsContainer.innerHTML = '<p class="text-gray-400 text-center py-2">Tidak ada kamar yang dipilih</p>';
                totalPriceElement.textContent = 'Rp0';
                return;
            }
            
            selectedRooms.forEach(room => {
                const roomElement = document.createElement('div');
                roomElement.className = 'bg-gray-700/50 rounded-lg p-3 relative h-[5.5rem] flex flex-col justify-between';
                roomElement.innerHTML = `
                    <div class="relative">
                        <button class="absolute -top-1 right-0 text-gray-400 hover:text-red-400" onclick="removeRoom(${room.id})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <h4 class="text-white font-medium text-sm pr-6 truncate">${room.name}</h4>
                        <p class="text-xs text-gray-400">${room.capacity} orang</p>
                    </div>
                    <div class="mt-auto">
                        <p class="text-[#FFA040] font-semibold text-sm">Rp${parseInt(room.price_per_night).toLocaleString('id-ID')}</p>
                    </div>
                `;
                selectedRoomsContainer.appendChild(roomElement);
            });

            // Update total price
            const total = selectedRooms.reduce((sum, room) => sum + parseInt(room.price_per_night), 0);
            totalPriceElement.textContent = `Rp${total.toLocaleString('id-ID')}`;
        }

        // Remove room from selection
        window.removeRoom = function(roomId) {
            // Verify user is still logged in
            const currentUserId = document.querySelector('meta[name="user-id"]').content;
            if (!currentUserId || currentUserId !== userId) {
                alert('Sesi Anda telah berakhir. Silakan login kembali.');
                window.location.href = '/login';
                return;
            }

            console.log('Removing room:', roomId);
            selectedRooms = selectedRooms.filter(room => room.id !== roomId);
            sessionStorage.setItem(cartStorageKey, JSON.stringify(selectedRooms));
            console.log('Updated selected rooms after removal:', selectedRooms);
            
            updateSidebar();
            updateButtonStates();
            updateCartCount();
            
            // Hide sidebar if no rooms selected
            if (selectedRooms.length === 0) {
                sidebar.classList.add('translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }

            // Re-enable the add button for the removed room
            const button = document.getElementById(`add-btn-${roomId}`);
            if (button) {
                button.classList.remove('bg-gray-500/10', 'text-gray-400', 'cursor-not-allowed');
                button.classList.add('bg-[#FFA040]/10', 'hover:bg-[#FFA040]/20', 'text-[#FFA040]');
                button.innerHTML = `
                    <span>Tambahkan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                `;
                button.disabled = false;
            }
        }

        // Clear cart on logout
        document.addEventListener('logout', function() {
            sessionStorage.removeItem(cartStorageKey);
        });

        // Proceed to booking
        proceedButton.addEventListener('click', function() {
            // Verify user is still logged in
            const currentUserId = document.querySelector('meta[name="user-id"]').content;
            if (!currentUserId || currentUserId !== userId) {
                alert('Sesi Anda telah berakhir. Silakan login kembali.');
                window.location.href = '/login';
                return;
            }

            console.log('Proceed button clicked');
            if (selectedRooms.length === 0) {
                alert('Pilih setidaknya satu kamar untuk melanjutkan pemesanan.');
                return;
            }

            // Store selected rooms in session storage with user-specific key
            sessionStorage.setItem(cartStorageKey, JSON.stringify(selectedRooms));
            console.log('Storing selected rooms before redirect:', selectedRooms);
            
            // Redirect to booking page
            window.location.href = '{{ route('bookings.create') }}';
        });
    }
});

function addToBooking(room) {
    // Check if user is authenticated
    @auth
        // Add room to booking
        window.dispatchEvent(new CustomEvent('add-room-to-booking', { detail: room }));
    @else
        // Show login prompt
        Swal.fire({
            title: 'Login Required',
            text: 'Please login first to book a room',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Login Now',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f59e0b',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("login") }}';
            }
        });
    @endauth
}
</script>
@endpush

@endsection