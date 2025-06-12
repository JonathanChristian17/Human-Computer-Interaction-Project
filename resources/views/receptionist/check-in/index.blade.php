<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Check-in Tamu') }}
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

            @if(session('error'))
                <div class="mb-6 bg-red-500/10 backdrop-blur-sm border border-red-500/20 rounded-xl p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-400">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-[#232323] backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-[#FFD740]/40">
                <div class="p-6">
                    <!-- Search and Filter Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('receptionist.check-in') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300">Cari</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white placeholder-gray-400 focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Nama, Email, atau No. Telp">
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-300">Tanggal Check-in</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Semua Status</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Menunggu Check-in</option>
                                    <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Sudah Check-in</option>
                                    <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>Sudah Check-out</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                    class="w-full px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900">
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
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Aksi
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
                                            @if($booking->checked_in_at)
                                                <div class="text-sm text-gray-400">{{ $booking->checked_in_at->format('H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                            {{ $booking->check_out_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg
                                                {{ $booking->status === 'confirmed' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/50' : '' }}
                                                {{ $booking->status === 'checked_in' ? 'bg-green-500/10 text-green-400 border border-green-500/50' : '' }}
                                                {{ $booking->status === 'checked_out' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/50' : '' }}">
                                                @if($booking->status === 'confirmed')
                                                    Menunggu Check-in
                                                @elseif($booking->status === 'checked_in')
                                                    Sudah Check-in
                                                @elseif($booking->status === 'checked_out')
                                                    Sudah Check-out
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if($booking->status === 'confirmed' && $booking->payment_status === 'paid')
                                                <form action="{{ route('receptionist.check-in.process', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                        class="text-amber-400 hover:text-amber-300 bg-amber-500/10 px-3 py-1.5 rounded-lg border border-amber-500/20">
                                                        Check-in
                                                    </button>
                                                </form>
                                            @elseif($booking->status === 'checked_in')
                                                <a href="{{ route('receptionist.check-out') }}" 
                                                    class="text-green-400 hover:text-green-300 bg-green-500/10 px-3 py-1.5 rounded-lg border border-green-500/20">
                                                    Lihat Check-out
                                                </a>
                                            @elseif($booking->status === 'checked_out')
                                                <span class="text-blue-400 bg-blue-500/10 px-3 py-1.5 rounded-lg border border-blue-500/20">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                            Tidak ada data booking yang ditemukan.
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