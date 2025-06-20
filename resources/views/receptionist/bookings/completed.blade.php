<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Riwayat Booking Selesai') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-500/10 backdrop-blur-sm border border-green-500/20 rounded-xl p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-[#232323] backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-[#FFD740]/40">
                <div class="p-6">
                    <!-- Search and Filter Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('receptionist.bookings.completed') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300">Cari</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-input bg-[#2D2D2D] border border-[#bbb] rounded-xl text-white placeholder-[#bbb] focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-base"
                                    placeholder="Nama, Email, atau No. Telp">
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-300">Tanggal Check-out</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="form-input bg-[#2D2D2D] border border-[#bbb] rounded-xl text-white focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-base">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                    class="w-full px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900 text-base">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Booking ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Tamu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Kamar
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Check-in
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Check-out
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($bookings as $booking)
                                    <tr class="hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                            #{{ $booking->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-100">{{ $booking->full_name }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->email }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($booking->rooms as $room)
                                                <div class="text-sm text-gray-100">Room {{ $room->room_number }}</div>
                                                <div class="text-sm text-gray-400">{{ $room->type }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-100">{{ $booking->check_in_date->format('d M Y') }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->checked_in_at ? $booking->checked_in_at->format('H:i') : '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-100">{{ $booking->check_out_date->format('d M Y') }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->checked_out_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-green-500/10 text-green-400 border border-green-500/50">
                                                Selesai
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                            Tidak ada data booking yang selesai.
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
    </div>
</x-receptionist-layout> 