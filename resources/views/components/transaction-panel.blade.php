<!-- Transaction Panel -->
<div x-data="{ open: false }" @keydown.window.escape="open = false">
    <div x-show="open" class="relative z-50">
        <!-- Background backdrop -->
        <div x-show="open" 
            x-transition:enter="ease-in-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="open"
                        x-transition:enter="transform transition ease-in-out duration-500"
                        x-transition:enter-start="translate-y-full"
                        x-transition:enter-end="translate-y-0"
                        x-transition:leave="transform transition ease-in-out duration-500"
                        x-transition:leave-start="translate-y-0"
                        x-transition:leave-end="translate-y-full"
                        class="pointer-events-auto w-screen max-w-4xl">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900">Transaction History</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" class="-m-2 p-2 text-gray-400 hover:text-gray-500" @click="open = false">
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flow-root">
                                        <div class="divide-y divide-gray-200">
                                            <!-- Empty State -->
                                            @if(empty($transactions) || count($transactions) === 0)
                                            <div class="flex flex-col items-center justify-center py-12">
                                                <img src="{{ asset('images/empty-transaction.svg') }}" alt="No transactions" class="w-48 h-48 mb-6">
                                                <h3 class="text-xl font-medium text-gray-900 mb-2">No Transactions Yet</h3>
                                                <p class="text-gray-500 text-center mb-8">Ready to start your journey? Book a room and create unforgettable memories with us.</p>
                                                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Book a Room Now
                                                </a>
                                            </div>
                                            @else
                                            <!-- Transaction Items -->
                                            @foreach($transactions as $transaction)
                                            <div class="flex py-6">
                                                <div class="flex-1 space-y-1">
                                                    <div class="flex items-center justify-between">
                                                        <h3 class="text-sm font-medium text-gray-900">Room {{ $transaction->room_number }}</h3>
                                                        <p class="text-sm font-medium text-gray-900">IDR {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <span>{{ $transaction->check_in }} - {{ $transaction->check_out }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                            @if($transaction->status === 'Unpaid') bg-yellow-100 text-yellow-800
                                                            @elseif($transaction->status === 'Paid') bg-green-100 text-green-800
                                                            @elseif($transaction->status === 'Refund') bg-blue-100 text-blue-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ $transaction->status }}
                                                        </span>
                                                        <div class="flex space-x-2">
                                                            @if($transaction->status === 'Unpaid')
                                                            <button class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Pay</button>
                                                            @endif
                                                            <button class="text-sm font-medium text-red-600 hover:text-red-500">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 