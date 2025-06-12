<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Guest Management') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Active Guests -->
        <div class="bg-[#232323] backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-[#FFD740]/40">
            <div class="p-6">
                <h3 class="text-lg font-medium text-white mb-4">Active Guests</h3>
                
                @if($activeGuests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Check-out</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($activeGuests as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">{{ $booking->full_name }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->email }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($booking->rooms as $room)
                                            <div class="text-sm text-white">Room {{ $room->room_number }}</div>
                                            <div class="text-sm text-gray-400">{{ $room->type }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">{{ $booking->check_in_date->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->check_in_time ? $booking->check_in_time->format('H:i') : '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">{{ $booking->check_out_date->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="checked_out">
                                                <button type="submit" class="text-amber-400 hover:text-amber-300">Check-out</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-400">No active guests at the moment.</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Guests -->
        <div class="bg-[#232323] backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-[#FFD740]/40">
            <div class="p-6">
                <h3 class="text-lg font-medium text-white mb-4">Upcoming Guests</h3>
                
                @if($upcomingGuests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Check-out</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($upcomingGuests as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">{{ $booking->full_name }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->email }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($booking->rooms as $room)
                                            <div class="text-sm text-white">Room {{ $room->room_number }}</div>
                                            <div class="text-sm text-gray-400">{{ $room->type }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">{{ $booking->check_in_date->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">{{ $booking->check_out_date->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($booking->check_in_date->isToday())
                                                <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="checked_in">
                                                    <button type="submit" class="text-green-400 hover:text-green-300">Check-in</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">{{ $booking->check_in_date->diffForHumans() }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-400">No upcoming guests.</p>
                @endif
            </div>
        </div>
    </div>
</x-receptionist-layout> 