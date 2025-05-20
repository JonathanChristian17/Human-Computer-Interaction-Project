<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('receptionist.bookings') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Nama, Email, atau No. Telp">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
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

            <!-- Bookings Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID Booking
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tamu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kamar
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Check In
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Check Out
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Pembayaran
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            #{{ $booking->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $booking->user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @foreach($booking->rooms as $room)
                                                {{ $room->room_number }}<br>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $booking->check_in_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $booking->check_out_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900' : '' }}
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : '' }}
                                                {{ $booking->status === 'checked_in' ? 'bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900' : '' }}
                                                {{ $booking->status === 'checked_out' ? 'bg-purple-100 text-purple-800 dark:bg-purple-200 dark:text-purple-900' : '' }}
                                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900' : '' }}">
                                                {{ ucfirst($booking->status) }}
                                                @if($booking->checked_in_at && $booking->status === 'checked_in')
                                                    <br><span class="text-xs">{{ $booking->checked_in_at->format('d/m/Y H:i') }}</span>
                                                @endif
                                                @if($booking->checked_out_at && $booking->status === 'checked_out')
                                                    <br><span class="text-xs">{{ $booking->checked_out_at->format('d/m/Y H:i') }}</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900' : '' }}
                                                {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : '' }}
                                                {{ $booking->payment_status === 'refunded' ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900' : '' }}">
                                                {{ ucfirst($booking->payment_status ?? 'Pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <!-- Update Status -->
                                                <form method="POST" action="{{ route('receptionist.bookings.status', $booking) }}" class="inline status-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" 
                                                        class="text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 status-select"
                                                        onchange="updateStatus(this)" data-current-status="{{ $booking->status }}">
                                                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Konfirmasi</option>
                                                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                                    </select>
                                                </form>

                                                <!-- Update Payment Status -->
                                                <form method="POST" action="{{ route('receptionist.bookings.payment', $booking) }}" class="inline payment-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="payment_status" 
                                                        class="text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 payment-select"
                                                        onchange="updatePaymentStatus(this)" data-current-payment-status="{{ $booking->payment_status }}">
                                                        <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                                        <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>Refund</option>
                                                    </select>
                                                </form>

                                                <!-- Generate Invoice -->
                                                <a href="{{ route('receptionist.bookings.invoice', $booking) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                    target="_blank">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Tidak ada pemesanan yang ditemukan.
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

    <!-- JavaScript for form submission -->
    <script>
        function updateStatus(selectElement) {
            const form = selectElement.closest('form');
            const newStatus = selectElement.value;
            const oldStatus = selectElement.getAttribute('data-current-status');
            
            if (newStatus === oldStatus) return;
            
            let message;
            switch (newStatus) {
                case 'checked_in':
                    message = 'Apakah Anda yakin ingin melakukan check-in untuk tamu ini?';
                    break;
                case 'checked_out':
                    message = 'Apakah Anda yakin ingin melakukan check-out untuk tamu ini?';
                    break;
                case 'cancelled':
                    message = 'Pesanan akan dibatalkan. Lanjutkan?';
                    break;
                default:
                    message = 'Apakah Anda yakin ingin mengubah status menjadi "' + newStatus + '"?';
            }
            
            if (confirm(message)) {
                console.log('Submitting status form:', {
                    action: form.action,
                    method: form.method,
                    newStatus: newStatus,
                    oldStatus: oldStatus
                });
                form.submit();
            } else {
                selectElement.value = oldStatus; // Reset to old value if cancelled
            }
        }

        function updatePaymentStatus(selectElement) {
            const form = selectElement.closest('form');
            const newStatus = selectElement.value;
            const oldStatus = selectElement.getAttribute('data-current-payment-status');
            
            if (newStatus === oldStatus) return;
            
            if (confirm('Apakah Anda yakin ingin mengubah status pembayaran menjadi "' + newStatus + '"?')) {
                console.log('Submitting payment status form:', {
                    action: form.action,
                    method: form.method,
                    newStatus: newStatus,
                    oldStatus: oldStatus
                });
                form.submit();
            } else {
                selectElement.value = oldStatus; // Reset to old value if cancelled
            }
        }

        // Add event listeners to catch form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const statusForms = document.querySelectorAll('.status-form');
            const paymentForms = document.querySelectorAll('.payment-form');

            statusForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    console.log('Status form submitting:', {
                        action: this.action,
                        method: this.method,
                        formData: new FormData(this)
                    });
                });
            });

            paymentForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    console.log('Payment form submitting:', {
                        action: this.action,
                        method: this.method,
                        formData: new FormData(this)
                    });
                });
            });
        });
    </script>
</x-receptionist-layout>