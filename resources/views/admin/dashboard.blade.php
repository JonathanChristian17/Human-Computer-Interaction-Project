<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Rooms -->
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800">Total Rooms</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalRooms }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-blue-700">Available: {{ $availableRooms }}</span><br>
                        <span class="text-orange-700">Occupied: {{ $occupiedRooms }}</span><br>
                        <span class="text-red-700">Maintenance: {{ $maintenanceRooms }}</span>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800">Users</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalUsers }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-green-700">Customers: {{ $totalCustomers }}</span><br>
                        <span class="text-green-700">Receptionists: {{ $totalReceptionists }}</span><br>
                        <span class="text-green-700">Admins: {{ $totalAdmins }}</span>
                    </div>
                </div>

                <!-- Bookings Overview -->
                <div class="bg-purple-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-800">Bookings</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalBookings }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-purple-700">Pending: {{ $pendingBookings }}</span><br>
                        <span class="text-purple-700">Active: {{ $activeBookings }}</span><br>
                        <span class="text-purple-700">Completed: {{ $completedBookings }}</span>
                    </div>
                </div>

                <!-- Revenue Overview -->
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800">Revenue</h3>
                    <p class="text-3xl font-bold text-yellow-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <div class="mt-2 text-sm">
                        <span class="text-yellow-700">This Month: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span><br>
                        <span class="text-yellow-700">Pending: Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Daily Stats -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold mb-4">Today's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>New Bookings:</span>
                            <span class="font-semibold">{{ $dailyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Check-ins:</span>
                            <span class="font-semibold">{{ $dailyCheckins }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Check-outs:</span>
                            <span class="font-semibold">{{ $dailyCheckouts }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Revenue:</span>
                            <span class="font-semibold">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Stats -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold mb-4">This Month's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Total Bookings:</span>
                            <span class="font-semibold">{{ $monthlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Occupancy Rate:</span>
                            <span class="font-semibold">{{ number_format($monthlyOccupancyRate, 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Average Daily Rate:</span>
                            <span class="font-semibold">Rp {{ number_format($monthlyADR, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Revenue:</span>
                            <span class="font-semibold">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Yearly Stats -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold mb-4">This Year's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Total Bookings:</span>
                            <span class="font-semibold">{{ $yearlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Average Occupancy:</span>
                            <span class="font-semibold">{{ number_format($yearlyOccupancyRate, 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Revenue:</span>
                            <span class="font-semibold">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Growth Rate:</span>
                            <span class="font-semibold {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Recent Activities</h3>
                    
                    <!-- Filter Form -->
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex gap-4">
                        <select name="activity_type" class="rounded-lg bg-gray-700/50 border border-gray-600/50 text-white text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Activities</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="user_id" class="rounded-lg bg-gray-700/50 border border-gray-600/50 text-white text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="flex gap-2">
                            <button type="submit" 
                                class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                Filter
                            </button>
                            @if(request()->hasAny(['activity_type', 'user_id']))
                                <a href="{{ route('admin.dashboard') }}" 
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-lg border overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentActivities as $activity)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activity->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->details }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $recentActivities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 