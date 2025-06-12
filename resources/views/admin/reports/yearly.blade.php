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
                                        <span class="px-2 py-1 rounded text-sm font-semibold"
                                            @if($booking->status === 'confirmed') style="background-color: #16a34a; color: #fff;" @endif
                                            @if($booking->status === 'pending') style="background-color: #FFA040; color: #000;" @endif
                                            @if($booking->status === 'cancelled') style="background-color: #dc2626; color: #fff;" @endif>
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