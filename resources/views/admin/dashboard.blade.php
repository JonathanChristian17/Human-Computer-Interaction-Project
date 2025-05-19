<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Rooms -->
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800">Total Rooms</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalRooms }}</p>
                </div>

                <!-- Total Customers -->
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800">Total Customers</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalCustomers }}</p>
                </div>

                <!-- Total Receptionists -->
                <div class="bg-purple-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-800">Total Receptionists</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalReceptionists }}</p>
                </div>

                <!-- Total Bookings -->
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800">Total Bookings</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $totalBookings }}</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Daily Stats -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold mb-4">Today's Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Bookings:</span>
                            <span class="font-semibold">{{ $dailyBookings }}</span>
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
                            <span>Bookings:</span>
                            <span class="font-semibold">{{ $monthlyBookings }}</span>
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
                            <span>Bookings:</span>
                            <span class="font-semibold">{{ $yearlyBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Revenue:</span>
                            <span class="font-semibold">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.rooms.create') }}" class="bg-blue-500 text-white p-4 rounded-lg text-center hover:bg-blue-600 transition">
                        Add New Room
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="bg-green-500 text-white p-4 rounded-lg text-center hover:bg-green-600 transition">
                        Add New User
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="bg-purple-500 text-white p-4 rounded-lg text-center hover:bg-purple-600 transition">
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 