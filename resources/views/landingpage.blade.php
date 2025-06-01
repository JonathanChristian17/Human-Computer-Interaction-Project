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
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

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
        font-family: 'Poppins', sans-serif;
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

    .custom-alert {
      width: 24em;
      min-height: 4.5em;
      background: #171717;
      color: white;
      border-radius: 20px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 1em;
      padding: 1.2em 2em;
      position: fixed;
      top: 2em;
      left: 50%;
      transform: translateX(-50%);
      z-index: 9999;
      font-family: 'Poppins', sans-serif;
      font-size: 1.2em;
      animation: fadeInDown 0.5s;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px) translateX(-50%);}
      to { opacity: 1; transform: translateY(0) translateX(-50%);}
    }
    @keyframes fadeOutUp {
      from { opacity: 1; transform: translateY(0) translateX(-50%);}
      to   { opacity: 0; transform: translateY(-30px) translateX(-50%);}
    }
    .custom-alert.success {
      border-left: 10px solid #22c55e;
    }
    .custom-alert.error {
      border-left: 10px solid #ef4444;
    }
    .custom-alert.warning {
      border-left: 10px solid #f59e0b;
    }
    .alert-icon {
      font-size: 2.5em;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .room-guest-dropdown {
      position: relative;
      min-width: 220px;
      font-family: 'Poppins', sans-serif;
      z-index: 9999 !important;
    }
    .dropdown-link {
      background: rgba(0,0,0,0.3);
      color: #fff;
      border-radius: 12px;
      padding: 12px 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      position: relative;
      transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
      z-index: 10000 !important;
    }
    .dropdown-link svg {
      width: 14px;
      height: 14px;
      fill: #fff;
      transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .dropdown-menu {
      position: absolute;
      top: 110%;
      left: 0;
      width: 100%;
      background: #fff;
      border-radius: 0 0 12px 12px;
      box-shadow: 0 8px 32px 0 rgba(0,0,0,0.18);
      opacity: 0;
      visibility: hidden;
      transform: translateY(-12px);
      transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
      z-index: 10001 !important;
      pointer-events: none;
      border: 2px solid #FFA040;
    }
    .room-guest-dropdown.open .dropdown-menu {
      display: block !important;
      opacity: 1 !important;
      visibility: visible !important;
      transform: translateY(0) !important;
      pointer-events: auto !important;
      z-index: 99999 !important;
      background: #FFA040 !important;
      color: #fff !important;
      box-shadow: 0 12px 32px 0 rgba(0,0,0,0.22) !important;
      border: 2px solid #FFA040 !important;
    }
    .dropdown-item {
      padding: 12px 24px;
      color: #222;
      cursor: pointer;
      text-align: center;
      transition: background 0.3s, color 0.3s;
      background: transparent;
    }
    .dropdown-item:hover, .dropdown-item.selected {
      background: #fff !important;
      color: #FFA040 !important;
    }

    /* Room & Guests Custom Select */
    .select {
      width: fit-content;
      min-width: 180px;
      cursor: pointer;
      position: relative;
      transition: 300ms;
      color: white;
      overflow: visible;
      font-family: 'Poppins', sans-serif;
    }
    .selected {
      background-color: rgba(0, 0, 0, 0.3);
      padding: 10px 16px;
      border-radius: 8px;
      position: relative;
      z-index: 100000;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      color: #fff;
      min-height: 40px;
      width: 100%;
      box-sizing: border-box;
      gap: 10px;
    }
    .arrow {
      margin-left: auto;
      height: 14px;
      width: 25px;
      fill: white;
      z-index: 100000;
      transition: 300ms;
    }
    .options {
      display: flex;
      flex-direction: column;
      border-radius: 8px;
      padding: 8px 0;
      background-color: rgba(0, 0, 0, 0.3);
      position: absolute;
      left: 0;
      top: 100%;
      min-width: 180px;
      width: 100%;
      opacity: 0;
      pointer-events: none;
      box-shadow: 0 8px 32px 0 rgba(0,0,0,0.18);
      border: 2px solid #FFA040;
      transition: opacity 0.3s, top 0.3s;
      z-index: 10001;
    }
    .select:hover > .options {
      opacity: 1;
      pointer-events: auto;
    }
    .select:hover > .selected .arrow {
      transform: rotate(0deg);
    }
    .option {
      border-radius: 8px;
      padding: 12px 24px;
      transition: background 0.3s, color 0.3s, transform 0.3s;
      background-color: rgba(0, 0, 0, 0.3);
      width: 100%;
      font-size: 1rem;
      color: #fff;
      text-align: left;
      box-sizing: border-box;
      cursor: pointer;
    }
    .option:hover, .options input[type="radio"]:focus + label {
      background-color: #FFA040 !important;
      color: #fff !important;
      transform: translateX(8px) scale(1.04);
    }
    .options input[type="radio"] {
      display: none;
    }
    .options label {
      display: inline-block;
    }
    .options label::before {
      content: attr(data-txt);
    }
    .options input[type="radio"]:checked + label {
      display: none;
    }
    .options input[type="radio"]#all:checked + label {
      display: none;
    }
    .select:has(.options input[type="radio"]#all:checked) .selected::before {
      content: attr(data-default);
    }
    .select:has(.options input[type="radio"]#option-1:checked) .selected::before {
      content: attr(data-one);
    }
    .select:has(.options input[type="radio"]#option-2:checked) .selected::before {
      content: attr(data-two);
    }
    .select:has(.options input[type="radio"]#option-3:checked) .selected::before {
      content: attr(data-three);
    }
</style>
@endsection

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
                <form id="searchForm" class="inline-flex items-center gap-4 p-4 mt-10 bg-black/40 backdrop-blur-md rounded-xl" style="overflow:visible; z-index:9999;">
                    <!-- Check-in -->
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/30 cursor-pointer" onclick="openCalendar('check_in')">
                        <i class="text-white fas fa-calendar"></i>
                        <input type="text" 
                               id="landing_check_in"
                               name="check_in"
                               class="text-white placeholder-white bg-transparent border-none focus:outline-none w-32" 
                               placeholder="Check in"
                               readonly>
                    </div>
                            
                    <!-- Check-out -->
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/30 cursor-pointer" onclick="openCalendar('check_out')">
                        <i class="text-white fas fa-calendar"></i>
                        <input type="text" 
                               id="landing_check_out"
                               name="check_out"
                               class="text-white placeholder-white bg-transparent border-none focus:outline-none w-32" 
                               placeholder="Check out"
                               readonly>
                    </div>
            
                    <!-- Room & Guests -->
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/30" style="height:48px;">
                      <div class="select" style="width:100%;">
                        <div
                          class="selected"
                          data-default="1 Room, 2 guest"
                          data-one="2 Rooms, 4 guests"
                          data-two="3 Rooms, 6 guests"
                          data-three="4 Rooms, 8 guests"
                        >
                          <i class="text-white fas fa-house" style="margin-right:8px;"></i>
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                            <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"></path>
                          </svg>
                        </div>
                        <div class="options">
                          <div title="1 Room, 2 guest">
                            <input id="all" name="guests" type="radio" value="1-2" checked />
                            <label class="option" for="all" data-txt="1 Room, 2 guest"></label>
                          </div>
                          <div title="2 Rooms, 4 guests">
                            <input id="option-1" name="guests" type="radio" value="2-4" />
                            <label class="option" for="option-1" data-txt="2 Rooms, 4 guests"></label>
                          </div>
                          <div title="3 Rooms, 6 guests">
                            <input id="option-2" name="guests" type="radio" value="3-6" />
                            <label class="option" for="option-2" data-txt="3 Rooms, 6 guests"></label>
                          </div>
                          <div title="4 Rooms, 8 guests">
                            <input id="option-3" name="guests" type="radio" value="4-8" />
                            <label class="option" for="option-3" data-txt="4 Rooms, 8 guests"></label>
                          </div>
                        </div>
                      </div>
                    </div>
                
                    <!-- Search Button -->
                    <button type="submit" class="px-6 py-3 font-semibold text-white transition-all rounded-lg" style="background:#FFA040; font-family:'Poppins',sans-serif; font-weight:600;" onmouseover="this.style.background='#ff8c1a'" onmouseout="this.style.background='#FFA040'">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Room Choice Section -->
    <section class="relative bg-white">
        <!-- Curved Welcome Section -->
        <div class="absolute left-0 right-0 -top-12">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <div class="px-32 py-3 -mt-2" style="background:#fff; clip-path: polygon(10% 0, 90% 0, 100% 100%, 0% 100%); box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);">
                        <h2 class="text-2xl font-bold tracking-wider text-gray-800" style="font-family:'Poppins',sans-serif;">WELCOME TO CAHAYA RESORT</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="px-4 pt-20 pb-20 mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                <div class="absolute top-0 right-0 pb-20">
                    <h3 class="text-6xl font-bold leading-tight"
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

    <!-- Custom Alert Card -->
    <div id="customAlert" class="custom-alert" style="display:none;">
      <div class="alert-icon" id="alertIcon"></div>
      <div class="alert-message" id="alertMessage"></div>
    </div>
@endsection

@push('scripts')
<!-- FullCalendar Scripts -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
// Custom Alert Function
function showCustomAlert(message, type = 'error') {
  const alertBox = document.getElementById('customAlert');
  const alertIcon = document.getElementById('alertIcon');
  const alertMessage = document.getElementById('alertMessage');
  alertBox.className = 'custom-alert ' + type;
  alertMessage.textContent = message;
  if (type === 'success') {
    alertIcon.innerHTML = '<span style="color:#22c55e;">&#10003;</span>';
  } else if (type === 'warning') {
    alertIcon.innerHTML = '<span style="color:#f59e0b;">&#33;</span>';
  } else {
    alertIcon.innerHTML = '<span style="color:#ef4444;">&#10006;</span>';
  }
  alertBox.style.display = 'flex';
  alertBox.style.animation = 'fadeInDown 0.5s';
  setTimeout(() => {
    alertBox.style.animation = 'fadeOutUp 0.5s';
    setTimeout(() => {
      alertBox.style.display = 'none';
    }, 500);
  }, 3000);
}

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

    // Room & Guest Dropdown
    setTimeout(function() { // ensure DOM is ready
        const dropdownBtn = document.getElementById('roomGuestDropdownBtn');
        const dropdownMenu = document.getElementById('roomGuestDropdownMenu');
        const dropdown = dropdownBtn ? dropdownBtn.parentElement : null;
        const selectedSpan = document.getElementById('roomGuestSelected');
        const hiddenInput = document.getElementById('roomGuestInput');
        if (dropdownBtn && dropdownMenu && selectedSpan && hiddenInput) {
            const items = dropdownMenu.querySelectorAll('.dropdown-item');
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });
            items.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.classList.add('hover');
                });
                item.addEventListener('mouseleave', function() {
                    this.classList.remove('hover');
                });
                item.addEventListener('click', function() {
                    items.forEach(i => i.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedSpan.textContent = this.textContent;
                    hiddenInput.value = this.getAttribute('data-value');
                    dropdown.classList.remove('open');
                });
            });
            document.addEventListener('click', function() {
                dropdown.classList.remove('open');
            });
        }
    }, 0);
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
                showCustomAlert('You must select both check-in and check-out dates!', 'warning');
                return;
            }
            
            // Format dates for the request
            const formattedCheckIn = formatDateForSubmit(checkIn);
            const formattedCheckOut = formatDateForSubmit(checkOut);
            const guests = document.getElementById('roomGuestInput').value;
            
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
                        showCustomAlert('Rooms content container not found!', 'error');
                        return;
                    }
                } else {
                    showCustomAlert('Rooms container not found in response!', 'error');
                    return;
                }
            } catch (error) {
                console.error('Error loading rooms:', error);
                showCustomAlert('Failed to load available rooms. Please try again.', 'error');
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
            showCustomAlert('Please select a check-in date before selecting check-out date!', 'error');
            closeCalendar();
            openCalendar('check_in');
            return;
        }
        if (clickedDate <= window.selectedStartDate) {
            showCustomAlert('Check-out date must be after check-in date!', 'error');
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
<script>
// Room & Guest Dropdown (dijamin dieksekusi paling akhir)
document.addEventListener('DOMContentLoaded', function() {
  console.log('Room & Guest Dropdown script loaded');
  const dropdownBtn = document.getElementById('roomGuestDropdownBtn');
  const dropdownMenu = document.getElementById('roomGuestDropdownMenu');
  const dropdown = dropdownBtn ? dropdownBtn.parentElement : null;
  const selectedSpan = document.getElementById('roomGuestSelected');
  const hiddenInput = document.getElementById('roomGuestInput');
  if (dropdownBtn && dropdownMenu && selectedSpan && hiddenInput) {
    console.log('Dropdown elements found');
    const items = dropdownMenu.querySelectorAll('.dropdown-item');
    dropdownBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
      console.log('Dropdown toggled:', dropdown.classList.contains('open'));
      // Debug: force show
      if (dropdown.classList.contains('open')) {
        dropdownMenu.style.display = 'block';
      } else {
        dropdownMenu.style.display = '';
      }
    });
    items.forEach(item => {
      item.addEventListener('click', function() {
        items.forEach(i => i.classList.remove('selected'));
        this.classList.add('selected');
        selectedSpan.textContent = this.textContent;
        hiddenInput.value = this.getAttribute('data-value');
        dropdown.classList.remove('open');
        dropdownMenu.style.display = '';
        console.log('Dropdown item selected:', this.getAttribute('data-value'));
      });
    });
    document.addEventListener('click', function() {
      dropdown.classList.remove('open');
      dropdownMenu.style.display = '';
    });
  } else {
    console.log('Dropdown elements NOT found');
  }
});
</script>
@endpush
