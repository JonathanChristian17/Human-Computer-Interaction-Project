@section('title', 'Receptionist Dashboard - Cahaya Resort Pangururan')

<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight text-center">
            {{ __('Dashboard Resepsionis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Check-out Reminder -->
            @if($todayCheckOuts->count() > 0)
                <div class="mb-8 bg-yellow-500/10 backdrop-blur-sm border border-yellow-500/20 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-yellow-400">Pengingat Check-out Hari Ini</h3>
                            <div class="mt-2 text-sm text-yellow-300">
                                <p>Ada {{ $todayCheckOuts->count() }} tamu yang harus check-out hari ini. Silakan cek daftar check-out untuk memproses.</p>
                            </div>
                            <div class="mt-4">
                                <div class="-mx-2 -my-1.5 flex">
                                    <a href="{{ route('receptionist.check-out') }}" class="bg-yellow-500 px-4 py-2 rounded-lg text-white text-sm font-medium hover:bg-yellow-600">
                                        Lihat Daftar Check-out
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Check-in Reminder -->
            @if($todayCheckIns->count() > 0)
                <div class="mb-8 bg-green-500/10 backdrop-blur-sm border border-green-500/20 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-green-400">Pengingat Check-in Hari Ini</h3>
                            <div class="mt-2 text-sm text-green-300">
                                <p>Ada {{ $todayCheckIns->count() }} tamu yang akan check-in hari ini. Silakan cek daftar check-in untuk memproses.</p>
                            </div>
                            <div class="mt-4">
                                <div class="-mx-2 -my-1.5 flex">
                                    <a href="{{ route('receptionist.check-in') }}" class="bg-green-500 px-4 py-2 rounded-lg text-white text-sm font-medium hover:bg-green-600">
                                        Lihat Daftar Check-in
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <!-- Total Rooms -->
                <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                    <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color:#FFA040;">Total Kamar</p>
                                <p class="text-lg font-semibold" style="color:#fff;">{{ $totalRooms }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Rooms -->
                <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                    <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color:#FFA040;">Kamar Tersedia</p>
                                <p class="text-lg font-semibold" style="color:#fff;">{{ $availableRooms }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Occupied Rooms -->
                <div class="relative overflow-hidden shadow-sm rounded-xl" style="background:#2D2D2D;">
                    <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-purple-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color:#FFA040;">Kamar Terisi</p>
                                <p class="text-2xl font-semibold" style="color:#fff;">{{ $occupiedRooms }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Check-ins Today -->
                <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                    <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4" style="color:#FFA040;">
                            Check-in Hari Ini ({{ $todayCheckIns->count() }})
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead style="background:#252525;">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color:#fff;">
                                            Tamu
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color:#fff;">
                                            Kamar
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color:#fff;">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="background:#2D2D2D;color:#fff;">
                                    @forelse ($todayCheckIns as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white dark:text-gray-100">
                                                    {{ $booking->user->name }}
                                                </div>
                                                <div class="text-sm text-white dark:text-gray-400">
                                                    {{ $booking->user->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-white dark:text-gray-100">
                                                    @foreach($booking->rooms as $room)
                                                        Kamar {{ $room->room_number }}<br>
                                                        <div class="text-sm text-white dark:text-gray-400">
                                                            {{ $room->type }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : '' }}
                                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900' : '' }}
                                                        {{ $booking->status === 'checked_in' ? 'bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900' : '' }}
                                                        {{ $booking->status === 'checked_out' ? 'bg-purple-100 text-purple-800 dark:bg-purple-200 dark:text-purple-900' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900' : '' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : '' }}
                                                        {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900' : '' }}
                                                        {{ $booking->payment_status === 'refunded' ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900' : '' }}">
                                                        {{ ucfirst($booking->payment_status) }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                Tidak ada check-in hari ini
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Check-outs Today -->
                <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                    <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4" style="color:#FFA040;">
                            Check-out Hari Ini ({{ $todayCheckOuts->count() }})
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead style="background:#252525;">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color:#fff;">
                                            Tamu
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color:#fff;">
                                            Kamar
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color:#fff;">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="background:#2D2D2D;color:#fff;">
                                    @forelse ($todayCheckOuts as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white dark:text-gray-100">
                                                    {{ $booking->user->name }}
                                                </div>
                                                <div class="text-sm text-white dark:text-gray-400">
                                                    {{ $booking->user->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-white dark:text-gray-100">
                                                    @foreach($booking->rooms as $room)
                                                        Kamar {{ $room->room_number }}<br>
                                                        <div class="text-sm text-white dark:text-gray-400">
                                                            {{ $room->type }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : '' }}
                                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900' : '' }}
                                                        {{ $booking->status === 'checked_in' ? 'bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900' : '' }}
                                                        {{ $booking->status === 'checked_out' ? 'bg-purple-100 text-purple-800 dark:bg-purple-200 dark:text-purple-900' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900' : '' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' : '' }}
                                                        {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900' : '' }}
                                                        {{ $booking->payment_status === 'refunded' ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900' : '' }}">
                                                        {{ ucfirst($booking->payment_status) }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                Tidak ada check-out hari ini
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4" style="color:#FFA040;">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('receptionist.bookings') }}" class="flex items-center p-4 bg-blue-500 hover:bg-blue-600 rounded-lg text-white">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Kelola Booking
                        </a>
                        <a href="{{ route('receptionist.check-in') }}" class="flex items-center p-4 bg-green-500 hover:bg-green-600 rounded-lg text-white">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Check-in
                        </a>
                        <a href="{{ route('receptionist.check-out') }}" class="flex items-center p-4 bg-yellow-500 hover:bg-yellow-600 rounded-lg text-white">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Check-out
                        </a>
                        <a href="{{ route('receptionist.reports') }}" class="flex items-center p-4 bg-purple-500 hover:bg-purple-600 rounded-lg text-white">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-receptionist-layout>