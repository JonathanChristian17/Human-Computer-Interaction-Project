<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Kelola Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="relative overflow-hidden shadow-sm sm:rounded-lg mb-6" style="background:#2D2D2D;">
                <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                <div class="p-6">
                    <form action="{{ route('receptionist.rooms') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor Kamar" 
                                class="w-full rounded-lg bg-[#2D2D2D] border border-[#bbb] text-white focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div class="md:w-64">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg bg-[#2D2D2D] border border-[#bbb] text-white focus:ring-amber-500 focus:border-amber-500">
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
                    <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                        <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
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
                                <div class="mt-4 p-4" style="background:#1D1D1D; border-radius:16px; border:1.5px solid #bbb; color:#fff;">
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
                                                class="form-input bg-[#2D2D2D] border border-[#bbb] rounded-xl text-white placeholder-[#bbb] focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-base disabled:opacity-50 disabled:cursor-not-allowed" 
                                                @if($isCheckedIn) disabled @endif
                                                data-current-status="{{ $room->status }}">
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
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add event listener to all status update forms
        document.querySelectorAll('.status-update-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                // Check if form is disabled
                if (form.querySelector('select[name="status"]').disabled) {
                    return;
                }

                const select = form.querySelector('select[name="status"]');
                const currentStatus = select.getAttribute('data-current-status') || 'available';
                const newStatus = select.value;

                // Only show confirmation if status is actually changing
                if (currentStatus !== newStatus) {
                    const roomNumber = form.closest('.relative').querySelector('h3').textContent;
                    const action = newStatus === 'maintenance' ? 'perbaikan' : 'tersedia';
                    
                    const result = await Swal.fire({
                        title: 'Konfirmasi Perubahan Status',
                        text: `Apakah Anda yakin ingin mengubah status ${roomNumber} menjadi ${action}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#FFA040',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Ubah Status',
                        cancelButtonText: 'Batal'
                    });

                    if (!result.isConfirmed) {
                        return;
                    }

                    // Show loading state
                    Swal.fire({
                        title: 'Memperbarui Status',
                        text: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
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
                            status: select.value
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Berhasil Diperbarui',
                        text: `Status kamar telah berhasil diubah menjadi ${select.value === 'maintenance' ? 'perbaikan' : 'tersedia'}`,
                        confirmButtonColor: '#FFA040'
                    }).then(() => {
                        // Reload the page to show updated status
                        window.location.reload();
                    });
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memperbarui Status',
                        text: 'Terjadi kesalahan saat memperbarui status kamar. Silakan coba lagi.',
                        confirmButtonColor: '#FFA040'
                    });
                }
            });
        });
    </script>
    @endpush
</x-receptionist-layout> 