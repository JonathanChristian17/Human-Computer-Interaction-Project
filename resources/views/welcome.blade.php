<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cahaya Resort Panguruan</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white">
    <!-- Include Navbar -->
    @include('components.navbar')

    <!-- Hero Section -->
    <section class="relative h-screen">
        <!-- Hero Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="Resort View" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative h-full flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-3xl">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        CAHAYA RESORT<br>PANGURUAN
                    </h1>
                    <p class="text-white text-lg mb-8">
                        We provide a variety of the best lodging accommodations for those of you who need it.
                    </p>

                    <!-- Booking Form -->
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-lg inline-flex items-center space-x-4">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="text-white text-sm">Check In</label>
                                <input type="date" class="bg-transparent text-white border-b border-white">
                            </div>
                            <div>
                                <label class="text-white text-sm">Check Out</label>
                                <input type="date" class="bg-transparent text-white border-b border-white">
                            </div>
                            <div>
                                <label class="text-white text-sm">Rooms / Guests</label>
                                <select class="bg-transparent text-white border-b border-white">
                                    <option>1 Room, 2 Guests</option>
                                    <option>2 Rooms, 4 Guests</option>
                                </select>
                            </div>
                        </div>
                        <button class="bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transition">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Choice Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-semibold text-center mb-4">WELCOME TO CAHAYA RESORT</h2>
            <h3 class="text-2xl font-medium text-center mb-12">Room Choice in Cahaya Resort</h3>
            
            <!-- Room Carousel -->
            <div class="relative">
                <div class="flex space-x-6 overflow-x-auto pb-6">
                    <!-- Room Cards -->
                    @foreach(['Deluxe Room', 'Suite Room', 'Twin Room', 'Double Room', 'Family Room'] as $room)
                    <div class="flex-none w-72">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="{{ asset('images/rooms/room-1.jpg') }}" alt="{{ $room }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h4 class="font-semibold">{{ $room }}</h4>
                                <p class="text-orange-500">Rp 500.000</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-semibold text-center mb-12">Why Cahaya Resort?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <h3 class="text-xl font-semibold mb-4">Traditional</h3>
                    <p>From local hotels to international, discover folklore of hotels all around the world.</p>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-semibold mb-4">Modern</h3>
                    <p>No need to search another site. The biggest names in hotel are right here.</p>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-semibold mb-4">Affordable</h3>
                    <p>We've scored deals with the world's leading hotels and we share those savings with you.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dream in Serene Luxury Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-semibold mb-6">Dream In Serene Luxury</h2>
                    <p class="text-gray-600 mb-8">
                        Cahaya Panguruan Inn, comfortable with local nuances and natural panorama of Samosir. Wake up with cool air, calming lake views, and a calm atmosphere that refreshes the soul. Enjoy the hospitality of the host, complete facilities, and comfort like at home.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ asset('images/facilities/rooms.jpg') }}" alt="Rooms" class="rounded-lg">
                    <img src="{{ asset('images/facilities/parking.jpg') }}" alt="Parking Area" class="rounded-lg">
                    <img src="{{ asset('images/facilities/park.jpg') }}" alt="Mini Park" class="rounded-lg">
                    <img src="{{ asset('images/facilities/lobby.jpg') }}" alt="Lobby" class="rounded-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Stay in the know</h2>
                    <p class="mb-6">Sign up to get marketing emails from Cahaya Resort, including promotions, rewards, and information about Cahaya Resort services.</p>
                    <div class="flex">
                        <input type="email" placeholder="Email Address" class="flex-1 px-4 py-2 rounded-l-full bg-white/10">
                        <button class="bg-orange-500 px-6 py-2 rounded-r-full hover:bg-orange-600 transition">
                            Subscribe
                        </button>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold mb-6">Preferred Room</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white/10 p-4 rounded-lg text-center">Delux</div>
                        <div class="bg-white/10 p-4 rounded-lg text-center">Standard</div>
                        <div class="bg-white/10 p-4 rounded-lg text-center">Suite</div>
                        <div class="bg-white/10 p-4 rounded-lg text-center">Twin</div>
                        <div class="bg-white/10 p-4 rounded-lg text-center">Double</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Cahaya Resort</h3>
                    <p class="text-gray-400">Subscribe to our newsletter</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Book Meeting</li>
                        <li>Workspace</li>
                        <li>Office</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">About</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Our Story</li>
                        <li>Benefits</li>
                        <li>Team</li>
                        <li>Careers</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Help</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>FAQs</li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 flex justify-between items-center">
                <div class="text-gray-400">
                    <a href="#" class="mr-4">Terms & Conditions</a>
                    <a href="#" class="mr-4">Privacy Policy</a>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
