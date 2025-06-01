@foreach($rooms as $room)
<div class="bg-white rounded-xl overflow-hidden border border-gray-100 mb-4" style="box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);">
    <div class="flex">
        <!-- Room Image -->
        <div class="w-1/3">
            <img src="{{ asset('storage/images/' . ($room->image ?: 'room-default.jpg')) }}" 
                 alt="{{ $room->name }}" 
                 class="w-full h-48 object-cover">
        </div>

        <!-- Room Details -->
        <div class="flex-1 p-6">
            <div class="flex items-center gap-3 mb-2">
                <h3 class="text-xl font-bold">{{ $room->name }}</h3>
                <div class="flex items-center gap-1">
                    <span class="px-2 py-1 bg-[#f59e0b]/10 text-[#f59e0b] rounded-lg text-xs">2.1</span>
                    <span class="px-2 py-1 bg-[#f59e0b]/10 text-[#f59e0b] rounded-lg text-xs">King Size Bed</span>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">{{ $room->description }}</p>
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-2xl font-bold text-gray-900">IDR {{ number_format($room->price_per_night, 0, ',', ',') }}</p>
                    <p class="text-sm text-gray-500">per night</p>
                </div>
                <template x-if="!isRoomSelected({{ $room->id }})">
                    <button @click="addRoom({
                        id: {{ $room->id }},
                        name: '{{ $room->name }}',
                        price: {{ $room->price_per_night }},
                        image: '{{ $room->image ?: 'room-default.jpg' }}'
                    })" 
                    class="bg-[#f59e0b] text-white px-6 py-2 rounded-lg hover:bg-[#d97706] transition text-sm">
                        Booking
                    </button>
                </template>
                <template x-if="isRoomSelected({{ $room->id }})">
                    <button 
                    class="bg-green-500 text-white px-6 py-2 rounded-lg transition text-sm flex items-center gap-2 cursor-default">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Sudah Ditambahkan
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="mt-6">
    <div class="pagination">
        {{ $rooms->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('.pagination a');
            if (paginationLink) {
                e.preventDefault();
                const url = new URL(paginationLink.href);
                const checkIn = window.selectedStartDate ? formatDateForSubmit(window.selectedStartDate) : document.getElementById('search_check_in').value;
                const checkOut = window.selectedEndDate ? formatDateForSubmit(window.selectedEndDate) : document.getElementById('search_check_out').value;
                const guests = document.getElementById('search_guests').value;
                url.searchParams.set('check_in', checkIn);
                url.searchParams.set('check_out', checkOut);
                url.searchParams.set('guests', guests);
                // Tampilkan loading spinner
                document.getElementById('rooms-container').innerHTML = `
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#f59e0b] mx-auto"></div>
                        <p class="mt-2 text-gray-600">Loading rooms...</p>
                    </div>
                `;
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load the next page. Please try again.',
                            confirmButtonColor: '#f59e0b'
                        });
                    });
            }
        });
    });
</script> 