@if($transactions->isEmpty())
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
        <p class="mt-1 text-sm text-gray-500">You haven't made any transactions yet.</p>
        <div class="mt-6">
            <button onclick="window.showRooms()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Book a Room
            </button>
        </div>
    </div>
@else
    <div class="space-y-4">
        @foreach($transactions as $transaction)
            <div class="border rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold">Room {{ optional($transaction->room())->number ?? 'N/A' }}</h3>
                        <p class="text-gray-600">{{ $transaction->check_in_date ? $transaction->check_in_date->format('d M Y') : 'N/A' }} - {{ $transaction->check_out_date ? $transaction->check_out_date->format('d M Y') : 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $transaction->nights }} night(s)</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $transaction->full_name }}</p>
                    </div>
                    <p class="text-lg font-bold">IDR {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($transaction->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($transaction->status ?? 'Unknown') }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($transaction->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($transaction->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($transaction->payment_status === 'refunded') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($transaction->payment_status ?? 'Unknown') }}
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        @if($transaction->payment_status === 'pending')
                            <button onclick="window.transactionPanel.payTransaction({{ $transaction->id }})" 
                                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                Pay Now
                            </button>
                        @endif
                        @if(in_array($transaction->status, ['pending', 'confirmed']) && $transaction->payment_status !== 'paid')
                            <button onclick="window.transactionPanel.cancelTransaction({{ $transaction->id }})"
                                    class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition">
                                Cancel
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif 