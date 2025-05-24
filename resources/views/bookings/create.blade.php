@extends('layouts.app')

@section('head')
<!-- Flatpickr Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #f59e0b;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #e67e22;
    }

    /* Date picker customization */
    .flatpickr-calendar {
        background: #1f2937 !important;
        border-color: #374151 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }

    .flatpickr-day {
        color: #fff !important;
        border-radius: 5px !important;
    }

    .flatpickr-day.flatpickr-disabled {
        color: #ef4444 !important;
        text-decoration: line-through !important;
        background-color: rgba(239, 68, 68, 0.1) !important;
    }

    .flatpickr-day.flatpickr-disabled:hover {
        background-color: rgba(239, 68, 68, 0.2) !important;
    }

    .flatpickr-months .flatpickr-month {
        color: #fff !important;
        fill: #fff !important;
    }

    .flatpickr-weekdays {
        color: #9ca3af !important;
    }

    .flatpickr-day.selected {
        background: #f59e0b !important;
        border-color: #f59e0b !important;
    }

    .flatpickr-day:hover {
        background: #374151 !important;
    }

    /* Custom date input styling */
    .date-input-wrapper {
        position: relative;
        width: 100%;
    }

    .date-input {
        width: 100%;
        padding: 0.5rem 1rem;
        padding-right: 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        color: #1f2937;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .date-input:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .calendar-icon {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    /* Unavailable dates styling */
    .unavailable-date {
        color: #ef4444 !important;
        text-decoration: line-through;
        pointer-events: none;
        opacity: 0.7;
        background-color: rgba(239, 68, 68, 0.1);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reservation Cahaya Resort</h1>
            <p class="text-gray-600 mt-2">Fill the blank to complete booking information</p>
            </div>
            
        <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" autocomplete="off">
                @csrf
            <!-- Add hidden inputs for selected room IDs -->
            @foreach($selectedRooms as $room)
                <input type="hidden" name="room_ids[]" value="{{ $room->id }}">
                            @endforeach

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
                                           autocomplete="off"
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
                                           autocomplete="off"
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
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" 
                                           id="phone"
                                           name="phone" 
                                           placeholder="Phone Number" 
                                           autocomplete="tel"
                                           required
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
                            <label for="special_request" class="block text-sm font-medium text-gray-700 mb-1">Special Request (Optional)</label>
                            <textarea id="special_request"
                                    name="special_request" 
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
                        
                        <!-- Selected Rooms Summary with Scroll -->
                        <div class="max-h-[400px] overflow-y-auto pr-2 mb-6 custom-scrollbar">
                            @foreach($selectedRooms as $room)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg mb-4 last:mb-0">
                                <img src="{{ asset('storage/images/' . $room->image) }}" 
                                     alt="{{ $room->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ $room->name }}</h4>
                                    <div class="flex gap-2 mt-1">
                                        <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">{{ $room->capacity }} Guests</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-2">
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-calendar"></i>
                                            <span class="summary_check_in">Select date</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-calendar"></i>
                                            <span class="summary_check_out">Select date</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 text-sm text-gray-600">
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

@push('scripts')
<!-- Flatpickr Script -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');

    const selectedRooms = @json($selectedRooms);
    const unavailableDates = @json($unavailableDates ?? []);
    let checkInPicker, checkOutPicker;

    // Initialize date pickers
    checkInPicker = flatpickr("#check_in_date", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr) {
            if (selectedDates[0]) {
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 1);
                
                checkOutPicker.set('minDate', nextDay);
                
                if (checkOutPicker.selectedDates[0] && checkOutPicker.selectedDates[0] <= selectedDates[0]) {
                    checkOutPicker.clear();
                }
            }
            updateSummary();
        }
    });

    checkOutPicker = flatpickr("#check_out_date", {
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(1),
        disableMobile: "true",
        onChange: function(selectedDates) {
            updateSummary();
        }
    });

    // Add click handlers to wrapper divs
    document.querySelectorAll('.date-input-wrapper').forEach(wrapper => {
        wrapper.addEventListener('click', function(e) {
            const input = this.querySelector('input');
            if (input && e.target !== input) {
                input.focus();
            }
        });
    });

    function updateSummary() {
        const checkIn = checkInPicker.selectedDates[0];
        const checkOut = checkOutPicker.selectedDates[0];

        if (checkIn && checkOut) {
            const checkInStr = formatDate(checkIn);
            const checkOutStr = formatDate(checkOut);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

            // Update all room summaries
            document.querySelectorAll('.summary_check_in').forEach(el => {
                el.textContent = checkInStr;
            });
            document.querySelectorAll('.summary_check_out').forEach(el => {
                el.textContent = checkOutStr;
            });
            document.querySelectorAll('.total_nights').forEach(el => {
                el.textContent = nights;
            });

            // Calculate subtotals for each room
            let totalAmount = 0;
            document.querySelectorAll('.room_subtotal').forEach(el => {
                const pricePerNight = parseInt(el.dataset.price);
                const roomSubtotal = nights * pricePerNight;
                el.textContent = `IDR ${formatNumber(roomSubtotal)}`;
                totalAmount += roomSubtotal;
            });

            // Update total amounts
            document.getElementById('total_subtotal').textContent = `IDR ${formatNumber(totalAmount)}`;
            document.getElementById('total').textContent = `IDR ${formatNumber(totalAmount)}`;
        } else {
            resetSummary();
        }
    }

    function resetSummary() {
        document.querySelectorAll('.summary_check_in').forEach(el => {
            el.textContent = 'Select date';
        });
        document.querySelectorAll('.summary_check_out').forEach(el => {
            el.textContent = 'Select date';
        });
        document.querySelectorAll('.total_nights').forEach(el => {
            el.textContent = '0';
        });
        document.querySelectorAll('.room_subtotal').forEach(el => {
            el.textContent = 'IDR 0';
        });
        document.getElementById('total_subtotal').textContent = 'IDR 0';
        document.getElementById('total').textContent = 'IDR 0';
    }

    function formatDate(date) {
        return date.toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Form validation
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const checkIn = checkInPicker.selectedDates[0];
        const checkOut = checkOutPicker.selectedDates[0];

        if (!checkIn || !checkOut) {
            e.preventDefault();
            alert('Please select both check-in and check-out dates');
            return false;
        }
    });
});
</script>
@endpush

@endsection