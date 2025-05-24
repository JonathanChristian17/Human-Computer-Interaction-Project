<!-- Navbar Component -->
<nav class="fixed w-full z-50 bg-gradient-to-b from-black/50 to-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="text-white text-2xl font-semibold">
                    Cahaya Resort
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/dashboard" class="text-white hover:text-gray-300 transition">Dashboard</a>
                <a href="/rooms" class="text-white hover:text-gray-300 transition">Rooms</a>
                <a href="/gallery" class="text-white hover:text-gray-300 transition">Gallery</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center space-x-4">
                <a href="/login" class="text-white hover:text-gray-300 transition px-4 py-2 rounded-full">LOGIN</a>
                <a href="/register" class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition">REGISTER</a>
            </div>
        </div>
    </div>
</nav> 