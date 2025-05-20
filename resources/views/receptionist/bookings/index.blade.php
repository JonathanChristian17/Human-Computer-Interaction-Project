<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Manage Bookings') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filter -->
        <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
            <div class="p-6">
                <form method="GET" action="{{ route('receptionist.bookings') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-400">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white placeholder-gray-400 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="Name, Email, or Phone">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-400">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                            <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Guest</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($bookings as $booking)
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
                                        <div class="text-sm text-white">Check-in: {{ $booking->check_in_date->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-400">Check-out: {{ $booking->check_out_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($booking->status === 'confirmed') bg-green-500/10 text-green-400
                                            @elseif($booking->status === 'pending') bg-amber-500/10 text-amber-400
                                            @elseif($booking->status === 'cancelled') bg-red-500/10 text-red-400
                                            @elseif($booking->status === 'checked_in') bg-blue-500/10 text-blue-400
                                            @else bg-gray-500/10 text-gray-400 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($booking->payment_status === 'paid') bg-green-500/10 text-green-400
                                            @elseif($booking->payment_status === 'pending') bg-amber-500/10 text-amber-400
                                            @else bg-red-500/10 text-red-400 @endif">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" onclick="return confirm('Konfirmasi pemesanan ini?')" class="text-green-400 hover:text-green-300">Confirm</button>
                                            </form>
                                            <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" onclick="return confirm('Batalkan pemesanan ini?')" class="text-red-400 hover:text-red-300">Cancel</button>
                                            </form>
                                        @elseif($booking->status === 'confirmed')
                                            @if($booking->payment_status === 'paid')
                                                <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="checked_in">
                                                    <button type="submit" onclick="return confirm('Check-in tamu ini?')" class="text-blue-400 hover:text-blue-300">Check-in</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" onclick="return confirm('Batalkan pemesanan ini? Jika sudah dibayar, status pembayaran akan diubah menjadi refunded.')" class="text-red-400 hover:text-red-300">Cancel</button>
                                            </form>
                                        @elseif($booking->status === 'checked_in')
                                            <form action="{{ route('receptionist.bookings.status', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="checked_out">
                                                <button type="submit" onclick="return confirm('Check-out tamu ini? Status kamar akan diubah menjadi cleaning.')" class="text-amber-400 hover:text-amber-300">Check-out</button>
                                            </form>
                                        @endif
                                        
                                        @if($booking->status !== 'cancelled')
                                            <a href="{{ route('receptionist.bookings.invoice', $booking) }}" class="text-gray-400 hover:text-white" target="_blank">Invoice</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-receptionist-layout> 