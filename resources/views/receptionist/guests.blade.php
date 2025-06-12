<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Tamu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="relative overflow-hidden shadow-sm sm:rounded-lg mb-6" style="background:#2D2D2D;">
                <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                <div class="p-6">
                    <form method="GET" action="{{ route('receptionist.guests') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-[#FFD740] bg-[#232323] text-white focus:border-[#FFD740] focus:ring-[#FFD740]"
                                placeholder="Nama, Email, No. Telp, atau No. ID">
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Guests List -->
            <div class="relative overflow-hidden shadow-sm sm:rounded-lg" style="background:#2D2D2D;">
                <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead style="background:#252525;">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Nama
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        No. Telp
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        No. ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Total Pemesanan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="color:#fff;">
                                        Terdaftar Pada
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background:#2D2D2D;color:#fff;" class="bg-[#232323] divide-y divide-[#333]">
                                @forelse ($guests as $guest)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $guest->full_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $guest->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $guest->phone }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $guest->id_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $guest->total_bookings }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $guest->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada tamu yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $guests->links() }}
            </div>
        </div>
    </div>
</x-receptionist-layout>

<style>
.pagination nav {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}
.pagination nav > div {
    width: 100%;
}
.pagination nav ul {
    display: flex;
    justify-content: center;
    gap: 4px;
    padding: 0;
}
.pagination nav li {
    list-style: none;
}
.pagination nav a,
.pagination nav span {
    background: #232323 !important;
    color: #fff !important;
    border: 1px solid #FFA040 !important;
    border-radius: 6px !important;
    padding: 8px 14px !important;
    margin: 0 2px;
    min-width: 36px;
    min-height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: background 0.2s, color 0.2s;
}
.pagination nav a:hover,
.pagination nav a:focus {
    background: #FFA040 !important;
    color: #232323 !important;
    border-color: #FFA040 !important;
}
.pagination nav .active span,
.pagination nav li.active span {
    background: #FFA040 !important;
    color: #232323 !important;
    border-color: #FFA040 !important;
}
.pagination nav .disabled span,
.pagination nav li.disabled span {
    background: #232323 !important;
    color: #888 !important;
    border-color: #444 !important;
    cursor: not-allowed !important;
}
</style> 