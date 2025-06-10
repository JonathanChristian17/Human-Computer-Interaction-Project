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
                    <span class="px-2 py-1 bg-[#FFA040]/10 text-[#FFA040] rounded-lg text-xs">{{ $room->type }}</span>
                    <span class="px-2 py-1 bg-[#FFA040]/10 text-[#FFA040] rounded-lg text-xs">{{ $room->capacity }} Guest</span>
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
                    <span class="text-lg font-bold text-[#FFA040]">Rp{{ number_format($room->price_per_night, 0, ',', '.') }}</span>
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
                        class="px-6 py-2 bg-[#FFA040] text-white rounded-lg hover:bg-[#FFA040]/90 transition-colors">
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

<!-- Custom Loader (pindah ke dalam rooms-container via JS) -->
<template id="custom-loader-template">
  <div id="custom-loader" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; z-index: 50; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center;">
    <div class="wrapper">
      <div class="circle"></div>
      <div class="circle"></div>
      <div class="circle"></div>
      <div class="shadow"></div>
      <div class="shadow"></div>
      <div class="shadow"></div>
    </div>
  </div>
</template>

<style>
    /* Custom Pagination Styles */
    .pagination {
        @apply flex justify-center gap-2;
    }
    
    .pagination > * {
        @apply flex items-center justify-center;
    }
    
    .pagination a {
        @apply px-4 py-2 text-sm font-medium rounded-lg transition-colors;
        color: #FFA040;
        border: 1px solid #FFA040;
    }
    
    .pagination a:hover,
    .pagination .relative.inline-flex.items-center:hover {
        background: #FFA040 !important;
        color: #fff !important;
        border-color: #FFA040 !important;
    }
    
    .pagination .active {
        @apply text-white;
        background-color: #FFA040;
        border-color: #FFA040;
    }
    
    .pagination .disabled {
        @apply text-gray-400 cursor-not-allowed;
        border-color: #e5e7eb;
    }
    
    .pagination .relative.inline-flex,
    .pagination .relative.inline-flex.items-center {
        background: #fff !important;
    }

    .wrapper {
        width: 200px;
        height: 60px;
        position: relative;
        z-index: 1;
    }
    .circle {
        width: 20px;
        height: 20px;
        position: absolute;
        border-radius: 50%;
        background-color: #FFA040;
        left: 15%;
        transform-origin: 50%;
        animation: circle7124 .5s alternate infinite ease;
    }
    @keyframes circle7124 {
        0% { top: 60px; height: 5px; border-radius: 50px 50px 25px 25px; transform: scaleX(1.7); }
        40% { height: 20px; border-radius: 50%; transform: scaleX(1); }
        100% { top: 0%; }
    }
    .circle:nth-child(2) { left: 45%; animation-delay: .2s; }
    .circle:nth-child(3) { left: auto; right: 15%; animation-delay: .3s; }
    .shadow {
        width: 20px;
        height: 4px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.09);
        position: absolute;
        top: 62px;
        transform-origin: 50%;
        z-index: -1;
        left: 15%;
        filter: blur(1px);
        animation: shadow046 .5s alternate infinite ease;
    }
    @keyframes shadow046 {
        0% { transform: scaleX(1.5); }
        40% { transform: scaleX(1); opacity: .7; }
        100% { transform: scaleX(.2); opacity: .4; }
    }
    .shadow:nth-child(4) { left: 45%; animation-delay: .2s }
    .shadow:nth-child(5) { left: auto; right: 15%; animation-delay: .3s; }
</style>

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

                // Show custom loader inside rooms-container
                const roomsContainer = document.getElementById('rooms-container');
                if (roomsContainer) {
                  // Remove existing loader if any
                  const oldLoader = document.getElementById('custom-loader');
                  if (oldLoader) oldLoader.remove();
                  // Insert loader
                  const loaderTemplate = document.getElementById('custom-loader-template');
                  if (loaderTemplate) {
                    roomsContainer.style.position = 'relative';
                    roomsContainer.insertAdjacentHTML('afterbegin', loaderTemplate.innerHTML);
                  }
                }

                // Fetch new page
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const roomList = doc.querySelector('#rooms-container');
                        if (roomList) {
                            roomsContainer.innerHTML = roomList.innerHTML;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        roomsContainer.innerHTML = `
                            <div class="text-center py-4">
                                <p class="text-red-600">Error loading rooms. Please try again.</p>
                            </div>
                        `;
                    });
            }
        });
    });
</script> 