<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Kelola Kamar') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filter -->
        <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl p-4">
            <form action="{{ route('receptionist.rooms') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor Kamar" 
                        class="w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div class="md:w-64">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                </div>
                <div class="md:flex md:items-end">
                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Room Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
                @php
                    $activeBooking = $room->bookings()
                        ->whereIn('status', ['confirmed', 'checked_in'])
                        ->with('user')
                        ->orderBy('check_in_date', 'asc')
                        ->first();
                    $isCheckedIn = $activeBooking && $activeBooking->status === 'checked_in';
                @endphp
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <!-- Room Header -->
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-white">Kamar {{ $room->room_number }}</h3>
                                <p class="text-sm text-gray-400">{{ $room->type }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($room->status === 'available') bg-green-500/10 text-green-400
                                @else bg-red-500/10 text-red-400 @endif">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>

                        <!-- Room Details -->
                        <div class="mt-4">
                            <p class="text-sm text-gray-400">{{ $room->description }}</p>
                            <p class="mt-2 text-sm text-gray-400">Kapasitas: {{ $room->capacity }} orang</p>
                            <p class="text-sm text-gray-400">Harga: Rp{{ number_format($room->price_per_night, 0, ',', '.') }}/malam</p>
                        </div>

                        <!-- Current Guest Info -->
                        @if($activeBooking)
                            <div class="mt-4 p-4 bg-gray-700/50 rounded-lg border border-gray-600/50">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-sm font-semibold text-white">Informasi Tamu</h4>
                                    <span class="px-2 py-0.5 text-xs rounded-full 
                                        @if($isCheckedIn) bg-green-500/10 text-green-400 @else bg-blue-500/10 text-blue-400 @endif">
                                        {{ $isCheckedIn ? 'Sedang Menginap' : 'Terkonfirmasi' }}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-400">Nama:</span>
                                        <span class="text-sm text-white">{{ $activeBooking->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-400">Check-in:</span>
                                        <span class="text-sm text-white">{{ $activeBooking->check_in_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-400">Check-out:</span>
                                        <span class="text-sm text-white">{{ $activeBooking->check_out_date->format('d M Y') }}</span>
                                    </div>
                                    @if($activeBooking->user->phone)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-400">Telepon:</span>
                                            <span class="text-sm text-white">{{ $activeBooking->user->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Status Update Form -->
                        <div class="mt-6">
                            <form action="{{ route('receptionist.rooms.status', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex space-x-2">
                                    <select name="status" 
                                            class="flex-1 rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500 disabled:opacity-50" 
                                            @if($isCheckedIn) disabled @endif>
                                        <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                                    </select>
                                    <button type="submit" 
                                            class="px-4 py-2 rounded-lg text-white transition-colors duration-200
                                                @if($isCheckedIn)
                                                    bg-gray-500 cursor-not-allowed
                                                @else
                                                    bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900
                                                @endif"
                                            @if($isCheckedIn) disabled @endif>
                                        Update
                                    </button>
                                </div>
                                @if($isCheckedIn)
                                    <p class="mt-2 text-xs text-amber-400">Status tidak dapat diubah saat tamu sedang menginap</p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $rooms->links() }}
        </div>
    </div>
</x-receptionist-layout> 