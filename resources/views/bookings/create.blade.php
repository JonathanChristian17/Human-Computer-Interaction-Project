@extends('layouts.app')

@section('head')
@parent
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* Reset all positioning and z-index */
* {
    position: relative;
}

/* Basic layout */
.min-h-screen {
    min-height: 100vh;
    width: 100%;
    background: #f9fafb;
}

/* Main content and payment panel container */
.content-container {
    position: relative;
    width: 100%;
    min-height: 100vh;
}

/* Main booking form */
.booking-form-container {
    width: 100%;
    transition: all 0.3s ease;
}

/* Payment panel */
.payment-panel {
    width: 100%;
    min-height: 100vh;
    background: white;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 20;
    transition: all 0.3s ease;
}

.payment-panel.hidden {
    display: none;
    opacity: 0;
    visibility: hidden;
}

/* Make sure form elements are clickable */
input,
select,
textarea,
button {
    position: relative;
    z-index: 5;
}

/* Ensure sticky elements work */
.sticky-back-container {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    padding: 1rem;
    margin: -2rem -2rem 1rem -2rem;
}

/* Date input styling */
.date-input-wrapper {
    position: relative;
}

.date-input {
    width: 100%;
    padding: 0.5rem;
    padding-right: 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    cursor: pointer;
}

.calendar-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

/* Flatpickr customization */
.flatpickr-calendar {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.flatpickr-day.selected {
    background: #f97316 !important;
    border-color: #f97316 !important;
}

.flatpickr-day.today {
    border-color: #f97316;
}

.flatpickr-day:hover {
    background: #fed7aa !important;
    border-color: #fed7aa !important;
}
</style>
@endsection

@section('content')
<div class="content-container">
    <!-- Booking Form Section -->
    <div class="booking-form-container" id="bookingFormContainer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="sticky-back-container">
            <button onclick="hidePanel('rooms')" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Rooms
            </button>
        </div>

        <!-- Content -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reservation Cahaya Resort</h1>
            <p class="text-gray-600 mt-2">Fill the blank to complete booking information</p>
        </div>
                
            <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" autocomplete="off" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    <!-- Add hidden input for selected rooms -->
                <input type="hidden" 
                       name="selected_rooms" 
                       id="selected_rooms"
                       value='@json($selectedRooms->map(function($room) {
                        return [
                            'id' => $room->id,
                               'price_per_night' => $room->price_per_night,
                               'name' => $room->name
                        ];
                       })->values())'>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column - Booking Form -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <!-- Check-in/Check-out Dates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                                    <div class="date-input-wrapper">
                                            <input type="text" 
                                                   name="check_in_date" 
                                                   id="check_in_date" 
                                                   class="date-input" 
                                                   placeholder="Select check-in date"
                                                   readonly
                                               autocomplete="off"
                                               data-input
                                                   required>
                                            <i class="fas fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                                    <div class="date-input-wrapper">
                                            <input type="text" 
                                                   name="check_out_date" 
                                                   id="check_out_date" 
                                                   class="date-input" 
                                                   placeholder="Select check-out date"
                                                   readonly
                                               autocomplete="off"
                                               data-input
                                                   required>
                                            <i class="fas fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Guest Details Section -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Guest Details*</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                            <input type="text" 
                                                   id="full_name"
                                                   name="full_name" 
                                                   placeholder="Full Name as per ID Card" 
                                                   autocomplete="name"
                                                   required
                                               value="{{ auth()->user()->name }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" 
                                                   id="email"
                                                   name="email" 
                                                   placeholder="Email" 
                                                   autocomplete="email"
                                                   required
                                               readonly
                                               value="{{ auth()->user()->email }}"
                                               class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                            <input type="tel" 
                                                   id="phone"
                                                   name="phone" 
                                                   placeholder="Phone Number" 
                                                   autocomplete="tel"
                                                   required
                                               value="{{ auth()->user()->phone }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                        </div>
                                        <div>
                                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                                            <input type="text" 
                                                   id="id_number"
                                                   name="id_number" 
                                                   placeholder="NIK/Passport Number" 
                                                   autocomplete="off"
                                                   required
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Special Request -->
                                <div class="mb-6">
                                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-1">Special Request (Optional)</label>
                                    <textarea id="special_requests"
                                            name="special_requests" 
                                            rows="3" 
                                            placeholder="Any special requests?" 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500"></textarea>
                                </div>

                                <!-- Important Information -->
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-bold text-red-600 mb-4">IMPORTANT INFORMATION</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="font-medium mb-2">⏰ Check-in & Check-out Time</h4>
                                            <ul class="text-sm text-gray-600 space-y-1 ml-4">
                                                <li>• Check-in: From 2:00 PM (WIB)</li>
                                                <li>• Check-out: By 12:00 PM (WIB)</li>
                                                <li class="text-xs text-gray-500">(Early check-in or late check-out? Sure, if available! Additional charges may apply)</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-medium mb-2">🪪 At Check-in</h4>
                                            <p class="text-sm text-gray-600 ml-4">• Don't forget to bring and show your ID card or Passport, okay?</p>
                                        </div>
                                        <div>
                                            <h4 class="font-medium mb-2">💰 Deposit</h4>
                                            <ul class="text-sm text-gray-600 space-y-1 ml-4">
                                                <li>• A deposit of Rp50,000 per room will be collected at check-in.</li>
                                                <li>• Don't worry, we'll return it at check-out as long as the room is in good condition.</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-medium mb-2">📋 Cancellation Policy</h4>
                                            <ul class="text-sm text-gray-600 space-y-1 ml-4">
                                                <li>• 7 days or more before check-in: Full 100% refund</li>
                                                <li>• 3-6 days before check-in: 50% refund</li>
                                                <li>• Less than 3 days before check-in: Sorry, no refund</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Booking Details -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-bold">BOOKING DETAILS</h3>
                                    <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-lg text-sm">Booking Now</span>
                                </div>
                                
                                <!-- Room Navigation -->
                                <div class="flex items-center justify-between mb-4">
                                    <button type="button" class="text-gray-500 hover:text-gray-700 disabled:opacity-50" id="prevRoomBtn">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="text-sm text-gray-600">
                                        Room <span id="currentRoomIndex">1</span> of <span id="totalRooms">{{ count($selectedRooms) }}</span>
                                    </span>
                                    <button type="button" class="text-gray-500 hover:text-gray-700 disabled:opacity-50" id="nextRoomBtn">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <!-- Selected Room Display -->
                                <div class="room-carousel relative overflow-hidden mb-6">
                                    <div class="room-slider flex transition-transform duration-300" style="transform: translateX(0);">
                                        @foreach($selectedRooms as $index => $room)
                                        <div class="room-slide flex-none w-full">
                                            <div class="p-4 bg-gray-50 rounded-lg">
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ asset('storage/images/' . $room->image) }}" 
                                                         alt="{{ $room->name }}" 
                                                         class="w-20 h-20 object-cover rounded-lg">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium">{{ $room->name }}</h4>
                                                        <div class="flex gap-2 mt-1">
                                                            <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">{{ $room->capacity }} Guests</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-1">
                                                            <i class="fas fa-calendar"></i>
                                                            <span class="summary_check_in">Select date</span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <i class="fas fa-calendar"></i>
                                                            <span class="summary_check_out">Select date</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-1 text-sm text-gray-600 mt-2">
                                                        <i class="fas fa-moon"></i>
                                                        <span class="total_nights">0</span> nights
                                                    </div>
                                                    <div class="mt-2 text-sm font-medium">
                                                        <span class="text-gray-600">IDR {{ number_format($room->price_per_night, 0, ',', ',') }} x </span>
                                                        <span class="total_nights">0</span>
                                                        <span class="text-gray-600"> nights</span>
                                                    </div>
                                                    <div class="mt-1 font-medium">
                                                        <span class="room_subtotal" data-price="{{ $room->price_per_night }}">IDR 0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Room Navigation Dots -->
                                <div class="flex justify-center gap-2 mb-6">
                                    @foreach($selectedRooms as $index => $room)
                                    <button type="button" 
                                            class="room-navigation-dot w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-orange-500 w-4' : 'bg-gray-300' }}"
                                            data-index="{{ $index }}">
                                    </button>
                                    @endforeach
                                </div>

                                <!-- Price Breakdown -->
                                <div class="space-y-3 mb-6 pt-4 border-t">
                                    <div class="flex justify-between text-gray-600">
                                        <span>SUB TOTAL</span>
                                        <span id="total_subtotal">IDR 0</span>
                                    </div>
                                    <div class="flex justify-between font-bold">
                                        <span>Total</span>
                                        <span id="total">IDR 0</span>
                                    </div>
                                </div>

                                <!-- Terms Checkbox -->
                                <div class="mb-6">
                                    <label class="flex items-start gap-2 text-sm text-gray-600">
                                        <input type="checkbox" 
                                               id="terms_accepted"
                                               name="terms_accepted" 
                                               required
                                               class="mt-1">
                                        <span>By booking, you have agreed to our IMPORTANT INFORMATION outlined below.</span>
                                    </label>
                                </div>

                                <!-- Complete Booking Button -->
                                <button type="submit" 
                                        class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold">
                                    COMPLETE BOOKING
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
    </div>
</div>

    <!-- Payment Panel Section -->
    <div class="payment-panel hidden" id="paymentPanel">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Back Button -->
            <div class="sticky-back-container border-b">
                <button onclick="hidePaymentPanel()" class="flex items-center text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Booking Form
                </button>
        </div>

            <!-- Payment Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Payment Details</h1>
                <p class="text-gray-600 mt-2">Complete your payment to confirm the booking</p>
    </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Payment Methods -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                        <!-- Payment Method Selection -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Payment Method</h3>
                            <div class="space-y-4" id="paymentMethods">
                                <!-- Midtrans Payment -->
                                <label class="relative block">
                                    <input type="radio" name="payment_method" value="midtrans" class="peer sr-only" checked>
                                    <div class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition peer-checked:border-orange-500 peer-checked:ring-1 peer-checked:ring-orange-500">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">Online Payment</h4>
                                            <p class="text-sm text-gray-600">Pay securely with Midtrans</p>
                                            <div class="mt-2 flex items-center gap-2">
                                                <img src="{{ asset('storage/payment/bni.png') }}" alt="Mastercard" class="h-6">
                                                <img src="{{ asset('storage/payment/bca.png') }}" alt="BCA" class="h-6">
                                                <img src="{{ asset('storage/payment/mandiri.png') }}" alt="Mandiri" class="h-6">
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="w-6 h-6 border-2 rounded-full peer-checked:border-orange-500 peer-checked:bg-orange-500 peer-checked:text-white flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Direct Payment -->
                                <label class="relative block">
                                    <input type="radio" name="payment_method" value="direct" class="peer sr-only">
                                    <div class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition peer-checked:border-orange-500 peer-checked:ring-1 peer-checked:ring-orange-500">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">Direct Payment</h4>
                                            <p class="text-sm text-gray-600">Pay directly to our account</p>
                                            <div class="mt-2 text-sm text-gray-600">
                                                <p>Bank Transfer or Cash on Check-in</p>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="w-6 h-6 border-2 rounded-full peer-checked:border-orange-500 peer-checked:bg-orange-500 peer-checked:text-white flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div id="directPaymentInstructions" class="bg-orange-50 rounded-lg p-6 hidden">
                            <h3 class="text-lg font-semibold text-orange-800 mb-4">Payment Instructions</h3>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-orange-800">Bank Transfer</h4>
                                    <ul class="mt-2 space-y-2 text-sm text-orange-700">
                                        <li class="flex items-center">
                                            <span class="w-20 font-medium">Bank</span>
                                            <span>: BCA</span>
                                        </li>
                                        <li class="flex items-center">
                                            <span class="w-20 font-medium">Account No</span>
                                            <span>: 1234567890</span>
                                        </li>
                                        <li class="flex items-center">
                                            <span class="w-20 font-medium">Name</span>
                                            <span>: Cahaya Resort</span>
                                        </li>
                                    </ul>
                                    <div class="mt-4 text-sm text-orange-700">
                                        <p class="font-medium">Important:</p>
                                        <ul class="list-disc ml-5 space-y-1">
                                            <li>Complete payment within 24 hours</li>
                                            <li>Send payment proof via WhatsApp</li>
                                            <li>Include booking reference in transfer description</li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium text-orange-800">Cash on Check-in</h4>
                                    <ul class="mt-2 space-y-1 text-sm text-orange-700 list-disc ml-5">
                                        <li>Prepare the exact amount</li>
                                        <li>Payment must be made during check-in</li>
                                        <li>We accept IDR currency only</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">BOOKING SUMMARY</h3>
                            <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-lg text-sm">To Pay</span>
                        </div>

                        <!-- Summary Details -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Check-in</span>
                                <span class="font-medium" id="payment_check_in">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Check-out</span>
                                <span class="font-medium" id="payment_check_out">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Duration</span>
                                <span class="font-medium"><span id="payment_nights">0</span> nights</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Rooms</span>
                                <span class="font-medium" id="payment_total_rooms">0</span>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6 pt-4 border-t">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-900">Total Amount</span>
                                <span id="payment_total_amount" class="font-bold text-orange-600">IDR 0</span>
                            </div>
                        </div>

                        <!-- Confirm Button -->
                        <button type="button" 
                                onclick="processPayment()" 
                                class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold flex items-center justify-center">
                            <span id="paymentButtonText">PAY NOW</span>
                            <div id="paymentSpinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
// Global variables for room navigation
let currentRoom = 0;
const totalRooms = {{ count($selectedRooms) }};

// Room Navigation Functions
function initializeRoomNavigation() {
    console.log('Initializing room navigation');
    const prevBtn = document.getElementById('prevRoomBtn');
    const nextBtn = document.getElementById('nextRoomBtn');
        const dots = document.querySelectorAll('.room-navigation-dot');
        
    if (!prevBtn || !nextBtn) {
        console.log('Navigation buttons not found');
            return;
        }

    prevBtn.onclick = prevRoom;
    nextBtn.onclick = nextRoom;
    
    dots.forEach(dot => {
        dot.onclick = function() {
            goToRoom(parseInt(this.dataset.index));
        };
    });

    updateRoomDisplay();
}

function updateRoomDisplay() {
    const slider = document.querySelector('.room-slider');
    const dots = document.querySelectorAll('.room-navigation-dot');
        const currentRoomIndexEl = document.getElementById('currentRoomIndex');
        const prevBtn = document.getElementById('prevRoomBtn');
        const nextBtn = document.getElementById('nextRoomBtn');

    if (slider) {
        slider.style.transform = `translateX(-${currentRoom * 100}%)`;
    }

    if (currentRoomIndexEl) {
        currentRoomIndexEl.textContent = currentRoom + 1;
    }

        if (prevBtn) {
        prevBtn.disabled = currentRoom === 0;
        prevBtn.style.opacity = currentRoom === 0 ? '0.5' : '1';
        }

        if (nextBtn) {
        nextBtn.disabled = currentRoom === totalRooms - 1;
        nextBtn.style.opacity = currentRoom === totalRooms - 1 ? '0.5' : '1';
        }
        
        dots.forEach((dot, index) => {
        if (index === currentRoom) {
                dot.classList.add('bg-orange-500', 'w-4');
                dot.classList.remove('bg-gray-300', 'w-2');
            } else {
                dot.classList.remove('bg-orange-500', 'w-4');
                dot.classList.add('bg-gray-300', 'w-2');
            }
        });
}

function prevRoom() {
    if (currentRoom > 0) {
        currentRoom--;
        updateRoomDisplay();
    }
}

function nextRoom() {
    if (currentRoom < totalRooms - 1) {
        currentRoom++;
        updateRoomDisplay();
    }
}

function goToRoom(index) {
    if (index >= 0 && index < totalRooms) {
        currentRoom = index;
        updateRoomDisplay();
    }
}

// Date picker initialization
let checkInPicker = null;
let checkOutPicker = null;

function initializeDatePickers() {
    console.log('Initializing date pickers');
    
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');

    if (!checkInInput || !checkOutInput) {
        console.log('Date inputs not found');
        return;
    }

    // Destroy existing instances if they exist
    if (checkInPicker) {
        checkInPicker.destroy();
        }
    if (checkOutPicker) {
        checkOutPicker.destroy();
    }

    const commonConfig = {
        dateFormat: "Y-m-d",
        minDate: "today",
        allowInput: false,
        disableMobile: true,
        clickOpens: true,
        locale: {
            firstDayOfWeek: 1
        },
        altInput: true,
        altFormat: "d/m/Y"
    };

    // Initialize check-in picker
    checkInPicker = flatpickr(checkInInput, {
        ...commonConfig,
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 1);
                
                if (checkOutPicker) {
                    checkOutPicker.set('minDate', nextDay);
                    
                    const checkOutDate = checkOutPicker.selectedDates[0];
                    if (!checkOutDate || checkOutDate <= selectedDates[0]) {
                        checkOutPicker.setDate(nextDay);
                    }
                }
                updateBookingSummary();
            }
        }
    });

    // Initialize check-out picker
    checkOutPicker = flatpickr(checkOutInput, {
        ...commonConfig,
        onChange: function() {
            updateBookingSummary();
        }
    });

    console.log('Date pickers initialized');
}

function updateBookingSummary() {
    if (!checkInPicker || !checkOutPicker) return;

    const checkInDate = checkInPicker.selectedDates[0];
    const checkOutDate = checkOutPicker.selectedDates[0];

    if (checkInDate && checkOutDate) {
        const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

        // Update display dates (using the alt format)
        document.querySelectorAll('.summary_check_in').forEach(el => {
            el.textContent = checkInPicker.altInput.value;
        });

        document.querySelectorAll('.summary_check_out').forEach(el => {
            el.textContent = checkOutPicker.altInput.value;
        });

        document.querySelectorAll('.total_nights').forEach(el => {
            el.textContent = nights;
        });

        updatePrices(nights);
    }
}

function formatDate(date) {
    return date.toLocaleDateString('en-GB');
}

function updatePrices(nights) {
    let total = 0;
    document.querySelectorAll('.room_subtotal').forEach(el => {
        const pricePerNight = parseInt(el.dataset.price);
        const subtotal = pricePerNight * nights;
        el.textContent = `IDR ${subtotal.toLocaleString('id-ID')}`;
        total += subtotal;
    });

    const totalSubtotalEl = document.getElementById('total_subtotal');
    const totalEl = document.getElementById('total');

    if (totalSubtotalEl) {
        totalSubtotalEl.textContent = `IDR ${total.toLocaleString('id-ID')}`;
    }
    if (totalEl) {
        totalEl.textContent = `IDR ${total.toLocaleString('id-ID')}`;
    }
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    initializeRoomNavigation();
    initializeDatePickers();
});

// Re-initialize when booking panel is shown
document.addEventListener('bookingPanelShown', function() {
    console.log('Booking panel shown event received');
    setTimeout(() => {
    initializeRoomNavigation();
        initializeDatePickers();
    }, 100);
});

// Add form submission handler
function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = document.getElementById('bookingForm');
    
    // Check if form is valid
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }

    // Check if dates are selected
    const checkInDate = document.getElementById('check_in_date').value;
    const checkOutDate = document.getElementById('check_out_date').value;
        
    if (!checkInDate || !checkOutDate) {
        Swal.fire({
            icon: 'error',
            title: 'Please select dates',
            text: 'You must select both check-in and check-out dates.',
            confirmButtonColor: '#f97316'
        });
        return false;
    }

    // Check if terms are accepted
    const termsAccepted = document.getElementById('terms_accepted').checked;
    if (!termsAccepted) {
        Swal.fire({
            icon: 'error',
            title: 'Terms not accepted',
            text: 'You must accept the terms and conditions to proceed.',
            confirmButtonColor: '#f97316'
        });
        return false;
    }

    // Show payment panel
    showPaymentPanel();
    return false;
}

// Update showPaymentPanel function
function showPaymentPanel() {
    const bookingForm = document.getElementById('bookingFormContainer');
    const paymentPanel = document.getElementById('paymentPanel');
    
    if (!bookingForm || !paymentPanel) {
        console.error('Required elements not found');
        return;
    }

    // Get values for payment summary
    const checkInDate = document.getElementById('check_in_date').value;
    const checkOutDate = document.getElementById('check_out_date').value;
    const totalNights = document.querySelectorAll('.total_nights')[0].textContent;
    const totalAmount = document.getElementById('total').textContent;
    const totalRooms = {{ count($selectedRooms) }};

    // Update payment summary
    document.getElementById('payment_check_in').textContent = checkInDate;
    document.getElementById('payment_check_out').textContent = checkOutDate;
    document.getElementById('payment_nights').textContent = totalNights;
    document.getElementById('payment_total_rooms').textContent = totalRooms;
    document.getElementById('payment_total_amount').textContent = totalAmount;

    // Show payment panel with animation
    bookingForm.style.opacity = '0';
    setTimeout(() => {
        bookingForm.style.display = 'none';
        paymentPanel.classList.remove('hidden');
        requestAnimationFrame(() => {
            paymentPanel.style.opacity = '1';
            paymentPanel.style.visibility = 'visible';
            window.scrollTo(0, 0);
        });
    }, 300);
}

// Update hidePaymentPanel function
function hidePaymentPanel() {
    const bookingForm = document.getElementById('bookingFormContainer');
    const paymentPanel = document.getElementById('paymentPanel');
    
    if (!bookingForm || !paymentPanel) {
        console.error('Required elements not found');
        return;
    }

    // Hide payment panel with animation
    paymentPanel.style.opacity = '0';
    paymentPanel.style.visibility = 'hidden';
    setTimeout(() => {
        paymentPanel.classList.add('hidden');
        bookingForm.style.display = 'block';
        requestAnimationFrame(() => {
            bookingForm.style.opacity = '1';
            window.scrollTo(0, 0);
        });
    }, 300);
    
    // Re-initialize components
    setTimeout(() => {
        initializeRoomNavigation();
        initializeDatePickers();
    }, 400);
}

// Add styles for animations
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        #bookingFormContainer,
        #paymentPanel {
            transition: opacity 0.3s ease;
        }
        #paymentPanel {
            opacity: 0;
        }
    `;
    document.head.appendChild(style);
        });

// Payment Method Handling
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const directPaymentInstructions = document.getElementById('directPaymentInstructions');
    const paymentButtonText = document.getElementById('paymentButtonText');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'direct') {
                directPaymentInstructions.classList.remove('hidden');
                paymentButtonText.textContent = 'CONFIRM BOOKING';
    } else {
                directPaymentInstructions.classList.add('hidden');
                paymentButtonText.textContent = 'PAY NOW';
            }
        });
    });
});

// Process Payment Function
function processPayment() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const paymentButton = document.querySelector('button[onclick="processPayment()"]');
    const paymentSpinner = document.getElementById('paymentSpinner');
    const paymentButtonText = document.getElementById('paymentButtonText');

    // Disable button and show spinner
    paymentButton.disabled = true;
    paymentSpinner.classList.remove('hidden');
    
    // Get form data
    const form = document.getElementById('bookingForm');
    const formData = new FormData(form);
    
    // Create the data object
    let jsonData = {};
    
    try {
        // Get selected rooms data
        const selectedRoomsInput = document.getElementById('selected_rooms');
        const selectedRoomsValue = selectedRoomsInput.value;
        let selectedRooms;
        
        try {
            selectedRooms = JSON.parse(selectedRoomsValue);
        } catch (e) {
            console.error('Error parsing selected rooms:', e);
            throw new Error('Invalid room data format');
        }

        if (!Array.isArray(selectedRooms)) {
            throw new Error('Selected rooms must be an array');
        }

        // Get dates in the correct format (Y-m-d)
        const checkInDate = checkInPicker ? checkInPicker.selectedDates[0]?.toISOString().split('T')[0] : null;
        const checkOutDate = checkOutPicker ? checkOutPicker.selectedDates[0]?.toISOString().split('T')[0] : null;

        if (!checkInDate || !checkOutDate) {
            throw new Error('Please select valid check-in and check-out dates');
        }

        // Build the request data
        jsonData = {
            check_in_date: checkInDate,
            check_out_date: checkOutDate,
            full_name: formData.get('full_name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            id_number: formData.get('id_number'),
            special_requests: formData.get('special_requests'),
            payment_method: paymentMethod,
            selected_rooms: JSON.stringify(selectedRooms),
            _token: '{{ csrf_token() }}'
        };

        console.log('Prepared data:', jsonData);
        
        // Validate required fields
        const requiredFields = ['check_in_date', 'check_out_date', 'full_name', 'email', 'phone', 'id_number'];
        const missingFields = requiredFields.filter(field => !jsonData[field]);
        
        if (missingFields.length > 0) {
            throw new Error(`Please fill in all required fields: ${missingFields.join(', ')}`);
        }

    } catch (error) {
        console.error('Error preparing data:', error);
        Swal.fire({
            icon: 'error',
            title: 'Form Error',
            text: error.message || 'Please check your form data and try again.',
            confirmButtonColor: '#f97316'
        });
        resetPaymentButton();
        return;
    }

    // Send booking data to server
    fetch('{{ route("bookings.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(jsonData)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Server error occurred');
        }
        return data;
    })
    .then(result => {
        if (result.success) {
            if (paymentMethod === 'midtrans' && result.snap_token) {
                // Open Midtrans Snap for online payment
                window.snap.pay(result.snap_token, {
                    onSuccess: function(result) {
                        // Log the success
                        console.log('Payment Success:', result);
                        
                        // Finalize the booking with order ID
                        finalizeBooking(result.order_id).then(() => {
                            // Show success message and redirect
                            Swal.fire({
                                icon: 'success',
                                title: 'Payment Successful',
                                text: 'Your payment has been processed successfully.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = '/?panel=transactions&source=midtrans';
                            });
                        });
                    },
                    onPending: function(result) {
                        // Log the pending status
                        console.log('Payment Pending:', result);
                        
                        // Finalize the booking with order ID
                        finalizeBooking(result.order_id).then(() => {
                            Swal.fire({
                                icon: 'info',
                                title: 'Payment Pending',
                                text: 'Please complete your payment using the provided instructions.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = '/?panel=transactions&source=midtrans';
                            });
                        });
                    },
                    onError: function(result) {
                        // Log the error
                        console.error('Payment Error:', result);
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: 'An error occurred during payment. Please try again.',
                            confirmButtonColor: '#f97316'
                        }).then(() => {
                            window.location.href = '/?panel=transactions&source=midtrans';
                        });
                    },
                    onClose: function() {
                        // Log the closure
                        console.log('Payment popup closed');
                        
                        // Redirect to landing page with transaction panel open
                        window.location.href = '/?panel=transactions&source=midtrans';
                    }
                });
            } else {
                // Handle direct payment success
                handleDirectPaymentSuccess(result.booking_id);
            }
        } else {
            throw new Error(result.message || 'Failed to process booking');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Booking Failed',
            text: error.message || 'An error occurred while processing your booking.',
            confirmButtonColor: '#f97316'
        });
        resetPaymentButton();
    });
}

// Function to finalize booking
async function finalizeBooking(orderId) {
    try {
        const response = await fetch('{{ route("bookings.finalize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ order_id: orderId })
        });

        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Failed to finalize booking');
        }
        return data;
    } catch (error) {
        console.error('Error finalizing booking:', error);
        throw error;
    }
}

function handleDirectPaymentSuccess(bookingId) {
    Swal.fire({
        icon: 'success',
        title: 'Booking Confirmed!',
        text: 'Please follow the payment instructions to complete your booking.',
        confirmButtonColor: '#f97316'
    }).then(() => {
        window.location.href = `/bookings/${bookingId}`;
    });
}

function resetPaymentButton() {
    const paymentButton = document.querySelector('button[onclick="processPayment()"]');
    const paymentSpinner = document.getElementById('paymentSpinner');
    const paymentButtonText = document.getElementById('paymentButtonText');
    
    paymentButton.disabled = false;
    paymentSpinner.classList.add('hidden');
    paymentButtonText.textContent = document.querySelector('input[name="payment_method"]:checked').value === 'direct' ? 'CONFIRM BOOKING' : 'PAY NOW';
}
</script>
@endpush
@endsection