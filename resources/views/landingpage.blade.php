@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Welcome to Cahaya Resort Pangururan')

@section('head')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Calendar Modal */
    .calendar-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }

    .calendar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .calendar-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 600px;
    }

    /* FullCalendar Customization */
    .fc {
        font-family: inherit;
        background: white;
        padding: 10px;
        border-radius: 8px;
    }

    .fc .fc-toolbar {
        margin-bottom: 1.5em;
    }

    .fc .fc-toolbar-title {
        font-size: 1.2em;
        font-weight: 600;
    }

    .fc .fc-button-primary {
        background-color: #f59e0b;
        border-color: #f59e0b;
        font-weight: 500;
        text-transform: capitalize;
        padding: 0.5em 0.85em;
    }

    .fc .fc-button-primary:hover {
        background-color: #d97706;
        border-color: #d97706;
    }

    .fc .fc-button-primary:disabled {
        background-color: #fcd34d;
        border-color: #fcd34d;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background-color: #fef3c7;
    }

    .fc .fc-highlight {
        background-color: #fde68a;
    }

    .fc .fc-day-past {
        background-color: #f3f4f6;
    }

    .fc .fc-day-disabled {
        background-color: #fee2e2;
        text-decoration: line-through;
        opacity: 0.7;
    }

    .fc .fc-day {
        cursor: pointer;
    }

    .fc .fc-day:hover {
        background-color: #f3f4f6;
    }

    .fc .fc-day.selected {
        background-color: #fef3c7;
    }

    /* Calendar Actions */
    .calendar-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .calendar-actions button {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel {
        background-color: #e5e7eb;
        color: #4b5563;
        border: none;
    }

    .btn-cancel:hover {
        background-color: #d1d5db;
    }

    .btn-apply {
        background-color: #f59e0b;
        color: white;
        border: none;
    }

    .btn-apply:hover {
        background-color: #d97706;
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen">
        <!-- Hero Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('storage/images/header.png') }}" alt="Resort View" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
  </div>

        <!-- Hero Content -->
        <div class="relative h-full flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-3xl -ml-20 -mt-40">
                    <h1 class="tracking-wider text-5xl md:text-7xl font-semibold text-white mb-6 drop-shadow-lg">
                        CAHAYA RESORT<br>
                        PANGURUAN
            </h1>

                    <!-- Description Box -->
                    <div class="absolute bottom-32 right-0 max-w-lg">
                        <div class="flex gap-4">
                            <div class="w-1 bg-orange-500"></div>
                            <div>
                                <p class="text-white text-2xl font-semibold mb-2">
                                    We provide a variety of the best lodging accommodations for those of you who need it.
                                </p>
                                <p class="text-white/80 text-sm">
                                    Don't worry about the quality of the service.
                                </p>
            </div>
        </div>
            </div>
            
                    <!-- Booking Form -->
                    <form id="searchForm" class="mt-10 bg-black/40 backdrop-blur-md p-4 rounded-xl inline-flex items-center gap-4">
                        <!-- Check-in -->
                        <div class="flex items-center gap-2 bg-black/30 px-4 py-2 rounded-lg cursor-pointer" onclick="openCalendar('check_in')">
                            <i class="fas fa-calendar text-white"></i>
                            <input type="text" 
                                   id="landing_check_in"
                                   name="check_in"
                                   class="bg-transparent text-white border-none focus:outline-none placeholder-white w-32" 
                                   placeholder="Check in"
                                   readonly>
                        </div>
                            
                        <!-- Check-out -->
                        <div class="flex items-center gap-2 bg-black/30 px-4 py-2 rounded-lg cursor-pointer" onclick="openCalendar('check_out')">
                            <i class="fas fa-calendar text-white"></i>
                            <input type="text" 
                                   id="landing_check_out"
                                   name="check_out"
                                   class="bg-transparent text-white border-none focus:outline-none placeholder-white w-32" 
                                   placeholder="Check out"
                                   readonly>
                        </div>
            
                        <!-- Room & Guests -->
                        <div class="flex items-center gap-2 bg-black/30 px-4 py-2 rounded-lg">
                            <i class="fas fa-house text-white"></i>
                            <select id="landing_room_guests"
                                    name="guests"
                                    class="bg-transparent text-white focus:outline-none">
                                <option value="1-2" class="text-black">1 Room, 2 guest</option>
                                <option value="2-4" class="text-black">2 Rooms, 4 guests</option>
                            </select>
                        </div>
                
                        <!-- Search Button -->
                        <button type="submit" class="bg-orange-500 text-white font-semibold px-6 py-3 rounded-lg hover:bg-orange-600 transition-all">
                            Search
                        </button>
                    </form>
                
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
        <div class="absolute -top-12 left-0 right-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <div class="custom-curve-top px-32 py-3 -mt-2">
                        <h2 class="text-2xl font-bold text-gray-800 tracking-wider	">WELCOME TO CAHAYA RESORT</h2>
                    </div>
                </div>
                            </div>
                        </div>
                        
        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-20  ">
            <p class="text-black-600 text-center mb-12 font-bold text-2xl">Room Choice in Cahaya Resort</p>

            <!-- Room Carousel -->
            <div class="relative px-12">
                <!-- Previous Button -->
                <button class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg z-10 flex items-center justify-center group carousel-prev">
                    <i class="fas fa-chevron-left text-gray-400 group-hover:text-gray-600"></i>
                </button>

                <!-- Next Button -->
                <button class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg z-10 flex items-center justify-center group carousel-next">
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                </button>

                <!-- Carousel Container -->
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500" id="roomSlider">
                        <!-- Original items -->
                        @foreach($rooms as $index => $room)
                        <div class="flex-none w-[300px] mx-3" data-index="{{ $index }}">
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition-all duration-500">
                                <div class="relative">
                                    <img src="{{ asset('storage/images/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-48 object-cover">
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-semibold text-lg">{{ $room->name }}</h3>
                                        <p class="text-orange-500 font-medium">Rp. {{ number_format($room->price_per_night, 0, ',', '.') }}</p>
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
                <button class="w-2 h-2 rounded-full bg-gray-300 transition-all duration-300" data-index="{{ $index }}"></button>
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
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Why Cahaya Resort?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                        <div>
                    <h3 class="text-xl font-medium mb-4">Traditional</h3>
                    <p class="text-gray-400">From local hotels to grand resorts, discover folklore of hotels all around the world.</p>
                        </div>
                        <div>
                    <h3 class="text-xl font-medium mb-4">Modern</h3>
                    <p class="text-gray-400">No need to search anywhere else. The biggest names in hotels are right here.</p>
                        </div>
                        <div>
                    <h3 class="text-xl font-medium mb-4">Affordable</h3>
                    <p class="text-gray-400">We've scored deals with the world's leading hotels and we share savings with you.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dream in Serene Luxury Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative">
                <!-- Left Content -->
                <div class="max-w-xl">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                        Dream In <span class="text-gray-600">Serene Luxury</span>
                    </h2>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Cahaya Pangururan Inn, comfortable with local nuances and natural panorama of Samosir. Wake up with cool air, calming lake views, and a calm atmosphere that refreshes the soul. Enjoy the hospitality of the host, complete facilities, and comfort like at home.
                    </p>
                </div>

                <!-- Stay & Relax Text -->
                <div class="absolute right-0 top-0">
                    <h3 class="text-6xl font-bold text-gray-100">Stay & Relax</h3>
                </div>

                <!-- Facility Gallery -->
                <div class="mt-16 relative">
                    <!-- Gallery Container -->
                    <div class="overflow-hidden">
                        <div class="flex gap-6" id="facilitySlider">
                            <!-- Facility Items -->
                            <div class="flex gap-6">
                                <!-- Rooms -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-1.jpg') }}" alt="Rooms" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <p class="text-sm font-medium"></p>
                                    </div>
                                </div>

                                <!-- Parking Area -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-2.jpg') }}" alt="Parking Area" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110 grayscale">
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <p class="text-sm font-medium"></p>
                                    </div>
                                </div>

                                <!-- Mini Park -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-3.jpg') }}" alt="Mini Park" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <p class="text-sm font-medium"></p>
                            </div>
                                </div>

                                <!-- Loby -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-4.jpg') }}" alt="Loby" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <p class="text-sm font-medium"></p>
                                </div>
                            </div>
                            
                                <!-- View -->
                                <div class="relative group w-[220px] flex-none overflow-hidden rounded-2xl">
                                    <img src="{{ asset('storage/images/facility-5.jpg') }}" alt="View" 
                                         class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <p class="text-sm font-medium"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                    <!-- Navigation Arrows -->
                    <button class="absolute top-1/2 -left-4 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center group focus:outline-none">
                        <i class="fas fa-chevron-left text-gray-400 group-hover:text-gray-600"></i>
                    </button>
                    <button class="absolute top-1/2 -right-4 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center group focus:outline-none">
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                    </button>

                    <!-- Navigation Dots -->
                    <div class="flex justify-center mt-8 space-x-2">
                        <div class="w-12 h-1 bg-gray-800 rounded"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stay in the know Section -->
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Stay in the know</h2>
                    <p class="mb-6">Sign up to get marketing emails from Cahaya Resort, including promotions, rewards, and information about Cahaya Resort services.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="flex-1 bg-white/10 px-4 py-2 rounded-l focus:outline-none" required>
                        <button class="bg-orange-500 text-white px-6 py-2 rounded-r hover:bg-orange-600 transition">
                            Subscribe
                        </button>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-6">Location</h3>
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

    <!-- Ready to get started Section -->
    <section class="bg-gray-900 text-white py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-lg">
                    Cahaya Resort
                </div>
                <div class="flex items-center">
                    <span class="mr-4">Ready to get started?</span>
                    <button class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition">
                        Get Started
                    </button>
                </div>
            </div>
    </div>
    </section>

    <!-- Calendar Modal -->
    <div class="calendar-modal" id="calendarModal">
        <div class="calendar-overlay"></div>
        <div class="calendar-container">
            <div id="calendar"></div>
            <div class="calendar-actions">
                <button class="btn-cancel" onclick="closeCalendar()">Cancel</button>
                <button class="btn-apply" onclick="applyDates()">Apply</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- FullCalendar Scripts -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.calendar = null;
    window.selectedStartDate = null;
    window.selectedEndDate = null;
    window.currentInputType = null;

    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // Initialize calendar
    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        selectMirror: true,
        unselectAuto: false,
        dateClick: handleDateClick,
        validRange: {
            start: new Date()
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        }
    });

    window.calendar.render();
});

// Calendar Functions
window.openCalendar = function(type) {
    window.currentInputType = type;
    document.querySelector('.calendar-modal').style.display = 'block';
    if (window.calendar) {
        window.calendar.render();
        
        // Set appropriate valid range based on input type
        if (type === 'check_out' && window.selectedStartDate) {
            const nextDay = new Date(window.selectedStartDate);
            nextDay.setDate(nextDay.getDate() + 1);
            window.calendar.setOption('validRange', {
                start: nextDay
            });
        } else {
            window.calendar.setOption('validRange', {
                start: new Date()
            });
        }
    }
};

window.closeCalendar = function() {
    document.querySelector('.calendar-modal').style.display = 'none';
};

window.applyDates = function() {
    if (window.currentInputType === 'check_in' && window.selectedStartDate) {
        document.getElementById('landing_check_in').value = formatDateForDisplay(window.selectedStartDate);
        // Clear check-out if it's before new check-in
        if (window.selectedEndDate && window.selectedEndDate <= window.selectedStartDate) {
            window.selectedEndDate = null;
            document.getElementById('landing_check_out').value = '';
        }
        closeCalendar();
        // Automatically open check-out selection
        setTimeout(() => openCalendar('check_out'), 100);
    } else if (window.currentInputType === 'check_out' && window.selectedEndDate) {
        document.getElementById('landing_check_out').value = formatDateForDisplay(window.selectedEndDate);
        closeCalendar();
    }
};

window.formatDateForDisplay = function(date) {
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Add a new function to format date for form submission
window.formatDateForSubmit = function(date) {
    return date.toISOString().split('T')[0]; // Returns YYYY-MM-DD format
};

// Add form submit handler
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get the dates
            const checkIn = window.selectedStartDate;
            const checkOut = window.selectedEndDate;
            
            if (!checkIn || !checkOut) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please Select Dates',
                    text: 'You must select both check-in and check-out dates.',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            
            // Format dates for the request
            const formattedCheckIn = formatDateForSubmit(checkIn);
            const formattedCheckOut = formatDateForSubmit(checkOut);
            const guests = document.getElementById('landing_room_guests').value;
            
            try {
                const response = await fetch(`{{ route('kamar.index') }}?check_in=${formattedCheckIn}&check_out=${formattedCheckOut}&guests=${guests}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const roomsContainer = doc.querySelector('#rooms-container');
                
                if (roomsContainer) {
                    const roomsContent = document.getElementById('roomsContent');
                    if (roomsContent) {
                        roomsContent.innerHTML = doc.querySelector('.min-h-screen').innerHTML;
                        hidePanel();
                        document.getElementById('roomsPanel').classList.add('show');
                        
                        // Update search inputs in the panel
                        const searchCheckIn = roomsContent.querySelector('#search_check_in');
                        const searchCheckOut = roomsContent.querySelector('#search_check_out');
                        const searchGuests = roomsContent.querySelector('#search_guests');
                        
                        if (searchCheckIn) searchCheckIn.value = formattedCheckIn;
                        if (searchCheckOut) searchCheckOut.value = formattedCheckOut;
                        if (searchGuests) searchGuests.value = guests;
                    } else {
                        throw new Error('Rooms content container not found');
                    }
                } else {
                    throw new Error('Rooms container not found in response');
                }
            } catch (error) {
                console.error('Error loading rooms:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load available rooms. Please try again.',
                    confirmButtonColor: '#f59e0b'
                });
            }
        });
    }
});

window.handleDateClick = function(info) {
    const clickedDate = new Date(info.dateStr);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (clickedDate < today) {
        return; // Prevent selecting past dates
    }

    if (window.currentInputType === 'check_in') {
        window.selectedStartDate = clickedDate;
        highlightDates();
    } else if (window.currentInputType === 'check_out') {
        if (!window.selectedStartDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Check-in First',
                text: 'Please select a check-in date before selecting check-out date.',
                confirmButtonColor: '#f59e0b'
            });
            closeCalendar();
            openCalendar('check_in');
            return;
        }
        if (clickedDate <= window.selectedStartDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date',
                text: 'Check-out date must be after check-in date.',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        window.selectedEndDate = clickedDate;
        highlightDates();
    }
};

window.highlightDates = function() {
    window.calendar.getEvents().forEach(event => event.remove());
    
    if (window.selectedStartDate) {
        // Create a new date object for end date to avoid modifying the original
        let displayEndDate = window.selectedEndDate ? new Date(window.selectedEndDate) : window.selectedStartDate;
        // Subtract one day from the end date for display purposes
        displayEndDate.setDate(displayEndDate.getDate() - 1);
        
        window.calendar.addEvent({
            start: window.selectedStartDate,
            end: displayEndDate,
            display: 'background',
            backgroundColor: '#fef3c7'
        });
    }
};

window.formatDate = function(date) {
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Event Listeners
document.querySelector('.calendar-overlay')?.addEventListener('click', closeCalendar);
</script>
@endpush
