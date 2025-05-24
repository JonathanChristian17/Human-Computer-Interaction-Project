<!-- Navbar Component -->
<nav class="fixed z-50 w-full bg-gradient-to-b from-black/50 to-transparent">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="text-2xl font-semibold text-white">
                    Cahaya Resort
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="items-center hidden space-x-8 md:flex">
                <a href="/dashboard" class="text-white transition hover:text-gray-300">Dashboard</a>
                <a href="/rooms" class="text-white transition hover:text-gray-300">Rooms</a>
                <a href="/gallery" class="text-white transition hover:text-gray-300">Gallery</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center space-x-4">
                <a href="/login" class="px-4 py-2 text-white transition rounded-full hover:text-gray-300">LOGIN</a>
                <a href="/register" class="px-4 py-2 text-white transition bg-orange-500 rounded-full hover:bg-orange-600">REGISTER</a>
            </div>
        </div>
    </div>
</nav> 