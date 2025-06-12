<x-receptionist-layout>
    <style>
        /* Custom Scrollbar Styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }

        /* Hide scrollbar when not hovering */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) rgba(55, 65, 81, 0.3);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Kelola Pemesanan') }}
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
                        <form method="GET" action="{{ route('receptionist.bookings') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300">Cari</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white placeholder-gray-400 focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Nama, Email, atau No. Telp">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                    <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
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
                                        ID Booking
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Tamu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Kamar
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Check In
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Check Out
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Pembayaran
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Detail
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
                                        <td class="px-6 py-4 text-sm text-white relative">
                                            <div class="max-h-[85px] overflow-y-auto custom-scrollbar">
                                                <div class="flex flex-col space-y-1.5 pr-2">
                                                    @foreach($booking->rooms as $room)
                                                        <div class="bg-gray-700/50 px-3 py-1.5 rounded-md border border-gray-600/30">
                                                            <div class="text-gray-100">Room {{ $room->room_number }}</div>
                                                            <div class="text-gray-400 text-xs">{{ $room->type }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                            {{ $booking->check_in_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                            {{ $booking->check_out_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(!in_array($booking->status, ['checked_in', 'checked_out']))
                                                <form id="statusForm{{ $booking->id }}" action="{{ route('receptionist.bookings.update-status', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="confirmStatusChange(this, {{ $booking->id }})"
                                                        class="text-sm rounded-lg bg-transparent
                                                        {{ $booking->status === 'pending' ? 'text-amber-400 border-amber-500/50' : '' }}
                                                        {{ $booking->status === 'confirmed' ? 'text-green-400 border-green-500/50' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'text-red-400 border-red-500/50' : '' }}
                                                        {{ $booking->status === 'expired' ? 'text-red-400 border-red-500/50' : '' }}">
                                                        <option value="{{ $booking->status }}" selected>{{ ucfirst($booking->status) }}</option>
                                                        @if($booking->status !== 'cancelled')
                                                            <option value="cancelled">Cancel Booking</option>
                                                        @endif
                                                    </select>
                                                </form>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg
                                                    {{ $booking->status === 'checked_in' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/50' : '' }}
                                                    {{ $booking->status === 'checked_out' ? 'bg-gray-500/10 text-gray-400 border border-gray-500/50' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($booking->payment_status === 'deposit')
                                                <form action="{{ route('receptionist.bookings.update-payment', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="payment_status" value="paid"
                                                        class="text-amber-400 hover:text-amber-300 bg-amber-500/10 px-3 py-1.5 rounded-lg border border-amber-500/20">
                                                        Deposit → Lunas
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg
                                                    {{ $booking->payment_status === 'paid' ? 'bg-green-500/10 text-green-400 border border-green-500/50' : '' }}
                                                    {{ $booking->payment_status === 'expired' ? 'bg-red-500/10 text-red-400 border border-red-500/50' : '' }}
                                                    {{ $booking->payment_status === 'cancelled' ? 'bg-red-500/10 text-red-400 border border-red-500/50' : '' }}
                                                    {{ $booking->payment_status === 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/50' : '' }}">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('receptionist.bookings.invoice', $booking) }}" 
                                                class="text-amber-400 hover:text-amber-300">
                                                Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-400">
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

                    <!-- Add SweetAlert2 CDN -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    <!-- Add JavaScript for confirmation dialog -->
                    <script>
                        function confirmStatusChange(selectElement, bookingId) {
                            const newStatus = selectElement.value;
                            const currentStatus = selectElement.querySelector('option[selected]').value;
                            
                            if (newStatus === 'cancelled') {
                                Swal.fire({
                                    title: 'Konfirmasi Pembatalan',
                                    text: 'Apakah Anda yakin ingin membatalkan booking ini?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Ya, batalkan',
                                    cancelButtonText: 'Tidak'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            title: 'Konfirmasi Terakhir',
                                            text: 'Konfirmasi sekali lagi: Anda yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Ya, saya yakin',
                                            cancelButtonText: 'Tidak'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('statusForm' + bookingId).submit();
                                            } else {
                                                selectElement.value = currentStatus;
                                            }
                                        });
                                    } else {
                                        selectElement.value = currentStatus;
                                    }
                                });
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-receptionist-layout> 