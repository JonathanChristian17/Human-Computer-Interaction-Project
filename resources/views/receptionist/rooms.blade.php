@php
    $today = now()->startOfDay();
@endphp

<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Kamar') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-white mb-8">Kelola Kamar</h2>

            <!-- Search and Filter -->
            <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl p-4 mb-6">
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
                            ->where('check_in_date', '<=', $today)
                            ->where('check_out_date', '>', $today)
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
                                <form action="{{ route('receptionist.rooms.status', $room) }}" method="POST" class="status-update-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex gap-2">
                                        <select name="status" 
                                                class="flex-1 rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500 disabled:opacity-50 disabled:cursor-not-allowed" 
                                                @if($isCheckedIn) disabled @endif>
                                            <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                                        <p class="mt-2 text-xs text-amber-400">* Kamar yang sedang digunakan tidak bisa diubah statusnya</p>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <div class="flex justify-center">
                    @if($rooms->hasPages())
                        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                            {{-- Previous Page Link --}}
                            @if($rooms->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-gray-800/70 border border-gray-600/50 cursor-default rounded-md">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $rooms->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800/70 border border-gray-600/50 rounded-md hover:bg-gray-700/70">
                                    Previous
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                                <div>
                                    <span class="relative z-0 inline-flex">
                                        @for($i = 1; $i <= $rooms->lastPage(); $i++)
                                            <a href="{{ $rooms->url($i) }}" 
                                               class="{{ $rooms->currentPage() == $i ? 'bg-amber-500 text-white' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }} relative inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-600/50 mx-1 rounded-md">
                                                {{ $i }}
                                            </a>
                                        @endfor
                                    </span>
                                </div>
                            </div>

                            {{-- Next Page Link --}}
                            @if($rooms->hasMorePages())
                                <a href="{{ $rooms->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800/70 border border-gray-600/50 rounded-md hover:bg-gray-700/70">
                                    Next
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-gray-800/70 border border-gray-600/50 cursor-default rounded-md">
                                    Next
                                </span>
                            @endif
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add event listener to all status update forms
        document.querySelectorAll('.status-update-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                // Check if form is disabled
                if (form.querySelector('select[name="status"]').disabled) {
                    return;
                }
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            _method: 'PATCH',
                            status: form.querySelector('select[name="status"]').value
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Reload the page to show updated status
                    window.location.reload();
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to update room status. Please try again.');
                }
            });
        });
    </script>
    @endpush
</x-receptionist-layout> 