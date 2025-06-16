<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[#FFA500] leading-tight">
                {{ __('Daily Report') }} - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
            </h2>
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-[#2A2A2A] text-[#E0E0E0] rounded-lg hover:bg-[#333333] transition-colors duration-200 border border-[#333333] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#1F1F1F] overflow-hidden shadow-sm sm:rounded-lg border border-[#333333]">
                <div class="p-6 text-[#E0E0E0]">
                    <!-- Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-[#2A2A2A] border border-[#333333] p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-[#FFA500] mb-2">Total Bookings</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalBookings }}</p>
                        </div>
                        <div class="bg-[#2A2A2A] border border-[#333333] p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-[#FFA500] mb-2">Total Revenue</h3>
                            <p class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Bookings Table -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4 text-[#FFA500]">Booking Details</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-[#1F1F1F] border border-[#333333] rounded-lg">
                                <thead>
                                    <tr class="bg-[#2A2A2A]">
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Booking ID</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Customer</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Rooms</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Check In</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Check Out</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Total Price</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Status</th>
                                        <th class="py-3 px-4 text-left text-[#FFA500] border-b border-[#333333] font-semibold">Managed By</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#333333]">
                                    @forelse ($bookings as $booking)
                                        <tr class="hover:bg-[#2A2A2A] transition-colors duration-150">
                                            <td class="py-3 px-4 text-[#E0E0E0]">{{ $booking->id }}</td>
                                            <td class="py-3 px-4 text-[#E0E0E0]">{{ $booking->user->name }}</td>
                                            <td class="py-3 px-4 text-[#E0E0E0]">
                                                @foreach($booking->rooms as $room)
                                                    Room {{ $room->room_number }} ({{ $room->type }})<br>
                                                @endforeach
                                            </td>
                                            <td class="py-3 px-4 text-[#E0E0E0]">{{ $booking->check_in_date }}</td>
                                            <td class="py-3 px-4 text-[#E0E0E0]">{{ $booking->check_out_date }}</td>
                                            <td class="py-3 px-4 text-[#E0E0E0]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-3 py-1 rounded-full text-sm font-semibold"
                                                    @if($booking->status === 'confirmed') style="background-color: #16a34a; color: #fff;"
                                                    @elseif($booking->status === 'pending') style="background-color: #FFA040; color: #000;"
                                                    @elseif($booking->status === 'cancelled' || $booking->status === 'expired') style="background-color: #dc2626; color: #fff;"
                                                    @elseif($booking->status === 'checked_in' || $booking->status === 'check_in') style="background-color: #2563eb; color: #fff;"
                                                    @elseif($booking->status === 'checked_out' || $booking->status === 'check_out') style="background-color: #9E9E9E; color: #fff;"
                                                    @elseif($booking->status === 'deposit') style="background-color: #9333ea; color: #fff;"
                                                    @endif>
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-[#E0E0E0]">{{ $booking->receptionist->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="py-6 px-4 text-center text-gray-400">
                                                No bookings found for this day
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            @if($bookings->hasPages())
                                <div class="mt-4 px-4">
                                    <div class="flex items-center justify-between">
                                        <!-- Previous Page -->
                                        @if($bookings->onFirstPage())
                                            <span class="px-4 py-2 text-[#E0E0E0] bg-[#2A2A2A] border border-[#333333] rounded-lg opacity-50 cursor-not-allowed">
                                                Previous
                                            </span>
                                        @else
                                            <a href="{{ $bookings->previousPageUrl() }}" 
                                               class="px-4 py-2 text-[#E0E0E0] bg-[#2A2A2A] border border-[#333333] rounded-lg hover:bg-[#333333] transition-colors duration-200">
                                                Previous
                                            </a>
                                        @endif

                                        <!-- Page Numbers -->
                                        <div class="flex items-center">
                                            @foreach($bookings->getUrlRange(max($bookings->currentPage() - 2, 1), min($bookings->currentPage() + 2, $bookings->lastPage())) as $page => $url)
                                                @if($page == $bookings->currentPage())
                                                    <span class="mx-1 px-4 py-2 text-white bg-[#FFA500] rounded-lg">
                                                        {{ $page }}
                                                    </span>
                                                @else
                                                    <a href="{{ $url }}" 
                                                       class="mx-1 px-4 py-2 text-[#E0E0E0] bg-[#2A2A2A] border border-[#333333] rounded-lg hover:bg-[#333333] transition-colors duration-200">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- Next Page -->
                                        @if($bookings->hasMorePages())
                                            <a href="{{ $bookings->nextPageUrl() }}" 
                                               class="px-4 py-2 text-[#E0E0E0] bg-[#2A2A2A] border border-[#333333] rounded-lg hover:bg-[#333333] transition-colors duration-200">
                                                Next
                                            </a>
                                        @else
                                            <span class="px-4 py-2 text-[#E0E0E0] bg-[#2A2A2A] border border-[#333333] rounded-lg opacity-50 cursor-not-allowed">
                                                Next
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Export Button -->
                    <div class="mt-8">
                        <a href="{{ route('admin.reports.export.pdf', ['type' => 'daily', 'date' => $date]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#FFA500] text-white rounded-lg hover:bg-[#ff8c1a] transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export to PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 