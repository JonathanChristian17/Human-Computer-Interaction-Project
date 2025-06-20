<?php

use Carbon\Carbon;
?>

<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#252525] rounded-xl shadow-xl overflow-hidden border border-[#FFA040]">
                <div class="p-6">
                    <!-- Search and Filter Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('receptionist.transactions') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white placeholder-gray-400 focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Name, Email, Phone, Order ID, or Transaction ID">
                            </div>
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-300">Payment Status</label>
                                <select name="payment_status" id="payment_status" 
                                    class="mt-1 block w-full rounded-lg bg-gray-700/50 border border-gray-600/50 text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="expired" {{ request('payment_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <table class="min-w-full divide-y divide-[#FFA040] bg-[#1D1D1D]">
                            <thead class="bg-[#FFA040]">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider border-b border-[#FFA040]">
                                        Guest
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider border-b border-[#FFA040]">
                                        Room
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider border-b border-[#FFA040]">
                                        Payment Details
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider border-b border-[#FFA040]">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider border-b border-[#FFA040]">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#FFA040]">
                                @forelse($bookings as $booking)
                                    @php
                                        $transaction = $booking->transactions->sortByDesc('created_at')->first();
                                    @endphp
                                    <tr class="hover:bg-[#FFA040]/10 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            <div class="text-sm text-white">{{ $booking->full_name }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->email }}</div>
                                            <div class="text-sm text-gray-400">{{ $booking->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            @foreach($booking->rooms as $room)
                                                <div class="text-sm text-white">Room {{ $room->room_number }}</div>
                                                <div class="text-sm text-gray-400">{{ $room->type }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 text-white">
                                            <div class="text-sm text-white">Rp {{ number_format($transaction->gross_amount ?? $booking->total_price, 0, ',', '.') }}</div>
                                            <div class="text-sm text-gray-400">Order #{{ $transaction->order_id }}</div>
                                            <div class="text-sm text-gray-400">Trans ID: {{ $transaction->transaction_id }}</div>
                                            @if($transaction && $transaction->payment_type)
                                                <div class="mt-1 px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/50">
                                                    {{ $transaction->payment_type === 'bank_transfer' ? 'BCA Virtual Account' : ucfirst($transaction->payment_type) }}
                                                    @if($transaction->payment_code)
                                                        {{ $transaction->payment_code }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction)
                                                @php
                                                    $deadline = $transaction->payment_deadline;
                                                    $now = now();
                                                    $remainingSeconds = $deadline ? $now->diffInSeconds($deadline, false) : 0;
                                                    $remainingMinutes = floor($remainingSeconds / 60);
                                                    $remainingSecondsDisplay = $remainingSeconds % 60;
                                                    
                                                    $status = match($transaction->transaction_status) {
                                                        'settlement', 'capture' => 'paid',
                                                        'pending' => ($deadline && $remainingSeconds <= 0) ? 'expired' : 'pending',
                                                        'expire' => 'expired',
                                                        'cancel', 'deny' => 'cancelled',
                                                        default => $transaction->transaction_status
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg
                                                    {{ $status === 'paid' ? 'bg-green-500/10 text-green-400 border border-green-500/50' : '' }}
                                                    {{ $status === 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/50' : '' }}
                                                    {{ $status === 'expired' ? 'bg-red-500/10 text-red-400 border border-red-500/50' : '' }}
                                                    {{ $status === 'cancelled' ? 'bg-gray-500/10 text-red-400 border border-red-500/50' : '' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                                @if($status === 'pending' && $deadline)
                                                    <div class="mt-1 text-sm text-gray-400" data-countdown="{{ $remainingSeconds }}" data-deadline="{{ $deadline }}">
                                                        Expires in: {{ $remainingMinutes }}m {{ $remainingSecondsDisplay }}s
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $transaction ? ($transaction->transaction_time ?? $transaction->created_at->format('d M Y H:i')) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                            No transactions found.
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update all countdowns every second
        setInterval(function() {
            document.querySelectorAll('[data-countdown]').forEach(function(el) {
                let seconds = parseInt(el.dataset.countdown) - 1;
                if (seconds <= 0) {
                    // Refresh the page when any timer expires
                    window.location.reload();
                    return;
                }
                
                el.dataset.countdown = seconds;
                let minutes = Math.floor(seconds / 60);
                let remainingSeconds = seconds % 60;
                el.textContent = `Expires in: ${minutes}m ${remainingSeconds}s`;
            });
        }, 1000);
    });
</script>
@endpush

<style>
.pagination nav {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}
.pagination nav > div {
    width: 100%;
}
.pagination nav ul {
    display: flex;
    justify-content: center;
    gap: 4px;
    padding: 0;
}
.pagination nav li {
    list-style: none;
}
.pagination nav a,
.pagination nav span {
    background: #232323 !important;
    color: #fff !important;
    border: 1px solid #FFA040 !important;
    border-radius: 6px !important;
    padding: 8px 14px !important;
    margin: 0 2px;
    min-width: 36px;
    min-height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}
.pagination nav a:hover {
    background: #FFA040 !important;
    color: #232323 !important;
}
.pagination nav .active span {
    background: #FFA040 !important;
    color: #232323 !important;
}
</style> 