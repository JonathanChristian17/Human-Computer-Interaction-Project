<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="bg-[#252525] overflow-hidden shadow-sm sm:rounded-lg border border-[#FFA040]">
        <div class="p-6 text-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily Report -->
                <div class="bg-[#1D1D1D] p-6 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">Daily Report</h3>
                    <form action="{{ route('admin.reports.daily') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-300">Select Date</label>
                            <input type="date" name="date" id="date" 
                                value="{{ now()->format('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md bg-[#252525] border-[#FFA040] text-white shadow-sm focus:border-[#FFA040] focus:ring focus:ring-[#FFA040] focus:ring-opacity-50">
                        </div>
                        <button type="submit" class="w-full bg-[#FFA040] text-white py-2 px-4 rounded-md hover:bg-[#ff8c1a] transition-all duration-200">
                            View Daily Report
                        </button>
                    </form>
                </div>

                <!-- Monthly Report -->
                <div class="bg-[#1D1D1D] p-6 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">Monthly Report</h3>
                    <form action="{{ route('admin.reports.monthly') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-300">Select Month</label>
                            <input type="month" name="month" id="month" 
                                value="{{ now()->format('Y-m') }}"
                                class="mt-1 block w-full rounded-md bg-[#252525] border-[#FFA040] text-white shadow-sm focus:border-[#FFA040] focus:ring focus:ring-[#FFA040] focus:ring-opacity-50">
                        </div>
                        <button type="submit" class="w-full bg-[#FFA040] text-white py-2 px-4 rounded-md hover:bg-[#ff8c1a] transition-all duration-200">
                            View Monthly Report
                        </button>
                    </form>
                </div>

                <!-- Yearly Report -->
                <div class="bg-[#1D1D1D] p-6 rounded-lg border border-[#FFA040]">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA040]">Yearly Report</h3>
                    <form action="{{ route('admin.reports.yearly') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-300">Select Year</label>
                            <select name="year" id="year" class="mt-1 block w-full rounded-md bg-[#252525] border-[#FFA040] text-white shadow-sm focus:border-[#FFA040] focus:ring focus:ring-[#FFA040] focus:ring-opacity-50">
                                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-[#FFA040] text-white py-2 px-4 rounded-md hover:bg-[#ff8c1a] transition-all duration-200">
                            View Yearly Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 