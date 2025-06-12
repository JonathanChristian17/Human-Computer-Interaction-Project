<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Room Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-medium text-[#FFA040]">All Rooms</h3>
                <a href="{{ route('admin.rooms.create') }}" class="bg-[#FFA040] hover:bg-[#ff8c1a] text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200">
                    Add New Room
                </a>
            </div>

            <div class="bg-[#252525] overflow-hidden shadow-sm sm:rounded-lg border border-[#FFA040]">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($rooms as $room)
                            <div class="bg-[#1D1D1D] rounded-lg overflow-hidden shadow-md border border-[#FFA040]">
                                @if($room->image)
                                    <img src="{{ asset('storage/images/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-48 object-cover">
                                @endif
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-semibold text-white">Room {{ $room->room_number }}</h4>
                                            <p class="text-sm text-gray-400">{{ ucfirst($room->type) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($room->status === 'available') bg-green-500/10 text-green-400
                                            @else bg-red-500/10 text-red-400 @endif">
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-400 mb-4">{{ $room->description }}</p>

                                    <div class="flex justify-between items-center text-sm text-gray-400">
                                        <span>Capacity: {{ $room->capacity }} persons</span>
                                        <span class="text-[#FFA040] font-semibold">Rp{{ number_format($room->price_per_night, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-4">
                                        <a href="{{ route('admin.rooms.edit', $room) }}" 
                                           class="px-5 py-2 bg-[#FFA040] text-white hover:bg-[#ff8c1a] rounded-lg transition-all duration-200">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="inline needs-confirm" data-confirm-message="Yakin ingin menghapus kamar ini? Data kamar akan dihapus permanen.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-[#1D1D1D] text-red-400 hover:bg-[#2D2D2D] rounded-lg transition-all duration-200 border border-red-400">
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