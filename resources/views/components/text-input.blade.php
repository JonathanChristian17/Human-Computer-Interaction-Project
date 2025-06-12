@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-[#1D1D1D] border-[#FFA040] text-white focus:border-[#FFA040] focus:ring-[#FFA040] rounded-md shadow-sm']) }}>
