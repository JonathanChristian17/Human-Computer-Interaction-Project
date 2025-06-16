@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@section('title', 'Welcome to Cahaya Resort Pangururan')

@section('head')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Email Verification Notice */
    .verification-notice {
        animation: slideUp 0.5s ease-out;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Add margin to the bottom of the page to prevent content from being hidden behind the notice */
    .has-verification-notice {
        margin-bottom: 4rem;
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
      position: relative;
      width: 220px;
      z-index: 1000;
    }

    .selected {
      background: rgba(0,0,0,0.3);
      color: #fff;
      border-radius: 12px;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      position: relative;
      transition: all 0.3s ease;
      height: 48px;
      min-width: 220px;
    }

    .selected i {
      color: #FFA040;
      font-size: 16px;
      width: 20px;
      text-align: center;
      flex-shrink: 0;
    }

    .selected-text {
      color: #fff;
      font-weight: 500;
      flex: 1;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-right: 8px;
    }

    .arrow {
      width: 14px;
      height: 14px;
      fill: #fff;
      transition: transform 0.3s ease;
      flex-shrink: 0;
    }

    .select.open .arrow {
      transform: rotate(180deg);
    }

    .options {
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      width: 220px;
      background: #1a1a1a;
      border-radius: 12px;
      padding: 8px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      max-height: 200px;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: #FFA040 #1a1a1a;
    }

    .options::-webkit-scrollbar {
      width: 6px;
    }

    .options::-webkit-scrollbar-track {
      background: #1a1a1a;
      border-radius: 3px;
    }

    .options::-webkit-scrollbar-thumb {
      background: #FFA040;
      border-radius: 3px;
    }

    .select.open .options {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .option {
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 12px;
      height: 48px;
    }

    .option:hover {
      background: rgba(255,160,64,0.1);
    }

    .option.selected {
      background: rgba(255,160,64,0.2);
    }

    .option i {
      color: #FFA040;
      font-size: 16px;
      width: 20px;
      text-align: center;
      flex-shrink: 0;
    }

    .option span {
      color: #fff;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .option input[type="radio"] {
      display: none;
    }

    .option label {
      display: flex;
      align-items: center;
      gap: 12px;
      width: 100%;
      cursor: pointer;
      height: 24px;
    }

    .option.selected span {
      color: #FFA040;
    }

    @media (max-width: 768px) {
      .select {
        width: 100%;
      }
      
      .selected {
        width: 100%;
        min-width: 0;
      }

      .options {
        width: 100%;
      }
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .modal-close {
        cursor: pointer;
        font-size: 1.5rem;
        color: #6b7280;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: #1a1a1a;
    }

    .modal-body {
        color: #4b5563;
        line-height: 1.6;
    }

    /* Bottom Sheet Panel Styles */
    .panel {
        display: none;
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        height: 90vh;
        background: white;
        z-index: 50;
        border-radius: 20px 20px 0 0;
        transform: translateY(100%);
        transition: transform 0.3s ease;
        overflow-y: auto;
    }

    .panel.show {
        transform: translateY(0);
    }

    .panel-header {
        position: sticky;
        top: 0;
        background: white;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1;
    }

    .panel-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .panel-close {
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .panel-close:hover {
        background-color: #f3f4f6;
    }

    .panel-content {
        padding: 1rem;
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        border: 3px solid #f3f4f6;
        border-radius: 50%;
        border-top-color: #FFA040;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        gap: 1rem;
    }
</style>
@endsection

@section('content')
    @auth
        @if(!auth()->user()->hasVerifiedEmail())
            <div class="fixed bottom-0 left-0 right-0 z-50 bg-yellow-500 text-white py-4 verification-notice">
                <div class="container mx-auto px-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Please verify your email address to book rooms. Check your inbox for the verification link.</span>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="text-sm underline hover:no-underline hover:text-white/80 transition-colors">
                            Resend verification email
                        </button>
                    </form>
                </div>
            </div>
            <script>
                document.body.classList.add('has-verification-notice');
            </script>
        @endif
    @endauth

    <!-- Hero Section -->
    <section class="relative h-auto pb-32 sm:pb-40 md:pb-48 lg:h-screen lg:pb-0">
        <!-- Hero Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('storage/images/header.png') }}" alt="Resort View" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative flex items-center h-full pt-64 md:pt-48 sm:pt-56 lg:pt-0 xl:pt-0">
            <div class="w-full px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="max-w-3xl ml-0 -mt-40 lg:-mt-0 lg:ml-0">
                    <h1 class="mb-6 text-3xl font-medium tracking-wider text-white md:text-5xl lg:text-6xl drop-shadow-lg" style="font-family:'Poppins',sans-serif; font-weight:500;">
                        CAHAYA RESORT<br>
                        PANGURUAN
                    </h1>

                    <!-- Description Box -->
                    <div class="absolute max-w-xs right-4 bottom-4 md:max-w-lg md:right-20 md:bottom-32">
                        <div class="flex gap-4">
                            <div class="w-1 bg-orange-500"></div>
                            <div>
                                <p class="mb-2 text-base font-semibold text-white md:text-xl">
                                    We provide a variety of the best lodging accommodations for those of you who need it.
                                </p>
                                <p class="text-xs text-white/80 md:text-sm">
                                    Don't worry about the quality of the service.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Booking Form -->
                <form id="searchForm" class="flex flex-col items-stretch gap-4 p-4 mt-10 bg-black/40 backdrop-blur-md rounded-xl md:inline-flex md:flex-row md::items-center md::gap-4" style="overflow:visible; z-index:9999;">
                    <!-- Check-in -->
                    <div class="flex items-center justify-center w-full gap-2 px-4 py-2 rounded-lg cursor-pointer bg-black/30 md:w-auto" onclick="openCalendar('check_in')">
                        <i class="text-white fas fa-calendar"></i>
                        <input type="text" 
                               id="landing_check_in"
                               name="check_in"
                               class="w-full text-white placeholder-white bg-transparent border-none focus:outline-none md:w-32" 
                               placeholder="Check in"
                               readonly>
                    </div>
                            
                    <!-- Check-out -->
                    <div class="flex items-center justify-center w-full gap-2 px-4 py-2 rounded-lg cursor-pointer bg-black/30 md:w-auto" onclick="openCalendar('check_out')">
                        <i class="text-white fas fa-calendar"></i>
                        <input type="text" 
                               id="landing_check_out"
                               name="check_out"
                               class="w-full text-white placeholder-white bg-transparent border-none focus:outline-none md:w-32" 
                               placeholder="Check out"
                               readonly>
                    </div>
            
                    <!-- Room Type Selector -->
                    <div class="flex items-center justify-center w-full gap-2 px-4 py-2 rounded-lg bg-black/30 md:w-auto">
                        <div class="select">
                            <div class="selected">
                                <i class="fas fa-home"></i>
                                <span class="selected-text">All Room Types</span>
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                    <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"></path>
                                </svg>
                            </div>
                            <div class="options">
                                <div class="option" data-value="">
                                    <input type="radio" id="all" name="room_type" value="" checked>
                                    <label for="all">
                                        <i class="fas fa-home"></i>
                                        <span>All Room Types</span>
                                    </label>
                                </div>
                                @foreach($roomTypes as $type)
                                <div class="option" data-value="{{ $type }}">
                                    <input type="radio" id="{{ Str::slug($type) }}" name="room_type" value="{{ $type }}">
                                    <label for="{{ Str::slug($type) }}">
                                        <i class="fas fa-bed"></i>
                                        <span>{{ $type }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                
                    <!-- Search Button -->
                    <button type="submit" class="w-full px-6 py-3 font-semibold text-white transition-all rounded-lg md:w-auto" style="background:#FFA040; font-family:'Poppins',sans-serif; font-weight:600;" onmouseover="this.style.background='#ff8c1a'" onmouseout="this.style.background='#FFA040'">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Room Choice Section -->
    <section class="relative pt-20 pb-20 bg-white">
        <!-- Curved Welcome Section -->
        <div class="absolute left-0 right-0 -top-12 sm:-top-16 md:-top-20 lg:-top-12">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <div class="px-10 py-3 -mt-2 text-center sm:px-16 md:px-32" style="background:#fff; clip-path: polygon(10% 0, 90% 0, 100% 100%, 0% 100%); box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);">
                        <h2 class="text-xl font-bold tracking-wider text-gray-800 md:text-2xl" style="font-family:'Poppins',sans-serif;">WELCOME TO CAHAYA RESORT</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="px-4 pt-20 pb-20 mx-auto mt-48 max-w-7xl sm:px-6 lg:px-8 sm:mt-56 md:mt-64 lg:mt-0">
            <p class="mb-12 text-xl font-bold text-center text-black-600 md:text-2xl">Room Choice in Cahaya Resort</p>

            <!-- Room Carousel -->
            <div class="relative px-4 sm:px-6 md:px-12">
                <!-- Previous Button -->
                <button class="absolute left-0 z-10 flex items-center justify-center w-8 h-8 -translate-y-1/2 bg-white rounded-full shadow-lg top-1/2 group carousel-prev md:w-10 md:h-10">
                    <i class="text-gray-400 fas fa-chevron-left group-hover:text-gray-600"></i>
                </button>

                <!-- Next Button -->
                <button class="absolute right-0 z-10 flex items-center justify-center w-8 h-8 -translate-y-1/2 bg-white rounded-full shadow-lg top-1/2 group carousel-next md:w-10 md:h-10">
                    <i class="text-gray-400 fas fa-chevron-right group-hover:text-gray-600"></i>
                </button>

                <!-- Carousel Container -->
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500" id="roomSlider">
                        <!-- Original items -->
                        @foreach($rooms as $index => $room)
                        <div class="flex-none w-[280px] mx-2 sm:w-[300px] sm:mx-3" data-index="{{ $index }}">
                            <div class="overflow-hidden transition-all duration-500 transform bg-white shadow-lg rounded-xl cursor-pointer hover:shadow-xl relative group"
                                 onclick="@if($room->status === 'available')selectRoom({
                                    id: {{ $room->id }},
                                    name: '{{ $room->name }}',
                                    price: {{ $room->price_per_night }},
                                    image: '{{ $room->image }}',
                                    capacity: {{ $room->capacity }},
                                    type: '{{ $room->type }}'
                                })@else showCustomAlert('This room is currently not available for booking.', 'warning')@endif">
                                <div class="relative">
                                    <img src="{{ asset('storage/images/' . $room->image) }}" alt="{{ $room->name }}" 
                                         class="object-cover w-full h-40 sm:h-48 transition-all duration-300 {{ $room->status !== 'available' ? 'grayscale group-hover:grayscale-0' : '' }}">
                                    @if($room->status !== 'available')
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-100 group-hover:opacity-0 transition-opacity duration-300">
                                        <span class="px-3 py-1 text-sm font-semibold text-white bg-red-500/80 rounded-full backdrop-blur-sm">
                                            Not Available
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-base font-semibold sm:text-lg {{ $room->status !== 'available' ? 'text-gray-600' : '' }}">
                                            {{ $room->name }}
                                        </h3>
                                        <p class="text-sm font-medium {{ $room->status === 'available' ? 'text-orange-500' : 'text-gray-500' }} sm:text-base">
                                            Rp. {{ number_format($room->price_per_night, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 sm:text-sm">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Pangururan</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $room->capacity }} Guest</span>
                                        <span class="text-gray-300">•</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($room->status) }}
                                        </span>
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
                    // Use a function to get dynamic card width based on screen size if needed
                    // For now, rely on CSS classes for width and adjust centering logic
                    const cardWidth = 300; // Base width, adjust or make dynamic if CSS w-[...] isn't enough
                    const cardGap = 24; // Base gap
                    let autoSlideInterval;

                    function updateSlider() {
                        const containerWidth = slider.parentElement.offsetWidth;
                        // Adjust centering logic to account for responsive card width and gap
                        const currentCardWidth = cards[0].offsetWidth + (parseFloat(window.getComputedStyle(cards[0]).marginLeft) + parseFloat(window.getComputedStyle(cards[0]).marginRight));
                        const offset = (containerWidth / 2) - (currentCardWidth / 2) - (currentIndex * currentCardWidth);

                        slider.style.transform = `translateX(${offset}px)`;
                        
                        // Update cards appearance (scaling/opacity) - this logic is less dependent on fixed pixels
                        cards.forEach((card, index) => {
                            const distance = Math.abs(index - currentIndex);
                            const cardElement = card.querySelector('.bg-white');
                            
                            if (distance === 0) {
                                cardElement.style.transform = 'scale(1.1) translateY(-20px)';
                                card.style.opacity = '1';
                                card.style.zIndex = '20';
                            } else if (distance === 1) {
                                const direction = index > currentIndex ? 1 : -1;
                                cardElement.style.transform = `scale(0.9) translateX(${direction * 20}px)`
                                card.style.opacity = '0.7';
                                card.style.zIndex = '10';
                            } else {
                                const direction = index > currentIndex ? 1 : -1;
                                cardElement.style.transform = `scale(0.8) translateX(${direction * 40}px)`
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
            <h2 class="mb-12 text-2xl font-bold text-center md:text-3xl lg:text-4xl" style="font-family:'Poppins',sans-serif; letter-spacing:0.10em;">Why Cahaya Resort ?</h2>
            <div class="grid grid-cols-1 gap-8 px-4 text-center sm:px-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-12">
                <div class="p-6 transition-transform duration-300 transform bg-white/5 rounded-xl hover:scale-105">
                    <div class="flex flex-col items-center">
                        <i class="mb-4 text-4xl text-[#FFA040] fas fa-home"></i>
                        <h3 class="mb-4 text-xl font-bold" style="font-family:'Poppins',sans-serif;">Traditional</h3>
                        <p class="text-sm text-gray-400 md:text-base">From local hotels to grand resorts, discover folklore of hotels all around the world.</p>
                    </div>
                </div>
                <div class="p-6 transition-transform duration-300 transform bg-white/5 rounded-xl hover:scale-105">
                    <div class="flex flex-col items-center">
                        <i class="mb-4 text-4xl text-[#FFA040] fas fa-building"></i>
                        <h3 class="mb-4 text-xl font-bold" style="font-family:'Poppins',sans-serif;">Modern</h3>
                        <p class="text-sm text-gray-400 md:text-base">No need to search anywhere else. The biggest names in hotels are right here.</p>
                    </div>
                </div>
                <div class="p-6 transition-transform duration-300 transform bg-white/5 rounded-xl hover:scale-105">
                    <div class="flex flex-col items-center">
                        <i class="mb-4 text-4xl text-[#FFA040] fas fa-tags"></i>
                        <h3 class="mb-4 text-xl font-bold" style="font-family:'Poppins',sans-serif;">Affordable</h3>
                        <p class="text-sm text-gray-400 md:text-base">We've scored deals with the world's leading hotels and we share savings with you.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dream in Serene Luxury Section -->
    <section class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="relative flex flex-col lg:flex-row lg:items-center lg:gap-12">
                <!-- Left Content -->
                <div class="max-w-xl lg:w-1/2">
                    <h2 class="flex flex-col items-start gap-1 mb-6 text-2xl font-semibold text-gray-800 sm:flex-row sm:items-center sm:gap-3 md:text-3xl">
                        Dream In <span class="text-gray-600">Serene Luxury</span>
                    </h2>
                    <p class="mb-8 text-sm leading-relaxed text-gray-600 md:text-base">
                        Cahaya Pangururan Inn, comfortable with local nuances and natural panorama of Samosir. Wake up with cool air, calming lake views, and a calm atmosphere that refreshes the soul. Enjoy the hospitality of the host, complete facilities, and comfort like at home.
                    </p>
                </div>

                <!-- Facility Gallery -->
                <div class="relative mt-8 lg:mt-0 lg:w-1/2">
                    <!-- Gallery Container -->
                    <div class="overflow-x-auto">
                        <div class="flex gap-4 pb-4 lg:gap-6" id="facilitySlider">
                            <!-- Facility Items -->
                            <div class="flex gap-4 lg:gap-6">
                                <!-- Rooms -->
                                <div class="relative group w-[180px] flex-none overflow-hidden rounded-xl sm:w-[220px]">
                                    <img src="{{ asset('storage/images/facility-1.jpg') }}" alt="Rooms" 
                                         class="w-full h-[300px] object-cover transition-transform duration-500 group-hover:scale-110 sm:h-[400px]">
                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-black/40 backdrop-blur-sm">
                                        <span class="text-base font-bold text-white sm:text-lg" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>

                                <!-- Parking Area -->
                                <div class="relative group w-[180px] flex-none overflow-hidden rounded-xl sm:w-[220px]">
                                    <img src="{{ asset('storage/images/facility-2.jpg') }}" alt="Parking Area" 
                                         class="w-full h-[300px] object-cover transition-transform duration-500 group-hover:scale-110 grayscale sm:h-[400px]">
                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-black/40 backdrop-blur-sm">
                                        <span class="text-base font-bold text-white sm:text-lg" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>

                                <!-- Mini Park -->
                                <div class="relative group w-[180px] flex-none overflow-hidden rounded-xl sm:w-[220px]">
                                    <img src="{{ asset('storage/images/facility-3.jpg') }}" alt="Mini Park" 
                                         class="w-full h-[300px] object-cover transition-transform duration-500 group-hover:scale-110 sm:h-[400px]">
                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-black/40 backdrop-blur-sm">
                                        <span class="text-base font-bold text-white sm:text-lg" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>

                                <!-- Loby -->
                                <div class="relative group w-[180px] flex-none overflow-hidden rounded-xl sm:w-[220px]">
                                    <img src="{{ asset('storage/images/facility-4.jpg') }}" alt="Loby" 
                                         class="w-full h-[300px] object-cover transition-transform duration-500 group-hover:scale-110 sm:h-[400px]">
                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-black/40 backdrop-blur-sm">
                                        <span class="text-base font-bold text-white sm:text-lg" style="font-family:'Poppins',sans-serif;">Pangururan</span>
                                    </div>
                                </div>
                                
                                <!-- View -->
                                <div class="relative group w-[180px] flex-none overflow-hidden rounded-xl sm:w-[220px]">
                                    <img src="{{ asset('storage/images/facility-5.jpg') }}" alt="View" 
                                         class="w-full h-[300px] object-cover transition-transform duration-500 group-hover:scale-110 sm:h-[400px]">
                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-black/40 backdrop-blur-sm">
                                        <span class="text-base font-bold text-white sm:text-lg" style="font-family:'Poppins',sans-serif;">Waterfall Pangururan</span>
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
    <section class="py-16 text-white md:py-20" style="background:#252525; font-family:'Poppins',sans-serif;">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 md:gap-12 lg:gap-16">
                <div class="flex flex-col items-center justify-center p-6 shadow-sm bg-white/5 rounded-xl md:p-8 lg:p-10">
                    <h2 class="mb-4 text-xl font-bold text-center md:text-2xl" style="font-family:'Poppins',sans-serif; font-weight:700;">Contact Center</h2>
                    <p class="mb-6 text-sm font-normal text-center md:text-base" style="font-family:'Poppins',sans-serif; font-weight:400;">Contact us for information, reservations or other assistance.</p>
                    <a href="https://wa.me/6281361002918" target="_blank" class="flex items-center gap-3 px-6 py-3 mt-2 text-lg font-semibold text-white transition bg-green-500 rounded-lg shadow-lg hover:bg-green-600" style="font-family:'Poppins',sans-serif; font-weight:500;">
                        <i class="text-2xl fab fa-whatsapp"></i>
                        0813 6100 2918
                    </a>
                </div>
                <div class="relative flex flex-col justify-center p-6 shadow-sm bg-white/5 rounded-xl md:p-8 md:border-l md:pl-10 lg:p-10 lg:pl-12 border-gray-400/20">
                    <span class="absolute top-8 left-0 hidden md:block w-0.5 h-[80%] bg-gradient-to-b from-transparent via-gray-400/20 to-transparent rounded-full"></span>
                    <h3 class="mb-6 text-xl font-bold md:text-2xl" style="font-family:'Poppins',sans-serif; font-weight:700;">Location</h3>
                    <div class="w-full h-[250px] md:h-[300px] rounded-lg overflow-hidden">
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
    <section class="relative h-[400px] md:h-[500px] flex items-center justify-center" style="background-image: url('{{ asset('storage/images/bg_fixed.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 flex flex-col items-center justify-center w-full h-full px-4">
            <h2 class="mb-8 text-xl font-semibold tracking-widest text-center text-white uppercase md:text-2xl lg:text-3xl xl:text-4xl" style="font-family:'Poppins',sans-serif; letter-spacing:0.15em;">
            "UNWIND BY THE WATER. A LAKESIDE ESCAPE CRAFTED FOR TIMELESS MOMENTS."
            </h2>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="px-8 py-3 mt-2 text-sm font-semibold tracking-widest text-gray-700 uppercase transition rounded-md bg-gray-300/70 md:px-10 md:text-base lg:text-lg hover:bg-gray-400/80" style="font-family:'Poppins',sans-serif;">
                BACK TO TOP
            </button>
        </div>
    </section>

    <!-- Footer Section -->
    <section class="pt-12 pb-8 text-white md:pt-16" style="background:#1D1D1D;">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="mb-8 text-3xl font-semibold text-center md:text-4xl lg:text-5xl" style="font-family:'Poppins',sans-serif;">Cahaya Resort</h2>
            <hr class="mb-8 border-t border-gray-700 md:mb-12">
            <div class="grid grid-cols-1 gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">
                <!-- Newsletter -->
                <div class="text-center md:text-left">
                    <h3 class="mb-4 text-lg font-medium md:text-xl" style="font-family:'Poppins',sans-serif;">Ready to get started?</h3>
                    <div class="flex items-center justify-center mt-8 md:justify-start">
                        <button type="button" onclick="openRoomsPanel()" class="w-full md:w-auto px-8 py-3 bg-[#D2A06E] text-white rounded-lg font-semibold text-lg flex items-center justify-center" style="font-family:'Poppins',sans-serif;">
                            Get Started
                        </button>
                    </div>
                </div>
                <!-- Services -->
                <div class="text-center md:text-left">
                    <h4 class="mb-2 text-base font-semibold text-[#FFA040] md:text-lg" style="font-family:'Poppins',sans-serif;">Services</h4>
                    <ul class="space-y-2 text-sm md:text-base">
                        <li>Email Marketing</li>
                        <li>Campaigns</li>
                        <li>Branding</li>
                        <li>Offline</li>
                    </ul>
                </div>
                <!-- About -->
                <div class="text-center md:text-left">
                    <h4 class="mb-2 text-base font-semibold text-[#FFA040] md:text-lg" style="font-family:'Poppins',sans-serif;">About</h4>
                    <ul class="space-y-2 text-sm md:text-base">
                        <li>Our Story</li>
                        <li>Benefits</li>
                        <li>Team</li>
                        <li>Careers</li>
                    </ul>
                </div>
                <!-- Help -->
                <div class="text-center md:text-left">
                    <h4 class="mb-2 text-base font-semibold text-[#FFA040] md:text-lg" style="font-family:'Poppins',sans-serif;">Help</h4>
                    <ul class="space-y-2 text-sm md:text-base">
                        <li>FAQs</li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col items-center justify-between pt-6 border-t border-gray-700 md:flex-row md:pt-8">
                <div class="flex flex-col items-center w-full gap-4 mb-6 md:flex-row md:w-auto md:mb-0">
                    <span class="text-xs md:text-sm cursor-pointer" onclick="openModal('termsModal')">Terms & Conditions</span>
                    <span class="text-xs md:text-sm cursor-pointer" onclick="openModal('privacyModal')">Privacy Policy</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="javascript:void(0)" onclick="confirmSocialMediaRedirect('https://www.facebook.com', 'Facebook')" class="text-xl md:text-2xl"><i class="fab fa-facebook"></i></a>
                    <a href="javascript:void(0)" onclick="confirmSocialMediaRedirect('https://x.com', 'X (Twitter)')" class="text-xl md:text-2xl"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="javascript:void(0)" onclick="confirmSocialMediaRedirect('https://www.instagram.com', 'Instagram')" class="text-xl md:text-2xl"><i class="fab fa-instagram"></i></a>
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

    <!-- Terms & Conditions Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Terms & Conditions</h2>
                <span class="modal-close" onclick="closeModal('termsModal')">&times;</span>
            </div>
            <div class="modal-body">
                <h3 class="text-lg font-semibold mb-4">1. Acceptance of Terms</h3>
                <p class="mb-4">By accessing and using Cahaya Resort's services, you agree to be bound by these Terms & Conditions.</p>
                
                <h3 class="text-lg font-semibold mb-4">2. Booking and Cancellation</h3>
                <p class="mb-4">- Reservations are subject to availability and confirmation<br>
                - Cancellations must be made 24 hours before check-in<br>
                - Late cancellations may incur charges</p>
                
                <h3 class="text-lg font-semibold mb-4">3. Check-in/Check-out</h3>
                <p class="mb-4">- Check-in time: 2:00 PM<br>
                - Check-out time: 12:00 PM<br>
                - Late check-out subject to availability</p>
                
                <h3 class="text-lg font-semibold mb-4">4. Payment</h3>
                <p class="mb-4">- Full payment required at check-in<br>
                - We accept major credit cards and cash<br>
                - Additional charges for extra services</p>
                
                <h3 class="text-lg font-semibold mb-4">5. Property Rules</h3>
                <p>- No smoking in rooms<br>
                - Quiet hours: 10:00 PM - 6:00 AM<br>
                - Pets not allowed</p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Privacy Policy</h2>
                <span class="modal-close" onclick="closeModal('privacyModal')">&times;</span>
            </div>
            <div class="modal-body">
                <h3 class="text-lg font-semibold mb-4">1. Information We Collect</h3>
                <p class="mb-4">We collect personal information including name, contact details, payment information, and stay preferences to provide our services.</p>
                
                <h3 class="text-lg font-semibold mb-4">2. How We Use Your Information</h3>
                <p class="mb-4">- Process your reservations<br>
                - Communicate about your stay<br>
                - Improve our services<br>
                - Send promotional offers (with consent)</p>
                
                <h3 class="text-lg font-semibold mb-4">3. Information Security</h3>
                <p class="mb-4">We implement appropriate security measures to protect your personal information from unauthorized access or disclosure.</p>
                
                <h3 class="text-lg font-semibold mb-4">4. Third-Party Sharing</h3>
                <p class="mb-4">We may share information with trusted partners for business purposes, always in compliance with privacy laws.</p>
                
                <h3 class="text-lg font-semibold mb-4">5. Your Rights</h3>
                <p>You have the right to:<br>
                - Access your personal data<br>
                - Request corrections<br>
                - Withdraw consent<br>
                - Request data deletion</p>
            </div>
        </div>
    </div>

    <!-- Bottom Sheet Panel -->
    <div class="panel-overlay" id="panelOverlay"></div>
    <div class="panel" id="roomsPanel">
        <div class="panel-header">
            <button class="flex items-center gap-2 panel-back" onclick="goBack()">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
            </button>
            <h2 class="panel-title">Rooms</h2>
            <button class="panel-close" onclick="closePanel()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="panel-content" id="roomsContent">
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p class="text-gray-600">Loading rooms...</p>
            </div>
        </div>
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
                start: new Date() // Use current date/time, FullCalendar handles start of day
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
    // Use UTC methods to get YYYY-MM-DD string without timezone issues
    const year = date.getUTCFullYear();
    const month = (date.getUTCMonth() + 1).toString().padStart(2, '0');
    const day = date.getUTCDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
};

// Update form submit handler
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store search parameters globally
            window.lastSearchParams = new URLSearchParams();
            
            // Get the dates (optional now)
            const checkIn = window.selectedStartDate;
            const checkOut = window.selectedEndDate;
            
            // Validate dates if they are selected
            if (checkIn || checkOut) {
                if (!checkIn || !checkOut) {
                    showCustomAlert('Please select both check-in and check-out dates!', 'warning');
                    return;
                }

                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (checkIn < today) {
                    showCustomAlert('Check-in date cannot be in the past!', 'error');
                    return;
                }
                
                if (checkOut <= checkIn) {
                    showCustomAlert('Check-out date must be after check-in date!', 'error');
                    return;
                }
            }
            
            // Get selected room type
            const selectedRoomType = document.querySelector('input[name="room_type"]:checked');
            
            // Show loading state in rooms panel
            const roomsPanel = document.getElementById('roomsPanel');
            const roomsContent = document.getElementById('roomsContent');
            
            if (roomsPanel && roomsContent) {
                // Show panel
                roomsPanel.style.display = 'block';
                roomsContent.innerHTML = `
                    <div class="py-4 text-center">
                        <div class="w-8 h-8 mx-auto border-b-2 border-orange-500 rounded-full animate-spin"></div>
                        <p class="mt-2 text-gray-600">Loading rooms...</p>
                    </div>
                `;
                
                // Force reflow and add show class
                roomsPanel.offsetHeight;
                roomsPanel.classList.add('show');
            }

            // Build query parameters
            if (checkIn && checkOut) {
                window.lastSearchParams.set('check_in', formatDateForSubmit(checkIn));
                window.lastSearchParams.set('check_out', formatDateForSubmit(checkOut));
            }
            if (selectedRoomType && selectedRoomType.value) {
                window.lastSearchParams.set('room_type', selectedRoomType.value);
            }

            // Update URL without page reload
            const url = new URL(window.location);
            Array.from(window.lastSearchParams.entries()).forEach(([key, value]) => {
                url.searchParams.set(key, value);
            });
            window.history.pushState({}, '', url);

            // Fetch filtered rooms
            fetchRoomsContent(window.lastSearchParams.toString());
        });
    }
});

// Update fetchRoomsContent function
function fetchRoomsContent(queryString = '') {
    const content = document.getElementById('roomsContent');
    content.innerHTML = `
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="text-gray-600">Loading rooms...</p>
        </div>
    `;

    fetch(`/kamar?${queryString}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const mainContent = doc.querySelector('.min-h-screen');
            
            if (mainContent) {
                content.innerHTML = mainContent.innerHTML;
                
                // Update navigation state
                const navContainer = document.querySelector('[x-data]');
                if (navContainer && navContainer.__x) {
                    navContainer.__x.$data.activeTab = 'rooms';
                    localStorage.setItem('activeTab', 'rooms');
                }

                // Force update UI for navigation
                document.querySelectorAll('.nav-item').forEach(item => {
                    const text = item.textContent.trim();
                    if (text === 'Rooms') {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });

                // Bind pagination events
                bindPanelEvents();
            } else {
                throw new Error('Could not find main content');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="p-4 text-center">
                    <div class="text-red-600 mb-2">Failed to load rooms.</div>
                    <button onclick="retryLastSearch()" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        Try Again
                    </button>
                </div>
            `;
        });
}

// Add retryLastSearch function
function retryLastSearch() {
    if (window.lastSearchParams) {
        fetchRoomsContent(window.lastSearchParams.toString());
    } else {
        fetchRoomsContent();
    }
}

// Update retryLoadRooms function
function retryLoadRooms() {
    retryLastSearch();
}

window.handleDateClick = function(info) {
    const clickedDate = info.date;
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (clickedDate < today) {
        showCustomAlert('Cannot select dates in the past!', 'error');
        return;
    }

    if (window.currentInputType === 'check_in') {
        window.selectedStartDate = clickedDate;
        // If check-out was before or same as new check-in, clear it
        if (window.selectedEndDate && window.selectedEndDate <= window.selectedStartDate) {
             window.selectedEndDate = null;
             document.getElementById('landing_check_out').value = '';
        }
        highlightDates();
        applyDates();
    } else if (window.currentInputType === 'check_out') {
        if (!window.selectedStartDate) {
            showCustomAlert('Please select a check-in date first!', 'error');
            setTimeout(() => openCalendar('check_in'), 100);
            return;
        }
        // Ensure check-out is strictly after check-in
        if (clickedDate <= window.selectedStartDate) {
            showCustomAlert('Check-out date must be after check-in date!', 'error');
            return;
        }
        window.selectedEndDate = clickedDate;
        highlightDates();
        applyDates();
    }
};

window.highlightDates = function() {
    // Clear existing highlights
    window.calendar.getEvents().forEach(event => event.remove());
    
    if (window.selectedStartDate) {
        let displayEndDate = window.selectedEndDate ? new Date(window.selectedEndDate) : new Date(window.selectedStartDate);
        
        // Add one day to include the end date in the highlight
        if (window.selectedEndDate) {
            displayEndDate.setDate(displayEndDate.getDate() + 1);
        }
       
        window.calendar.addEvent({
            start: window.selectedStartDate,
            end: displayEndDate,
            display: 'background',
            backgroundColor: '#fef3c7'
        });
    }
};

window.formatDate = function(date) {
     // This function seems redundant, using formatDateForDisplay instead
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.querySelector('.select');
    const selected = select.querySelector('.selected');
    const selectedText = select.querySelector('.selected-text');
    const options = select.querySelectorAll('.option');
    
    // Toggle dropdown on selected click
    selected.addEventListener('click', function(e) {
        e.stopPropagation();
        select.classList.toggle('open');
    });
    
    // Handle option selection
    options.forEach(option => {
        const input = option.querySelector('input');
        const label = option.querySelector('label');
        
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            updateSelection(this);
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        select.classList.remove('open');
    });
    
    // Initialize from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const roomType = urlParams.get('room_type');
    if (roomType) {
        const option = select.querySelector(`.option input[value="${roomType}"]`).closest('.option');
        if (option) {
            updateSelection(option, false);
        }
    }
    
    function updateSelection(selectedOption, shouldUpdateUrl = true) {
        const input = selectedOption.querySelector('input');
        const label = selectedOption.querySelector('label');
        const value = input.value;
        
        // Update radio state
        input.checked = true;
        
        // Update selected text
        selectedText.textContent = label.querySelector('span').textContent;
        
        // Update visual state
        options.forEach(opt => opt.classList.remove('selected'));
        selectedOption.classList.add('selected');
        
        // Close dropdown
        select.classList.remove('open');
        
        // Update URL if needed
        if (shouldUpdateUrl) {
            const url = new URL(window.location);
            if (value) {
                url.searchParams.set('room_type', value);
            } else {
                url.searchParams.delete('room_type');
            }
            window.history.pushState({}, '', url);
        }
    }
});
</script>
<script>
// Add these new functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

function openRoomsPanel() {
    const panel = document.getElementById('roomsPanel');
    const overlay = document.getElementById('panelOverlay');
    const content = document.getElementById('roomsContent');

    if (!panel || !content) {
        console.error('Required elements not found');
        return;
    }

    // Show overlay
    if (overlay) {
        overlay.style.display = 'block';
        overlay.style.opacity = '1';
    }

    // Show panel
    panel.style.display = 'block';
    
    // Force reflow
    panel.offsetHeight;
    
    // Add show class
    panel.classList.add('show');
    
    // Show loading state
    content.innerHTML = `
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="text-gray-600">Loading rooms...</p>
        </div>
    `;

    // Fetch rooms content
    fetch('/kamar')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const mainContent = doc.querySelector('.min-h-screen');
            
            if (mainContent) {
                content.innerHTML = mainContent.innerHTML;
                bindPanelEvents();
            } else {
                throw new Error('Could not find main content');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="p-4 text-center">
                    <div class="text-red-600 mb-2">Failed to load rooms.</div>
                    <button onclick="retryLoadRooms()" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        Try Again
                    </button>
                </div>
            `;
        });
}

function closePanel() {
    const panel = document.getElementById('roomsPanel');
    const overlay = document.getElementById('panelOverlay');
    
    // Remove show class from panel
    panel.classList.remove('show');
    
    // Fade out overlay
    if (overlay) {
        overlay.style.opacity = '0';
    }
    
    // Wait for transition to finish
    setTimeout(() => {
        panel.style.display = 'none';
        if (overlay) {
            overlay.style.display = 'none';
        }
    }, 300);
}

function goBack() {
    closePanel();
}

function bindPanelEvents() {
    // Add event listeners for pagination, filters, etc.
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const url = new URL(link.href);
            fetchRoomsContent(url.search);
        });
    });
}

// Close panel when clicking overlay
document.getElementById('panelOverlay')?.addEventListener('click', closePanel);
</script>
<script>
function selectRoom(room) {
    if (!room) return;
    
    // Open the rooms panel
    const panel = document.getElementById('roomsPanel');
    const content = document.getElementById('roomsContent');
    
    if (!panel || !content) {
        console.error('Required elements not found');
        return;
    }
    
    // Show loading state
    panel.style.display = 'block';
    panel.offsetHeight; // Force reflow
    panel.classList.add('show');
    
    content.innerHTML = `
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="text-gray-600">Loading room details...</p>
        </div>
    `;

    // Get check-in and check-out dates if selected
    const checkIn = document.getElementById('landing_check_in').value;
    const checkOut = document.getElementById('landing_check_out').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (checkIn) params.set('check_in', checkIn);
    if (checkOut) params.set('check_out', checkOut);
    params.set('selected_room', room.id);
    
    // Fetch rooms with the selected room highlighted
    fetch(`/kamar?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const mainContent = doc.querySelector('.min-h-screen');
            
            if (mainContent) {
                content.innerHTML = mainContent.innerHTML;
                
                // Add the selected room to booking if available
                if (typeof addRoom === 'function') {
                    addRoom(room);
                }
                
                bindPanelEvents();
            } else {
                throw new Error('Could not find main content');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="p-4 text-center">
                    <div class="text-red-600 mb-2">Failed to load room details.</div>
                    <button onclick="retryLoadRooms()" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        Try Again
                    </button>
                </div>
            `;
        });
}
</script>
<script>
function confirmSocialMediaRedirect(url, platform) {
    Swal.fire({
        title: 'Open ' + platform + '?',
        text: "You will be redirected to a new page",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FFA040',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, open it!',
        cancelButtonText: 'No, cancel',
        background: '#fff',
        customClass: {
            popup: 'rounded-xl',
            title: 'font-poppins font-semibold',
            text: 'font-poppins'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(url, '_blank');
        }
    });
}
</script>
@endpush
