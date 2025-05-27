<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-[#F0F7FF] min-h-screen">
        <div class="p-6 text-gray-900">
            <!-- Welcome Message -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600">Here's what's happening with your hotel today.</p>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Rooms -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Total Rooms</h3>
                        <i class="fas fa-door-open text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalRooms }}</p>
                    <p class="text-sm text-gray-600 mt-2">Available: {{ $availableRooms ?? 0 }}</p>
                </div>

                <!-- Total Customers -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Total Customers</h3>
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-green-600">{{ $totalCustomers }}</p>
                    <p class="text-sm text-gray-600 mt-2">New this month: {{ $newCustomers ?? 0 }}</p>
                </div>

                <!-- Total Receptionists -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Total Receptionists</h3>
                        <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalReceptionists }}</p>
                    <p class="text-sm text-gray-600 mt-2">Active: {{ $activeReceptionists ?? 0 }}</p>
                </div>

                <!-- Total Bookings -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Total Bookings</h3>
                        <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-yellow-600">{{ $totalBookings }}</p>
                    <p class="text-sm text-gray-600 mt-2">Pending: {{ $pendingBookings ?? 0 }}</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Daily Stats -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Today's Statistics</h3>
                        <i class="fas fa-chart-line text-blue-600"></i>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Bookings:</span>
                            <span class="font-semibold text-gray-800">{{ $dailyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Revenue:</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-ins:</span>
                            <span class="font-semibold text-gray-800">{{ $dailyCheckIns ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-outs:</span>
                            <span class="font-semibold text-gray-800">{{ $dailyCheckOuts ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Stats -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">This Month's Statistics</h3>
                        <i class="fas fa-chart-bar text-blue-600"></i>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Bookings:</span>
                            <span class="font-semibold text-gray-800">{{ $monthlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Revenue:</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">New Customers:</span>
                            <span class="font-semibold text-gray-800">{{ $newCustomers ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Occupancy Rate:</span>
                            <span class="font-semibold text-gray-800">{{ $monthlyOccupancyRate ?? '0' }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Yearly Stats -->
                <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">This Year's Statistics</h3>
                        <i class="fas fa-chart-pie text-blue-600"></i>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Bookings:</span>
                            <span class="font-semibold text-gray-800">{{ $yearlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Revenue:</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Customers:</span>
                            <span class="font-semibold text-gray-800">{{ $totalCustomers }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Quick Actions -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Bookings -->
                <div class="lg:col-span-2">
                    <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                            <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#FF5722]">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#FF5722]">
                                    @forelse($recentBookings ?? [] as $booking)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->full_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->email }}</div>
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($booking->rooms->isNotEmpty())
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->rooms->first()->room_number }}</div>
                                                <div class="text-sm text-gray-500">{{ $booking->rooms->first()->name }}</div>
                                            @else
                                                <span class="text-sm text-gray-500">No room assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $booking->check_in_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent bookings</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div>
                    <div class="bg-[#FEB47B] p-4 rounded-lg shadow-sm border border-[#FF5722]">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="{{ route('admin.rooms.create') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                <i class="fas fa-plus-circle mr-3"></i>
                                <span>Add New Room</span>
                            </a>
                            <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                                <i class="fas fa-user-plus mr-3"></i>
                                <span>Add New User</span>
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                                <i class="fas fa-chart-bar mr-3"></i>
                                <span>View Reports</span>
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                <span>Manage Bookings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 