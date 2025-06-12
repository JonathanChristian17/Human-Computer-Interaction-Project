<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="relative overflow-hidden shadow-sm sm:rounded-lg mb-6" style="background:#2D2D2D;">
                <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                <div class="p-6">
                    <form method="GET" action="{{ route('receptionist.transactions') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-[#FFD740] bg-[#232323] text-white focus:border-[#FFD740] focus:ring-[#FFD740]"
                                placeholder="Name, Email, or Phone">
                        </div>
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                            <select name="payment_status" id="payment_status"
                                class="text-sm rounded-md border-[#FFD740] bg-[#232323] text-white focus:border-[#FFD740] focus:ring-[#FFD740] status-select">
                                <option value="">All</option>
                                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead style="background:#252525;">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Guest
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Room
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Payment Details
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background:#2D2D2D;color:#fff;" class="bg-[#232323] divide-y divide-[#333]">
                                @forelse($bookings as $booking)
                                    <tr class="hover:bg-[#252525] transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-white">{{ $booking->full_name }}</div>
                                                <div class="text-sm text-gray-400">{{ $booking->email }}</div>
                                                <div class="text-sm text-gray-400">{{ $booking->phone }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">
                                                @foreach($booking->rooms as $room)
                                                    Room {{ $room->number }}<br>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col space-y-2">
                                                <div class="text-sm font-medium text-white">
                                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                </div>
                                                @if($booking->transaction)
                                                    <div class="text-xs text-gray-400">
                                                        Order #{{ $booking->transaction->order_id }}
                                                    </div>
                                                    @if($booking->transaction->transaction_id)
                                                        <div class="text-xs text-gray-400">
                                                            Trans ID: {{ $booking->transaction->transaction_id }}
                                                        </div>
                                                    @endif
                                                    @if($booking->transaction->payment_type)
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                            {{ $booking->transaction->payment_type }}
                                                            @if($booking->transaction->payment_code)
                                                                <span class="ml-1 font-mono">{{ $booking->transaction->payment_code }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col space-y-2">
                                                @if($booking->transaction)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($booking->transaction->transaction_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                        @elseif(in_array($booking->transaction->transaction_status, ['settlement', 'capture', 'success'])) bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100
                                                        @elseif(in_array($booking->transaction->transaction_status, ['cancel', 'deny', 'expire'])) bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                                        @endif">
                                                        {{ ucfirst($booking->transaction->transaction_status) }}
                                                    </span>
                                                    @if($booking->transaction->payment_status !== $booking->transaction->transaction_status)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($booking->transaction->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                            @elseif($booking->transaction->payment_status === 'paid') bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100
                                                            @elseif($booking->transaction->payment_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                            @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                                            @endif">
                                                            Payment: {{ ucfirst($booking->transaction->payment_status) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                                        No Transaction
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                            {{ $booking->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400">
                                            No transactions found
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