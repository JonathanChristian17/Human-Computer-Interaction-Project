<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-[#252525] overflow-hidden shadow-sm sm:rounded-lg border border-[#FFA040]">
        <div class="p-6 text-gray-100">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Rooms -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Total Rooms</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalRooms }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-green-400">Available: {{ $availableRooms }}</span><br>
                        <span class="text-[#FFA040]">Occupied: {{ $occupiedRooms }}</span><br>
                        <span class="text-red-400">Maintenance: {{ $maintenanceRooms }}</span>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Users</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-gray-300">Customers: {{ $totalCustomers }}</span><br>
                        <span class="text-gray-300">Receptionists: {{ $totalReceptionists }}</span><br>
                        <span class="text-gray-300">Admins: {{ $totalAdmins }}</span>
                    </div>
                </div>

                <!-- Bookings Overview -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Bookings</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalBookings }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-gray-300">Pending: {{ $pendingBookings }}</span><br>
                        <span class="text-gray-300">Active: {{ $activeBookings }}</span><br>
                        <span class="text-gray-300">Completed: {{ $completedBookings }}</span>
                    </div>
                </div>

                <!-- Revenue Overview -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Revenue</h3>
                    <p class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-gray-300">This Month: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span><br>
                        <span class="text-gray-300">Pending: Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Daily Stats -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">Today's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-300">New Bookings:</span>
                            <span class="font-semibold text-white">{{ $dailyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Check-ins:</span>
                            <span class="font-semibold text-white">{{ $dailyCheckins }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Check-outs:</span>
                            <span class="font-semibold text-white">{{ $dailyCheckouts }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Revenue:</span>
                            <span class="font-semibold text-white">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Stats -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">This Month's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Total Bookings:</span>
                            <span class="font-semibold text-white">{{ $monthlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Occupancy Rate:</span>
                            <span class="font-semibold text-white">{{ number_format($monthlyOccupancyRate, 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Average Daily Rate:</span>
                            <span class="font-semibold text-white">Rp {{ number_format($monthlyADR, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Revenue:</span>
                            <span class="font-semibold text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Yearly Stats -->
                <div class="bg-[#1D1D1D] p-4 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">This Year's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Total Bookings:</span>
                            <span class="font-semibold text-white">{{ $yearlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Average Occupancy:</span>
                            <span class="font-semibold text-white">{{ number_format($yearlyOccupancyRate, 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Total Revenue:</span>
                            <span class="font-semibold text-white">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Growth Rate:</span>
                            <span class="font-semibold {{ $revenueGrowth >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-[#FFA040]">Recent Activities</h3>
                    
                    <!-- Filter Form -->
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex gap-4">
                        <select name="activity_type" class="rounded-lg bg-[#1D1D1D] border border-[#FFA040] text-white text-sm focus:ring-[#FFA040] focus:border-[#FFA040]">
                            <option value="">All Activities</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="user_id" class="rounded-lg bg-[#1D1D1D] border border-[#FFA040] text-white text-sm focus:ring-[#FFA040] focus:border-[#FFA040]">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="flex gap-2">
                            <button type="submit" 
                                class="px-4 py-2 bg-[#FFA040] text-white rounded-lg hover:bg-[#ff8c1a] focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:ring-offset-2 focus:ring-offset-[#1D1D1D]">
                                Filter
                            </button>
                            @if(request()->hasAny(['activity_type', 'user_id']))
                                <a href="{{ route('admin.dashboard') }}" 
                                    class="px-4 py-2 bg-[#1D1D1D] text-white rounded-lg hover:bg-[#2D2D2D] focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:ring-offset-2 focus:ring-offset-[#1D1D1D] border border-[#FFA040]">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="bg-[#1D1D1D] rounded-lg border border-[#FFA040] overflow-hidden">
                    <table class="min-w-full divide-y divide-[#FFA040]">
                        <thead class="bg-[#252525]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#FFA040] uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#FFA040] uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#FFA040] uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#FFA040] uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#1D1D1D] divide-y divide-[#FFA040]">
                            @foreach($recentActivities as $activity)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $activity->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $activity->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $activity->details }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-[#252525] border-t border-[#FFA040]">
                        {{ $recentActivities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 