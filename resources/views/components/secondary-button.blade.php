<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 border border-[#FFA040] rounded-md font-semibold text-xs text-[#FFA040] uppercase tracking-widest bg-transparent hover:bg-[#FFA040] hover:text-white focus:bg-[#FFA040] focus:text-white active:bg-[#e07a00] focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
