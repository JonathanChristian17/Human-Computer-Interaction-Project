<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daily Report') }} - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
            </h2>
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition-colors duration-200">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <!-- Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800">Total Bookings</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalBookings }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800">Total Revenue</h3>
                    <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Booking Details</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border">Booking ID</th>
                                <th class="py-2 px-4 border">Customer</th>
                                <th class="py-2 px-4 border">Rooms</th>
                                <th class="py-2 px-4 border">Check In</th>
                                <th class="py-2 px-4 border">Check Out</th>
                                <th class="py-2 px-4 border">Total Price</th>
                                <th class="py-2 px-4 border">Status</th>
                                <th class="py-2 px-4 border">Managed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $booking->id }}</td>
                                    <td class="py-2 px-4 border">
                                        <div class="text-sm font-medium">{{ $booking->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->email }}</div>
                                    </td>
                                    <td class="py-2 px-4 border">
                                        @foreach($booking->rooms as $room)
                                            Room {{ $room->room_number }} ({{ $room->type }})<br>
                                        @endforeach
                                    </td>
                                    <td class="py-2 px-4 border">{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td class="py-2 px-4 border">{{ $booking->check_out_date->format('M d, Y') }}</td>
                                    <td class="py-2 px-4 border">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td class="py-2 px-4 border">
                                        <span class="px-2 py-1 rounded text-sm
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border">{{ $booking->receptionist->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-4 px-4 border text-center text-gray-500">
                                        No bookings found for this day
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Export Button -->
            <div class="mt-6">
                <a href="{{ route('admin.reports.export.pdf', ['type' => 'daily', 'date' => $date]) }}" 
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