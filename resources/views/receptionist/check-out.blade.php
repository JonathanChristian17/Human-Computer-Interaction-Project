<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Check-out Tamu') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-500/10 backdrop-blur-sm border border-green-500/20 rounded-xl p-4">
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
                <div class="bg-red-500/10 backdrop-blur-sm border border-red-500/20 rounded-xl p-4">
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

            <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                <div class="p-6">
                    <!-- Search and Filter Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('receptionist.check-out') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300">Cari</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nama, Email, atau No. Telp">
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-300">Tanggal Check-out</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Status</option>
                                    <option value="not_checked_out" {{ request('status') === 'not_checked_out' ? 'selected' : '' }}>Belum Check Out</option>
                                    <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>Sudah Check Out</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Filter
                                </button>
                                @if(request()->hasAny(['search', 'date', 'status']))
                                    <a href="{{ route('receptionist.check-out') }}" 
                                        class="ml-2 inline-flex justify-center rounded-md border border-gray-600 bg-transparent py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Booking ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Nama Tamu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Kamar
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Check-in
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Check-out
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            #{{ $booking->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $booking->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            @foreach($booking->rooms as $room)
                                                {{ $room->room_number }}@if(!$loop->last), @endif
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $booking->check_in_date->format('d M Y') }}
                                            <br>
                                            <span class="text-xs text-gray-400">
                                                {{ $booking->checked_in_at ? $booking->checked_in_at->format('H:i') : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $booking->check_out_date->format('d M Y') }}
                                            @if($booking->checked_out_at)
                                                <br>
                                                <span class="text-xs text-gray-400">
                                                    {{ $booking->checked_out_at->format('H:i') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($booking->checked_out_at)
                                                <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-500/10 border border-blue-500/20">
                                                    <span class="text-blue-400">Sudah Check Out</span>
                                                    <span class="text-xs text-gray-400 ml-2">{{ $booking->checked_out_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @elseif($booking->checked_in_at)
                                                <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-500/10 border border-green-500/20">
                                                    <span class="text-green-400">Checked In</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($booking->checked_in_at && !$booking->checked_out_at)
                                                <form action="{{ route('receptionist.check-out.process', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                        class="text-blue-400 hover:text-blue-300 bg-blue-500/10 px-3 py-1.5 rounded-lg border border-blue-500/20">
                                                        Check-out
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                            Tidak ada tamu yang perlu check-out.
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