@foreach($rooms as $room)
<div class="bg-white rounded-xl overflow-hidden border border-gray-100 mb-4" style="box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);">
    <div class="flex flex-col md:flex-row">
        <!-- Room Image -->
        <div class="w-full md:w-1/3">
            <img src="{{ asset('storage/images/' . ($room->image ?: 'room-default.jpg')) }}" 
                 alt="{{ $room->name }}" 
                 class="w-full h-48 md:h-full object-cover">
        </div>

        <!-- Room Details -->
        <div class="flex-1 p-4 md:p-6">
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3 mb-2">
                <h3 class="text-lg md:text-xl font-bold">{{ $room->name }}</h3>
                <div class="flex items-center gap-1">
                    <span class="px-2 py-1 bg-[#f59e0b]/10 text-[#f59e0b] rounded-lg text-xs">{{ $room->type }}</span>
                    <span class="px-2 py-1 bg-[#f59e0b]/10 text-[#f59e0b] rounded-lg text-xs">{{ $room->capacity }} Guest</span>
                    <span class="px-2 py-1 rounded-lg text-xs
                        @if($room->status === 'available') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($room->status) }}
                    </span>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4 line-clamp-3 md:line-clamp-none">{{ $room->description }}</p>

            <!-- Price and Action -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-lg font-bold text-[#f59e0b]">Rp{{ number_format($room->price_per_night, 0, ',', '.') }}</span>
                    <span class="text-gray-500 text-sm">/night</span>
                </div>
                @if($room->status === 'available')
                    <template x-if="!isRoomSelected({{ $room->id }})">
                        <button @click="addRoom({
                            id: {{ $room->id }},
                            name: '{{ $room->name }}',
                            price: {{ $room->price_per_night }},
                            image: '{{ $room->image ?: 'room-default.jpg' }}'
                        })" 
                        class="px-6 py-2 bg-[#f59e0b] text-white rounded-lg hover:bg-[#f59e0b]/90 transition-colors">
                            Add to Booking
                        </button>
                    </template>
                    <template x-if="isRoomSelected({{ $room->id }})">
                        <button @click="removeRoom({{ $room->id }})" 
                        class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Remove
                        </button>
                    </template>
                @else
                    <button class="px-6 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                        Not Available
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="mt-6">
    <div class="pagination">
        {{ $rooms->appends(request()->query())->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('.pagination a');
            if (paginationLink) {
                e.preventDefault();
                const url = new URL(paginationLink.href);
                
                // Get search parameters from URL if they exist
                const urlParams = new URLSearchParams(window.location.search);
                const checkIn = urlParams.get('check_in') || '';
                const checkOut = urlParams.get('check_out') || '';
                const roomType = urlParams.get('room_type') || '';
                
                // Only add parameters if they exist
                if (checkIn) url.searchParams.set('check_in', checkIn);
                if (checkOut) url.searchParams.set('check_out', checkOut);
                if (roomType) url.searchParams.set('room_type', roomType);

                // Show loading spinner
                document.getElementById('rooms-container').innerHTML = `
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#f59e0b] mx-auto"></div>
                        <p class="mt-2 text-gray-600">Loading rooms...</p>
                    </div>
                `;

                // Fetch new page
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const roomList = doc.querySelector('#rooms-container');
                        if (roomList) {
                            document.getElementById('rooms-container').innerHTML = roomList.innerHTML;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('rooms-container').innerHTML = `
                            <div class="text-center py-4">
                                <p class="text-red-600">Error loading rooms. Please try again.</p>
                            </div>
                        `;
                    });
            }
        });
    });
</script> 