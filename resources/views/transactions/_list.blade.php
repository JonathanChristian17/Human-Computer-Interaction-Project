@if($transactions->isEmpty())
    <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg">
        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">No Transactions Yet</h3>
        <p class="mt-1 text-sm text-gray-500">Start by booking a room to see your transactions here.</p>
        <button onclick="hidePanel(); showRooms();" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Book a Room
        </button>
    </div>
@else
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Order Details</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Booking Period</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider">Actions</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                        <tr class="@if($loop->even) bg-gray-50 @else bg-white @endif hover:bg-orange-50/50 transition-colors duration-150">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold text-gray-900">Order #{{ $transaction->order_id }}</div>
                                    @if($transaction->transaction_id)
                                        <div class="text-xs text-gray-500 mt-1">Transaction ID: {{ $transaction->transaction_id }}</div>
                                    @endif
                                    <div class="text-sm text-gray-500 mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                                    @if($transaction->payment_type)
                                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $transaction->payment_type }}
                                            @if($transaction->payment_code)
                                                <span class="ml-1.5 font-semibold text-blue-700">{{ $transaction->payment_code }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($transaction->booking && $transaction->booking->check_in_date && $transaction->booking->check_out_date)
                                    <div class="flex flex-col space-y-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-medium text-gray-500">Check-in</span>
                                                <span class="text-sm font-medium text-gray-900 mt-1">{{ $transaction->booking->check_in_date->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-gray-400">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-medium text-gray-500">Check-out</span>
                                                <span class="text-sm font-medium text-gray-900 mt-1">{{ $transaction->booking->check_out_date->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col space-y-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($transaction->transaction_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif(in_array($transaction->transaction_status, ['settlement', 'capture', 'success'])) bg-emerald-100 text-emerald-800
                                        @elseif(in_array($transaction->transaction_status, ['cancel', 'deny', 'expire'])) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($transaction->transaction_status) }}
                                    </span>
                                    @if($transaction->payment_status !== $transaction->transaction_status)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($transaction->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->payment_status === 'paid') bg-emerald-100 text-emerald-800
                                            @elseif($transaction->payment_status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            Payment: {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button 
                                        @if($transaction->transaction_status === 'pending')
                                            onclick="window.transactionPanel.payTransaction({{ $transaction->id }})"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500 transition-colors"
                                        @else
                                            disabled
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-gray-300 text-white cursor-not-allowed"
                                        @endif
                                    >
                                        Pay Now
                                    </button>
                                    <button 
                                        @if(in_array($transaction->transaction_status, ['pending']))
                                            onclick="window.transactionPanel.cancelTransaction({{ $transaction->id }})"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-red-500 text-white hover:bg-red-600 transition-colors"
                                        @else
                                            disabled
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-gray-300 text-white cursor-not-allowed"
                                        @endif
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <button 
                                    onclick="window.transactionPanel.showDetails({{ $transaction->id }})"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif 