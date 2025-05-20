<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('receptionist.rooms') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Nomor Kamar">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Terisi</option>
                                <option value="cleaning" {{ request('status') === 'cleaning' ? 'selected' : '' }}>Dibersihkan</option>
                                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Rooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($rooms as $room)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        Kamar {{ $room->room_number }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $room->type }} - {{ number_format($room->price_per_night, 0, ',', '.') }} / malam
                                    </p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $room->status === 'occupied' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $room->status === 'cleaning' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $room->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </div>

                            <div class="mt-4">
                                <form method="POST" action="{{ route('receptionist.rooms.status', $room) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center space-x-2">
                                        <select name="status" onchange="this.form.submit()"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Terisi</option>
                                            <option value="cleaning" {{ $room->status === 'cleaning' ? 'selected' : '' }}>Dibersihkan</option>
                                            <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            @if($room->currentBooking)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tamu Saat Ini</h4>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ optional($room->bookings->first())->full_name }}<br>
                                        Check-in: {{ optional($room->bookings->first())->check_in_date?->format('d/m/Y') }}<br>
                                        Check-out: {{ optional($room->bookings->first())->check_out_date?->format('d/m/Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-4 text-gray-500 dark:text-gray-400">
                        Tidak ada kamar yang ditemukan.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</x-receptionist-layout> 