<x-receptionist-layout>
    <style>
        /* Custom Scrollbar Styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }

        /* Hide scrollbar when not hovering */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) rgba(55, 65, 81, 0.3);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Revenue -->
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-400">Total Pendapatan Bulan Ini</div>
                        <div class="mt-2 text-3xl font-bold text-white">
                            Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}
                        </div>
                        @php
                            $lastMonthRevenue = $monthlyRevenue->where('month', now()->subMonth()->month)->first()?->revenue ?? 0;
                            $thisMonthRevenue = $monthlyRevenue->where('month', now()->month)->first()?->revenue ?? 0;
                            $revenueGrowth = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
                        @endphp
                        <div class="mt-2 text-sm {{ $revenueGrowth >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ number_format(abs($revenueGrowth), 1) }}% {{ $revenueGrowth >= 0 ? 'kenaikan' : 'penurunan' }} dari bulan lalu
                        </div>
                    </div>
                </div>

                <!-- Average Daily Rate -->
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-400">Rata-rata Harga per Malam</div>
                        @php
                            $thisMonthBookings = $monthlyBookings->where('month', now()->month)->first()?->total ?? 0;
                            $thisMonthRevenue = $monthlyRevenue->where('month', now()->month)->first()?->revenue ?? 0;
                            $averageRate = $thisMonthBookings > 0 ? $thisMonthRevenue / $thisMonthBookings : 0;
                        @endphp
                        <div class="mt-2 text-3xl font-bold text-white">
                            Rp {{ number_format($averageRatePerNight, 0, ',', '.') }}
                        </div>
                        <div class="mt-2 text-sm text-gray-400">
                            Rata-rata harga kamar per malam
                        </div>
                    </div>
                </div>

                <!-- Occupancy Rate -->
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-400">Tingkat Hunian</div>
                        @php
                            $totalRooms = App\Models\Room::count();
                        @endphp
                        <div class="mt-2 text-3xl font-bold text-white">
                            {{ number_format($occupancyRate, 1) }}%
                        </div>
                        <div class="mt-2 text-sm text-gray-400">
                            Persentase kamar yang terisi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Reports -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Bookings -->
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Pemesanan per Bulan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Bulan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Total Pemesanan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($monthlyBookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ $booking['month']->format('F Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ number_format($booking['total']) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Pendapatan per Bulan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Bulan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Total Pendapatan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($monthlyRevenue as $revenue)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ $revenue['month']->format('F Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                Rp {{ number_format($revenue['revenue'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details with Pagination -->
            <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-white">Detail Pesanan</h3>
                        <a href="{{ route('receptionist.reports.download') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Nama Tamu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Kamar
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Check-in
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Check-out
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Pembayaran
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($bookingDetails as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $detail['tanggal']->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $detail['guest_name'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-white relative">
                                            @php
                                                $rooms = explode(', ', $detail['rooms']);
                                            @endphp
                                            <div class="max-h-[85px] overflow-y-auto custom-scrollbar">
                                                <div class="flex flex-col space-y-1.5 pr-2">
                                                    @foreach($rooms as $room)
                                                        <div class="bg-gray-700/50 px-3 py-1.5 rounded-md border border-gray-600/30">{{ $room }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $detail['check_in'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $detail['check_out'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            Rp {{ number_format($detail['total_price'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $detail['status'] === 'checked_in' ? 'bg-blue-100/10 text-blue-400' : 
                                                   ($detail['status'] === 'checked_out' ? 'bg-blue-100/10 text-blue-400' :
                                                   ($detail['status'] === 'confirmed' ? 'bg-green-100/10 text-green-400' :
                                                   ($detail['status'] === 'cancelled' || $detail['status'] === 'expired' ? 'bg-red-100/10 text-red-400' : 
                                                   ($detail['status'] === 'pending' ? 'bg-yellow-100/10 text-yellow-400' : 'bg-gray-100/10 text-gray-400')))) }}">
                                                {{ ucfirst($detail['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $detail['payment_status'] === 'paid' ? 'bg-green-100/10 text-green-400' : 
                                                   ($detail['payment_status'] === 'pending' ? 'bg-yellow-100/10 text-yellow-400' : 
                                                   ($detail['payment_status'] === 'deposit' ? 'bg-blue-100/10 text-blue-400' : 'bg-red-100/10 text-red-400')) }}">
                                                {{ ucfirst($detail['payment_status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $bookingDetails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-receptionist-layout> 