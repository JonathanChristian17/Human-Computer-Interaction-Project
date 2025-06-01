@foreach($rooms as $room)
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-4">
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
                    <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">2.1</span>
                    <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">King Size Bed</span>
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
                    class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition text-sm">
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