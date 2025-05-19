<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Room Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-100">All Rooms</h3>
                <a href="{{ route('admin.rooms.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Add New Room
                </a>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($rooms as $room)
                            <div class="bg-gray-700 rounded-lg overflow-hidden shadow-md">
                                @if($room->image)
                                    <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-48 object-cover">
                                @endif
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-semibold text-white">Room {{ $room->room_number }}</h4>
                                            <p class="text-sm text-gray-400">{{ ucfirst($room->type) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($room->status === 'available') bg-green-500/10 text-green-400
                                            @elseif($room->status === 'occupied') bg-blue-500/10 text-blue-400
                                            @elseif($room->status === 'cleaning') bg-amber-500/10 text-amber-400
                                            @else bg-red-500/10 text-red-400 @endif">
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-400 mb-4">{{ $room->description }}</p>

                                    <div class="flex justify-between items-center text-sm text-gray-400">
                                        <span>Capacity: {{ $room->capacity }} persons</span>
                                        <span class="text-amber-400 font-semibold">Rp{{ number_format($room->price_per_night, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-4">
                                        <a href="{{ route('admin.rooms.edit', $room) }}" 
                                           class="px-5 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg transition-colors duration-200">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg transition-colors duration-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 