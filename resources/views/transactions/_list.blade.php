@if(empty($transactions) || count($transactions) === 0)
<div class="flex flex-col items-center justify-center py-12">
    <img src="{{ asset('images/empty-transaction.svg') }}" alt="No transactions" class="w-48 h-48 mb-6">
    <h3 class="text-xl font-medium text-gray-900 mb-2">No Transactions Yet</h3>
    <p class="text-gray-500 text-center mb-8">Ready to start your journey? Book a room and create unforgettable memories with us.</p>
    <button onclick="hidePanel(); showRooms();" class="book-room-btn inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Book a Room Now
    </button>
</div>
@else
<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider sm:px-6 sm:py-4">Order Details</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider sm:px-6 sm:py-4">Booking Period</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider sm:px-6 sm:py-4">Status</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider sm:px-6 sm:py-4">Amount</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider sm:px-6 sm:py-4">Actions</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider sm:px-6 sm:py-4">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                    <tr class="@if($loop->even) bg-gray-10 @else bg-white @endif hover:bg-orange-50/50 transition-colors duration-150">
                        <td class="px-4 py-4 sm:px-6 sm:py-5">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-900">Order #{{ $transaction['order_id'] }}</div>
                                @if($transaction['transaction_id'])
                                    <div class="text-xs text-gray-500 mt-1">Transaction ID: {{ $transaction['transaction_id'] }}</div>
                                @endif
                                <div class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($transaction['created_at'])->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</div>
                                @if($transaction['payment_type'])
                                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $transaction['payment_type'] }}
                                        @if($transaction['payment_code'])
                                            <span class="ml-1.5 font-semibold text-blue-700">{{ $transaction['payment_code'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 sm:px-6 sm:py-5">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaction['booking']['check_in_date'])->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y') }} - 
                                {{ \Carbon\Carbon::parse($transaction['booking']['check_out_date'])->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y') }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $transaction['booking']['room_number'] ? 'Room ' . $transaction['booking']['room_number'] : '' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 sm:px-6 sm:py-5">
                            <div class="flex flex-col">
                                <div>
                                    @php
                                        $statusClass = match($transaction['status']) {
                                            'Paid', 'Settlement', 'Capture' => 'bg-green-100 text-green-800',
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Cancelled', 'Expired', 'Deny' => 'bg-red-100 text-red-800',
                                            'Deposit' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $transaction['status'] }}
                                    </span>
                                    @if($transaction['status'] === 'Pending' && !$transaction['is_expired'] && $transaction['payment_deadline'])
                                        <div 
                                            x-data="{ 
                                                deadline: '{{ $transaction['payment_deadline'] }}',
                                                remaining: '',
                                                timer: null,
                                                init() {
                                                    this.updateTimer();
                                                    this.timer = setInterval(() => this.updateTimer(), 1000);
                                                },
                                                updateTimer() {
                                                    const now = new Date().getTime();
                                                    const target = new Date(this.deadline).getTime();
                                                    const distance = target - now;

                                                    if (distance <= 0) {
                                                        clearInterval(this.timer);
                                                        this.remaining = 'Expired';
                                                        
                                                        // Refresh the transaction list
                                                        if (window.transactionPanel && typeof window.transactionPanel.loadTransactions === 'function') {
                                                            window.transactionPanel.loadTransactions();
                                                        }
                                                        return;
                                                    }

                                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                    this.remaining = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                                }
                                            }"
                                            x-init="init()"
                                            class="text-xs font-medium text-gray-500 mt-1"
                                        >
                                            Time left: <span x-text="remaining"></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 sm:px-6 sm:py-5 text-right">
                            <span class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($transaction['gross_amount'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 sm:px-6 sm:py-5 text-right">
                            <div class="flex justify-end space-x-2">
                                @php
                                    $isDisabled = in_array($transaction['status'], ['Paid', 'Settlement', 'Capture', 'Deposit', 'Cancelled', 'Expired', 'Deny']);
                                    $payButtonClass = $isDisabled 
                                        ? 'bg-gray-500 cursor-not-allowed opacity-75 hover:bg-gray-500' 
                                        : 'bg-yellow-400 hover:bg-yellow-500';
                                    $cancelButtonClass = $isDisabled 
                                        ? 'bg-gray-500 cursor-not-allowed opacity-75 hover:bg-gray-500' 
                                        : 'bg-red-500 hover:bg-red-600';
                                @endphp

                                @if($transaction['status'] === 'Pending')
                                    <button 
                                        type="button"
                                        @if(!$isDisabled)
                                            @click="payTransaction({{ $transaction['id'] }})"
                                        @endif
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-white transition-colors sm:px-3 sm:py-1.5 sm:text-sm {{ $payButtonClass }}"
                                        @if($isDisabled)
                                            disabled
                                        @endif
                                    >
                                        Pay Now
                                    </button>

                                    <button 
                                        type="button"
                                        @if(!$isDisabled)
                                            @click="cancelTransaction({{ $transaction['id'] }})"
                                        @endif
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-white transition-colors sm:px-3 sm:py-1.5 sm:text-sm {{ $cancelButtonClass }}"
                                        @if($isDisabled)
                                            disabled
                                        @endif
                                    >
                                        Cancel
                                    </button>
                                @else
                                    <button 
                                        type="button"
                                        disabled
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-white bg-gray-500 cursor-not-allowed opacity-75 hover:bg-gray-500 sm:px-3 sm:py-1.5 sm:text-sm"
                                    >
                                        Pay Now
                                    </button>

                                    <button 
                                        type="button"
                                        disabled
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-white bg-gray-500 cursor-not-allowed opacity-75 hover:bg-gray-500 sm:px-3 sm:py-1.5 sm:text-sm"
                                    >
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 sm:px-6 sm:py-5 text-center">
                            <button 
                                type="button"
                                @click="showDetails({{ $transaction['id'] }})"
                                class="inline-flex items-center px-2 py-1.5 text-sm font-medium rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors sm:px-3 sm:py-1.5">
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

@push('scripts')
<script>
</script>
@endpush 