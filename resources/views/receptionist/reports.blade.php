<x-receptionist-layout>
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
                            Rp {{ number_format($monthlyRevenue->where('month', now()->month)->first()?->revenue ?? 0, 0, ',', '.') }}
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
                            Rp {{ number_format($averageRate, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Occupancy Rate -->
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-400">Tingkat Hunian</div>
                        @php
                            $totalRooms = App\Models\Room::count();
                            $occupiedRooms = App\Models\Room::where('status', 'occupied')->count();
                            $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
                        @endphp
                        <div class="mt-2 text-3xl font-bold text-white">
                            {{ number_format($occupancyRate, 1) }}%
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
                                    @foreach($monthlyBookings->sortByDesc('month') as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ \Carbon\Carbon::create()->month($booking->month)->format('F Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ number_format($booking->total) }}
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
                                    @foreach($monthlyRevenue->sortByDesc('month') as $revenue)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ \Carbon\Carbon::create()->month($revenue->month)->format('F Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                Rp {{ number_format($revenue->revenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Details -->
            <div class="bg-gray-800/70 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Detail Pendapatan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Booking ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Tipe
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Dicatat Oleh
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($revenueDetails->sortByDesc('created_at') as $revenue)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $revenue->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            #{{ $revenue->booking_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $revenue->type === 'check_in' ? 'Check-in' : 'Check-out' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            Rp {{ number_format($revenue->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $revenue->recorder->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-receptionist-layout> 