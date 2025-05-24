@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Welcome to Cahaya Resort Pangururan')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen">
        <!-- Hero Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('storage/images/header.png') }}" alt="Resort View" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/40"></div>
  </div>

        <!-- Hero Content -->
        <div class="relative flex items-center h-full">
            <div class="w-full px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="max-w-3xl -mt-40 ml-0">
                    <h1 class="mb-6 text-5xl font-medium tracking-wider text-white md:text-7xl drop-shadow-lg" style="font-family:'Poppins',sans-serif; font-weight:500;">
                        CAHAYA RESORT<br>
                        PANGURUAN
                    </h1>

                    <!-- Description Box -->
                    <div class="absolute right-20 max-w-lg bottom-32">
                        <div class="flex gap-4">
                            <div class="w-1 bg-orange-500"></div>
                            <div>
                                <p class="mb-2 text-2xl font-semibold text-white">
                                    We provide a variety of the best lodging accommodations for those of you who need it.
                                </p>
                                <p class="text-sm text-white/80">
                                    Don't worry about the quality of the service.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            
                    <!-- Booking Form -->
                    <div class="inline-flex items-center gap-4 p-4 mt-10 bg-black/40 backdrop-blur-md rounded-xl">
                <!-- Check-in -->
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/30">
                    <i class="text-white fas fa-calendar"></i>
                    <input type="date" 
                           id="landing_check_in"
                           name="landing_check_in"
                           class="text-white placeholder-white bg-transparent border-none focus:outline-none" 
                           placeholder="Check in">
                            </div>
                            
                <!-- Check-out -->
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/30">
                    <i class="text-white fas fa-calendar"></i>
                    <input type="date" 
                           id="landing_check_out"
                           name="landing_check_out"
                           class="text-white placeholder-white bg-transparent border-none focus:outline-none" 
                           placeholder="Checkout">
            </div>
            
                <!-- Room & Guests -->
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/30">
                    <i class="text-white fas fa-house"></i>
                    <select id="landing_room_guests"
                            name="landing_room_guests"
                            class="text-white bg-transparent focus:outline-none">
                        <option value="1-2" class="text-black">1 Room, 2 guest</option>
                        <option value="2-4" class="text-black">2 Rooms, 4 guests</option>
                    </select>
                </div>
                
                <!-- Search Button -->
                <button class="px-6 py-3 font-semibold text-white transition-all rounded-lg" style="background:#FFA040; font-family:'Poppins',sans-serif; font-weight:600;" onmouseover="this.style.background='#ff8c1a'" onmouseout="this.style.background='#FFA040'">
                    Search
                </button>
                </div>
                
                </div>
            </div>
        </div>
    </section>

    <!-- Room Choice Section -->
    <section class="relative bg-white">
        <style>
            .custom-curve-top {
                position: relative;
                background: #ffffff;
                border-radius: 100px 100px 0 0;
            }
            .custom-curve-top::before,
            .custom-curve-top::after {
                content: '';
                position: absolute;
                bottom: 0;
                width: 40px;
                height: 40px;
                background-color: transparent;
            }
            .custom-curve-top::before {
                left: -20px;
                border-bottom-right-radius: 20px;
                box-shadow: 10px 0 0 0 #ffffff;
            }
            .custom-curve-top::after {
                right: -20px;
                border-bottom-left-radius: 20px;
                box-shadow: -10px 0 0 0 #ffffff;
            }
        </style>

        <!-- Curved Welcome Section -->
        <div class="absolute left-0 right-0 -top-12">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <div class="px-32 py-3 -mt-2" style="background:#fff; clip-path: polygon(10% 0, 90% 0, 100% 100%, 0% 100%); box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);">
                        <h2 class="text-2xl font-bold tracking-wider text-gray-800 " style="font-family:'Poppins',sans-serif;">WELCOME TO CAHAYA RESORT</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="px-4 pt-20 pb-20 mx-auto max-w-7xl sm:px-6 lg:px-8 ">
            <p class="mb-12 text-2xl font-bold text-center text-black-600">Room Choice in Cahaya Resort</p>

            <!-- Room Carousel -->
            <div class="relative px-12">
                <!-- Previous Button -->
                <button class="absolute left-0 z-10 flex items-center justify-center w-10 h-10 -translate-y-1/2 bg-white rounded-full shadow-lg top-1/2 group carousel-prev">
                    <i class="text-gray-400 fas fa-chevron-left group-hover:text-gray-600"></i>
                </button>

                <!-- Next Button -->
                <button class="absolute right-0 z-10 flex items-center justify-center w-10 h-10 -translate-y-1/2 bg-white rounded-full shadow-lg top-1/2 group carousel-next">
                    <i class="text-gray-400 fas fa-chevron-right group-hover:text-gray-600"></i>
                </button>

                <!-- Carousel Container -->
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500" id="roomSlider">
                        <!-- Original items -->
                        @foreach($rooms as $index => $room)
                        <div class="flex-none w-[300px] mx-3" data-index="{{ $index }}">
                            <div class="overflow-hidden transition-all duration-500 transform bg-white shadow-lg rounded-xl">
                                <div class="relative">
                                    <img src="{{ asset('storage/images/' . $room->image) }}" alt="{{ $room->name }}" class="object-cover w-full h-48">
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-semibold">{{ $room->name }}</h3>
                                        <p class="font-medium text-orange-500">Rp. {{ number_format($room->price_per_night, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Pangururan</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $room->capacity }} Guest</span>
                            </div>
                        </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Carousel Navigation Dots -->
            <div class="flex justify-center gap-2 mt-8" id="carouselDots">
                @foreach($rooms as $index => $room)
                <button class="w-2 h-2 transition-all duration-300 bg-gray-300 rounded-full" data-index="{{ $index }}"></button>
                @endforeach
            </div>

            <!-- Carousel Script -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const slider = document.getElementById('roomSlider');
                    const cards = Array.from(slider.children);
                    const dots = Array.from(document.querySelectorAll('#carouselDots button'));
                    const totalCards = cards.length;
                    let currentIndex = 0;
                    const cardWidth = 300;
                    const cardGap = 24;
                    let autoSlideInterval;

                    function updateSlider() {
                        const containerWidth = slider.parentElement.offsetWidth;
                        const centerPosition = (containerWidth - cardWidth) / 2;
                        const offset = centerPosition - (currentIndex * (cardWidth + cardGap));
                        
                        slider.style.transform = `translateX(${offset}px)`;
                        
                        // Update cards appearance
                        cards.forEach((card, index) => {
                            const distance = Math.abs(index - currentIndex);
                            const cardElement = card.querySelector('.bg-white');
                            
                            if (distance === 0) {
                                cardElement.style.transform = 'scale(1.1) translateY(-20px)';
                                card.style.opacity = '1';
                                card.style.zIndex = '20';
                            } else if (distance === 1) {
                                const direction = index > currentIndex ? 1 : -1;
                                cardElement.style.transform = `scale(0.9) translateX(${direction * 20}px)`;
                                card.style.opacity = '0.7';
                                card.style.zIndex = '10';
                            } else {
                                const direction = index > currentIndex ? 1 : -1;
                                cardElement.style.transform = `scale(0.8) translateX(${direction * 40}px)`;
                                card.style.opacity = '0.5';
                                card.style.zIndex = '1';
                            }
                        });

                        // Update dots
                        dots.forEach((dot, index) => {
                            if (index === currentIndex) {
                                dot.classList.add('bg-gray-800', 'w-4');
                                dot.classList.remove('bg-gray-300', 'w-2');
                            } else {
                                dot.classList.add('bg-gray-300', 'w-2');
                                dot.classList.remove('bg-gray-800', 'w-4');
                            }
                        });
                    }

                    function nextSlide() {
                        currentIndex = (currentIndex + 1) % totalCards;
                        slider.style.transition = 'transform 500ms ease';
                        updateSlider();
                    }

                    function prevSlide() {
                        currentIndex = (currentIndex - 1 + totalCards) % totalCards;
                        slider.style.transition = 'transform 500ms ease';
                        updateSlider();
                    }

                    function startAutoSlide() {
                        if (autoSlideInterval) clearInterval(autoSlideInterval);
                        autoSlideInterval = setInterval(nextSlide, 2000);
                    }

                    function stopAutoSlide() {
                        if (autoSlideInterval) {
                            clearInterval(autoSlideInterval);
                            autoSlideInterval = null;
                        }
                    }

                    // Event Listeners
                    document.querySelector('.carousel-prev').addEventListener('click', () => {
                        stopAutoSlide();
                        prevSlide();
                        startAutoSlide();
                    });

                    document.querySelector('.carousel-next').addEventListener('click', () => {
                        stopAutoSlide();
                        nextSlide();
                        startAutoSlide();
                    });

                    dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            stopAutoSlide();
                            currentIndex = index;
                            slider.style.transition = 'transform 500ms ease';
                            updateSlider();
                            startAutoSlide();
                        });
                    });

                    slider.addEventListener('mouseenter', stopAutoSlide);
                    slider.addEventListener('mouseleave', startAutoSlide);
                    window.addEventListener('resize', updateSlider);

                    // Initialize
                    slider.style.transition = 'transform 500ms ease';
                    updateSlider();
                    startAutoSlide();
                });
            </script>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 text-white" style="background:#1D1D1D;">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="mb-12 text-center font-bold" style="font-size:2.5rem; letter-spacing:0.10em; font-family:'Poppins',sans-serif;">Why Cahaya Resort ?</h2>
            <div class="grid grid-cols-1 gap-12 text-center md:grid-cols-3">
                <div>
                    <h3 class="mb-4 text-xl font-bold" style="font-family:'Poppins',sans-serif;">Traditional</h3>
                    <p class="text-gray-400">From local hotels to grand resorts, discover folklore of hotels all around the world.</p>
                </div>
                <div>
                    <h3 class="mb-4 text-xl font-bold" style="font-family:'Poppins',sans-serif;">Modern</h3>
                    <p class="text-gray-400">No need to search anywhere else. The biggest names in hotels are right here.</p>
                </div>
                <div>
                    <h3 class="mb-4 text-xl font-bold" style="font-family:'Poppins',sans-serif;">Affordable</h3>
                    <p class="text-gray-400">We've scored deals with the world's leading hotels and we share savings with you.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dream in Serene Luxury Section -->
    <section class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="relative">
                <!-- Left Content -->
                <div class="max-w-xl">
                    <h2 class="flex items-center gap-3 mb-6 text-3xl font-semibold text-gray-800">
                        Dream In <span class="text-gray-600">Serene Luxury</span>
                    </h2>
                    <p class="mb-8 leading-relaxed text-gray-600">
                        Cahaya Pangururan Inn, comfortable with local nuances and natural panorama of Samosir. Wake up with cool air, calming lake views, and a calm atmosphere that refreshes the soul. Enjoy the hospitality of the host, complete facilities, and comfort like at home.
                    </p>
                </div>

                <!-- Stay & Relax Text -->
                <div class="absolute top-0 right-0">
                    <h3 class="text-6xl font-bold" 
                        style="color: #080808;
                            text-shadow: 0 4px 4px rgba(0, 0, 0, 0.25) inset;
                            -webkit-text-fill-color: transparent;
                            -webkit-background-clip: text;
                            background-image: linear-gradient(to bottom, #080808, #080808);">
                        Stay & Relax
                    </h3>
                </div>

                <!-- Facility Gallery -->
                <div class="relative mt-16">
                    <!-- Gallery Container -->
                    <div class="overflow-hidden">
                        <div class="flex gap-6" id="facilitySlider">
                            <!-- Facility Items -->
                            <div class="flex gap-6">
                                <!-- Rooms -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-1.jpg') }}" alt="Rooms" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 backdrop-blur-sm">
                                        <span class="text-white text-lg font-bold" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>

                                <!-- Parking Area -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-2.jpg') }}" alt="Parking Area" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110 grayscale">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 backdrop-blur-sm">
                                        <span class="text-white text-lg font-bold" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>

                                <!-- Mini Park -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-3.jpg') }}" alt="Mini Park" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 backdrop-blur-sm">
                                        <span class="text-white text-lg font-bold" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>

                                <!-- Loby -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-4.jpg') }}" alt="Loby" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 backdrop-blur-sm">
                                        <span class="text-white text-lg font-bold" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>
                                
                                <!-- View -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-5.jpg') }}" alt="View" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 backdrop-blur-sm">
                                        <span class="text-white text-lg font-bold" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stay in the know Section -->
    <section class="py-20 text-white" style="background:#252525; font-family:'Poppins',sans-serif;">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                <div class="bg-white/5 rounded-xl p-8 md:p-10 shadow-sm">
                    <h2 class="mb-4 text-2xl font-bold" style="font-family:'Poppins',sans-serif; font-weight:700;">Stay in the know</h2>
                    <p class="mb-6 text-[1.1rem] font-normal" style="font-family:'Poppins',sans-serif; font-weight:400;">Sign up to get marketing emails from Cahaya Resort, including promotions, rewards, and information about Cahaya Resort services.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Your email" class="flex-1 px-5 py-2 rounded-lg bg-white text-gray-800 focus:outline-none font-normal" style="font-family:'Poppins',sans-serif; font-weight:400; font-size:1rem;" required>
                        <button class="px-6 py-2 text-white transition rounded-lg font-semibold" style="background:#FFA040; font-family:'Poppins',sans-serif; font-weight:500; font-size:1rem;" onmouseover="this.style.background='#ff8c1a'" onmouseout="this.style.background='#FFA040'">
                            Send it
                        </button>
                    </div>
                </div>
                <div class="relative bg-white/5 rounded-xl p-8 md:p-10 shadow-sm md:border-l md:pl-12 border-gray-400/20 flex flex-col justify-center">
                    <span class="absolute top-8 left-0 hidden md:block w-0.5 h-[80%] bg-gradient-to-b from-transparent via-gray-400/20 to-transparent rounded-full"></span>
                    <h3 class="mb-6 text-2xl font-bold" style="font-family:'Poppins',sans-serif; font-weight:700;">Location</h3>
                    <div class="w-full h-[300px] rounded-lg overflow-hidden">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m12!1m8!1m3!1d31885.646008294185!2d98.701418!3d2.6016867!3m2!1i1024!2i768!4f13.1!2m1!1spenginapan%20cahaya%20pangururan!5e0!3m2!1sid!2sid!4v1748025674249!5m2!1sid!2sid"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy"
                            class="rounded-lg"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Parallax Section -->
    <section class="relative h-[500px] flex items-center justify-center" style="background-image: url('{{ asset('storage/images/bg_fixed.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 flex flex-col items-center justify-center w-full h-full px-4">
            <h2 class="text-white text-2xl md:text-4xl lg:text-3xl xl:text-4xl font-semibold tracking-widest text-center uppercase mb-8" style="font-family:'Poppins',sans-serif; letter-spacing:0.15em;">
            "UNWIND BY THE WATER. A LAKESIDE ESCAPE CRAFTED FOR TIMELESS MOMENTS."
            </h2>
            <button class="mt-2 px-10 py-3 rounded-md bg-gray-300/70 text-gray-700 font-semibold tracking-widest uppercase text-base md:text-lg transition hover:bg-gray-400/80" style="font-family:'Poppins',sans-serif;">
                CHECK RATE
            </button>
        </div>
    </section>

    <!-- Footer Section -->
    <section class="pt-16 pb-8 text-white" style="background:#1D1D1D;">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-5xl font-semibold text-center mb-8" style="font-family:'Poppins',sans-serif;">Cahaya Resort</h2>
            <hr class="border-t border-gray-700 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <!-- Newsletter -->
                <div>
                    <h3 class="mb-4 text-xl font-medium" style="font-family:'Poppins',sans-serif;">Ready to get started?</h3>
                    <div class="flex items-center mt-8">
                        <button type="button" class="w-full md:w-auto px-10 py-3 bg-[#D2A06E] text-white rounded-lg font-semibold text-xl flex items-center justify-center" style="font-family:'Poppins',sans-serif;">
                            Get Started
                        </button>
                    </div>
                </div>
                <!-- Services -->
                <div>
                    <h4 class="mb-2 text-lg font-semibold text-[#FFA040]" style="font-family:'Poppins',sans-serif;">Services</h4>
                    <ul class="space-y-2 text-base">
                        <li>Email Marketing</li>
                        <li>Campaigns</li>
                        <li>Branding</li>
                        <li>Offline</li>
                    </ul>
                </div>
                <!-- About -->
                <div>
                    <h4 class="mb-2 text-lg font-semibold text-[#FFA040]" style="font-family:'Poppins',sans-serif;">About</h4>
                    <ul class="space-y-2 text-base">
                        <li>Our Story</li>
                        <li>Benefits</li>
                        <li>Team</li>
                        <li>Careers</li>
                    </ul>
                </div>
                <!-- Help -->
                <div>
                    <h4 class="mb-2 text-lg font-semibold text-[#FFA040]" style="font-family:'Poppins',sans-serif;">Help</h4>
                    <ul class="space-y-2 text-base">
                        <li>FAQs</li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-between border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row items-center gap-8 w-full md:w-auto">
                    <span class="text-sm">Terms & Conditions</span>
                    <span class="text-sm">Privacy Policy</span>
                </div>
                <div class="flex items-center gap-6 mt-6 md:mt-0">
                    <a href="#" class="text-2xl"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-2xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-2xl"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </section>
@endsection
