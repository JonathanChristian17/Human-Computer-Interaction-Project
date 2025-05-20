<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Manage Rooms') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Room Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($rooms as $room)
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-white">Room {{ $room->room_number }}</h3>
                                <p class="text-sm text-gray-400">{{ $room->type }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($room->status === 'available') bg-green-500/10 text-green-400
                                @elseif($room->status === 'occupied') bg-blue-500/10 text-blue-400
                                @elseif($room->status === 'cleaning') bg-amber-500/10 text-amber-400
                                @else bg-red-500/10 text-red-400 @endif">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm text-gray-400">{{ $room->description }}</p>
                            <p class="mt-2 text-sm text-gray-400">Capacity: {{ $room->capacity }} persons</p>
                            <p class="text-sm text-gray-400">Price: Rp{{ number_format($room->price_per_night, 0, ',', '.') }}/night</p>
                        </div>

                        <div class="mt-6">
                            <form action="{{ route('receptionist.rooms.status', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex space-x-2">
                                    <select name="status" class="flex-1 rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                                        <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="cleaning" {{ $room->status === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                        <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if($room->status === 'occupied')
                            @php
                                $currentBooking = $room->bookings()
                                    ->where('status', 'checked_in')
                                    ->with('user')
                                    ->first();
                            @endphp
                            @if($currentBooking)
                                <div class="mt-4 p-3 bg-gray-700/50 rounded-lg border border-gray-600/50">
                                    <p class="text-sm font-medium text-white">Current Guest:</p>
                                    <p class="text-sm text-gray-400">{{ $currentBooking->full_name }}</p>
                                    <p class="text-sm text-gray-400">Check-out: {{ $currentBooking->check_out_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                        @else
                            @php
                                $pendingBooking = $room->bookings()
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'asc')
                                    ->first();
                            @endphp
                            @if($pendingBooking)
                                <div class="mt-4 p-3 bg-amber-500/10 rounded-lg border border-amber-500/50">
                                    <p class="text-sm font-medium text-amber-400">Pending Booking:</p>
                                    <p class="text-sm text-gray-400">{{ $pendingBooking->full_name }}</p>
                                    <p class="text-sm text-gray-400">Check-in: {{ $pendingBooking->check_in_date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-400">Check-out: {{ $pendingBooking->check_out_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-receptionist-layout> 