<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="bg-[#1F1F1F] overflow-hidden shadow-sm sm:rounded-lg border border-[#333333]">
        <div class="p-6 text-[#E0E0E0]">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily Report -->
                <div class="bg-[#2A2A2A] hover:bg-[#333333] p-6 rounded-lg border border-[#333333] transition-all duration-200">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA500]">Daily Report</h3>
                    <form action="{{ route('admin.reports.daily') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-[#E0E0E0]">Select Date</label>
                            <input type="date" name="date" id="date" 
                                value="{{ now()->format('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md bg-[#1F1F1F] border-[#333333] text-[#E0E0E0] shadow-sm focus:border-[#FFA500] focus:ring focus:ring-[#FFA500] focus:ring-opacity-50">
                        </div>
                        <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#ff8c1a] transition-all duration-200">
                            View Daily Report
                        </button>
                    </form>
                </div>

                <!-- Monthly Report -->
                <div class="bg-[#2A2A2A] hover:bg-[#333333] p-6 rounded-lg border border-[#333333] transition-all duration-200">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA500]">Monthly Report</h3>
                    <form action="{{ route('admin.reports.monthly') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="month" class="block text-sm font-medium text-[#E0E0E0]">Select Month</label>
                            <input type="month" name="month" id="month" 
                                value="{{ now()->format('Y-m') }}"
                                class="mt-1 block w-full rounded-md bg-[#1F1F1F] border-[#333333] text-[#E0E0E0] shadow-sm focus:border-[#FFA500] focus:ring focus:ring-[#FFA500] focus:ring-opacity-50">
                        </div>
                        <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#ff8c1a] transition-all duration-200">
                            View Monthly Report
                        </button>
                    </form>
                </div>

                <!-- Yearly Report -->
                <div class="bg-[#2A2A2A] hover:bg-[#333333] p-6 rounded-lg border border-[#333333] transition-all duration-200">
                    <h3 class="text-lg font-semibold mb-4 text-[#FFA500]">Yearly Report</h3>
                    <form action="{{ route('admin.reports.yearly') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="year" class="block text-sm font-medium text-[#E0E0E0]">Select Year</label>
                            <select name="year" id="year" class="mt-1 block w-full rounded-md bg-[#1F1F1F] border-[#333333] text-[#E0E0E0] shadow-sm focus:border-[#FFA500] focus:ring focus:ring-[#FFA500] focus:ring-opacity-50">
                                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#ff8c1a] transition-all duration-200">
                            View Yearly Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 