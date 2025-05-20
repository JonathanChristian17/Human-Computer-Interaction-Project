<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Transactions List -->
        <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Transaction ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Guest</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($transactions as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">#{{ $booking->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">{{ $booking->full_name }}</div>
                                        <div class="text-sm text-gray-400">{{ $booking->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">Room {{ $booking->room->room_number }}</div>
                                        <div class="text-sm text-gray-400">{{ $booking->room->type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($booking->payment_status === 'paid') bg-green-500/10 text-green-400
                                            @elseif($booking->payment_status === 'pending') bg-amber-500/10 text-amber-400
                                            @else bg-red-500/10 text-red-400 @endif">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">{{ $booking->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-400">{{ $booking->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @if($booking->payment_status !== 'paid')
                                            <form action="{{ route('receptionist.bookings.payment', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="payment_status" value="paid">
                                                <button type="submit" class="text-green-400 hover:text-green-300">Mark as Paid</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('receptionist.bookings.invoice', $booking) }}" class="text-gray-400 hover:text-white" target="_blank">View Invoice</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-receptionist-layout> 