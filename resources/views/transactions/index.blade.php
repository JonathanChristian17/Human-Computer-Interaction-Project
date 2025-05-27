@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <button onclick="window.history.back()" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </button>
        <h1 class="text-3xl font-bold text-gray-800 ml-4">Transaction History</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        @if($transactions->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't made any transactions yet.</p>
                <div class="mt-6">
                    <a href="{{ route('rooms') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Book a Room
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold">Room {{ $transaction->room_number }}</h3>
                                <p class="text-gray-600">{{ $transaction->check_in }} - {{ $transaction->check_out }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $transaction->nights }} night(s)</p>
                            </div>
                            <p class="text-lg font-bold">IDR {{ number_format($transaction->total, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($transaction->status === 'Unpaid') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status === 'Paid') bg-green-100 text-green-800
                                @elseif($transaction->status === 'Refund') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $transaction->status }}
                            </span>
                            <div class="flex space-x-2">
                                @if($transaction->status === 'Unpaid')
                                    <button onclick="payTransaction({{ $transaction->id }})" 
                                            class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                        Pay Now
                                    </button>
                                @endif
                                @if(in_array($transaction->status, ['Unpaid', 'Paid']))
                                    <button onclick="cancelTransaction({{ $transaction->id }})"
                                            class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition">
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
async function payTransaction(id) {
    try {
        const response = await fetch(`/transactions/${id}/pay`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) throw new Error('Failed to process payment');

        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to process payment. Please try again.'
        });
    }
}

async function cancelTransaction(id) {
    try {
        const result = await Swal.fire({
            title: 'Cancel Transaction?',
            text: 'Are you sure you want to cancel this transaction?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel it',
            cancelButtonText: 'No, keep it'
        });

        if (result.isConfirmed) {
            const response = await fetch(`/transactions/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Failed to cancel transaction');

            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to cancel transaction. Please try again.'
        });
    }
}
</script>
@endpush 