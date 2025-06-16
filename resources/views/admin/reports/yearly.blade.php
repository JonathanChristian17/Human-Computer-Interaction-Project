<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[#FFA040] leading-tight">
                {{ __('Yearly Report') }} - {{ $year }}
            </h2>
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-[#1D1D1D] border border-[#FFA040] text-white rounded-md hover:bg-[#FFA040] hover:text-black transition-colors duration-200">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="bg-[#1D1D1D] border border-[#FFA040] overflow-hidden shadow-sm sm:rounded-lg text-white">
        <div class="p-6 text-white">
            <!-- Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-[#1D1D1D] border border-[#FFA040] p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Total Bookings</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalBookings }}</p>
                </div>
                <div class="bg-[#1D1D1D] border border-[#FFA040] p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Total Revenue</h3>
                    <p class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Monthly Stats -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">Monthly Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-[#1D1D1D] border border-[#FFA040] text-white">
                        <thead>
                            <tr class="bg-[#FFA040] text-black">
                                <th class="py-2 px-4 border border-[#FFA040]">Month</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Bookings</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthlyStats as $stat)
                                <tr>
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $stat['month'] }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $stat['bookings'] }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">Rp {{ number_format($stat['revenue'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">Booking Details</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-[#1D1D1D] border border-[#FFA040] text-white">
                        <thead>
                            <tr class="bg-[#FFA040] text-black">
                                <th class="py-2 px-4 border border-[#FFA040]">Booking ID</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Customer</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Rooms</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Check In</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Check Out</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Total Price</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Status</th>
                                <th class="py-2 px-4 border border-[#FFA040]">Managed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $booking->id }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $booking->user->name }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">
                                        @foreach($booking->rooms as $room)
                                            Room {{ $room->room_number }} ({{ $room->type }})<br>
                                        @endforeach
                                    </td>
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $booking->check_in_date }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $booking->check_out_date }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td class="py-2 px-4 border border-[#FFA040]">
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
                                    <td class="py-2 px-4 border border-[#FFA040]">{{ $booking->managedBy->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-4 px-4 border border-[#FFA040] text-center text-gray-400">
                                        No bookings found for this year
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
                                    <span class="px-4 py-2 text-white bg-[#1D1D1D] border border-[#FFA040] rounded-md opacity-50 cursor-not-allowed">
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $bookings->previousPageUrl() }}" 
                                       class="px-4 py-2 text-white bg-[#1D1D1D] border border-[#FFA040] rounded-md hover:bg-[#FFA040] hover:text-black transition-colors duration-200">
                                        Previous
                                    </a>
                                @endif

                                <!-- Page Numbers -->
                                <div class="flex items-center">
                                    @foreach($bookings->getUrlRange(max($bookings->currentPage() - 2, 1), min($bookings->currentPage() + 2, $bookings->lastPage())) as $page => $url)
                                        @if($page == $bookings->currentPage())
                                            <span class="mx-1 px-4 py-2 text-black bg-[#FFA040] rounded-md">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" 
                                               class="mx-1 px-4 py-2 text-white bg-[#1D1D1D] border border-[#FFA040] rounded-md hover:bg-[#FFA040] hover:text-black transition-colors duration-200">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Next Page -->
                                @if($bookings->hasMorePages())
                                    <a href="{{ $bookings->nextPageUrl() }}" 
                                       class="px-4 py-2 text-white bg-[#1D1D1D] border border-[#FFA040] rounded-md hover:bg-[#FFA040] hover:text-black transition-colors duration-200">
                                        Next
                                    </a>
                                @else
                                    <span class="px-4 py-2 text-white bg-[#1D1D1D] border border-[#FFA040] rounded-md opacity-50 cursor-not-allowed">
                                        Next
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Export Button -->
            <div class="mt-6">
                <a href="{{ route('admin.reports.export.pdf', ['type' => 'yearly', 'year' => $year]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export to PDF
                </a>
            </div>
        </div>
    </div>
</x-admin-layout> 