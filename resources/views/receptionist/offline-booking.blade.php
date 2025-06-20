@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .room-card {
    transition: all 0.3s ease;
}
    .room-card.selected {
        border-color: #f97316;
        background-color: #2D2D2D;
        color: #fff;
    }
    .room-card.maintenance {
        opacity: 0.92;
        cursor: not-allowed !important;
        background-color: #3b0d0c;
    }
    .step-content {
    display: none;
    }
    .step-content.active {
        display: block;
    }
    .sidebar {
    position: sticky;
        top: 2rem;
        height: calc(60vh - 4rem);
        overflow-y: auto;
    background: #2D2D2D;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .selected-room-card, .selected-room-card * {
        background: #2D2D2D !important;
        color: #fff !important;
        border: 1.5px solid #1D1D1D !important;
    }
    .selected-room-card:hover {
        border-color: #f97316;
        background-color: #252525;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .selected-room-info h4 {
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.25rem;
    }
    .selected-room-price {
        color: #fff;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    .remove-room-btn {
        color: #ef4444;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
    }
    .remove-room-btn:hover {
        background-color: #fee2e2;
    }
    .sidebar-footer {
        position: sticky;
        bottom: 0;
        background: #2D2D2D;
        padding: 1rem;
        border-top: 2px solid #FFA040;
        border-radius: 0 0 1rem 1rem;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .total-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.5rem 0;
    }
    .total-label {
        font-size: 1rem;
        font-weight: 500;
        color: #fff;
    }
    .total-amount {
        font-size: 1.25rem;
        font-weight: 600;
        color: #f97316;
    }
    .continue-btn {
        width: 100%;
        padding: 0.75rem;
        background-color: #f97316;
        color: white;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .continue-btn:hover:not(:disabled) {
        background-color: #ea580c;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.2);
    }
    .continue-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    #selectedRoomsList {
        max-height: calc(100% - 140px);
        overflow-y: auto;
        padding: 1rem;
        background: #2D2D2D;
        color: #fff;
    }
    #selectedRoomsList::-webkit-scrollbar {
        width: 4px;
    }
    #selectedRoomsList::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 2px;
    }
    #selectedRoomsList::-webkit-scrollbar-thumb {
        background-color: #f97316;
        border-radius: 2px;
    }
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: "*";
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .form-input {
    width: 100%;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    transition: all 0.3s ease;
}

    .form-input:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    .form-input.error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    display: none;
    }

    .error-message.visible {
        display: block;
    }

    .form-input.error:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .validation-alert {
        position: fixed;
        top: 1rem;
        right: 1rem;
    padding: 1rem;
        border-radius: 0.5rem;
        background-color: #fef2f2;
        border: 1px solid #ef4444;
        color: #ef4444;
        z-index: 50;
        display: none;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
}

/* Date input styling */
.date-input-wrapper {
    position: relative;
}

input.date-input {
    width: 100% !important;
    box-sizing: border-box !important;
    padding: 1em !important;
    padding-right: 2.5rem !important;
    border: 1px solid #d1d5db !important;
    border-radius: 15px !important;
    background-color: #ccc !important;
    box-shadow: inset 2px 5px 10px rgba(0,0,0,0.3) !important;
    transition: 300ms ease-in-out !important;
    cursor: pointer !important;
}

input.date-input:focus {
    background-color: white !important;
    transform: scale(1.05) !important;
    box-shadow: 13px 13px 100px #969696, -13px -13px 100px #ffffff !important;
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

/* Add custom styles for booked and disabled dates */
.flatpickr-day.fully-booked {
    background-color: #fee2e2 !important;
    color: #991b1b !important;
    text-decoration: line-through;
    pointer-events: none;
    opacity: 0.7;
}

.flatpickr-day.fully-booked:hover {
    background-color: #fecaca !important;
}

.flatpickr-day.disabled-date {
    color: #ccc !important;
    background-color: transparent !important;
    pointer-events: none;
    text-decoration: none;
    opacity: 0.3;
}

.room-type-filter-btn {
    transition: all 0.2s;
    cursor: pointer;
}
.room-type-filter-btn.selected,
.room-type-filter-btn:focus {
    background: #FFA040 !important;
    color: #fff !important;
    border: none;
    outline: none;
}
.room-type-filter-btn:hover {
    background: #FFA040;
    color: #fff;
}

.room-card, .room-card * {
    color: #F0F0F0 !important;
}
.room-card .text-orange-600, .room-card .text-orange-700, .room-card .text-orange-500, .room-card .text-yellow-500, .room-card .text-yellow-400 {
    color: #FFA040 !important;
}
.room-card .bg-green-100, .room-card .text-green-800 {
    color: #16a34a !important;
    background: #bbf7d0 !important;
}
.room-card .bg-red-100, .room-card .text-red-800 {
    color: #dc2626 !important;
    background: #fee2e2 !important;
}
.room-card .text-sm.text-gray-600, .room-card .text-gray-900, .room-card .text-gray-500 {
    color: #F0F0F0 !important;
}
.selected-room-card, .selected-room-card * {
    background: #1D1D1D !important;
    color: #fff !important;
}
.selected-room-card .text-orange-500, .selected-room-card .text-orange-600 {
    color: #FFA040 !important;
}
.sidebar, .sidebar * {
    color: #F0F0F0 !important;
}
.sidebar .text-orange-500, .sidebar .text-orange-600 {
    color: #FFA040 !important;
}
.sidebar .total-amount {
    color: #FFA040 !important;
}
.sidebar input,
.sidebar select,
.sidebar textarea {
    background: #2D2D2D !important;
    color: #fff !important;
    border: 1.5px solid #1D1D1D !important;
    border-radius: 8px !important;
}
.sidebar input::placeholder,
.sidebar textarea::placeholder {
    color: #bbb !important;
    opacity: 1 !important;
}
.sidebar label,
.sidebar .form-label {
    color: #fff !important;
}

/* Guest Details Form Styling */
.step-content input,
.step-content select,
.step-content textarea {
    background: #2D2D2D !important;
    color: #fff !important;
    border: 1.5px solid #bbb !important;
    border-radius: 12px !important;
    font-size: 1rem;
    transition: border 0.2s, box-shadow 0.2s;
}
.step-content input::placeholder,
.step-content textarea::placeholder {
    color: #bbb !important;
    opacity: 1 !important;
}
.step-content input:focus,
.step-content select:focus,
.step-content textarea:focus {
    border: 1.5px solid #FFA040 !important;
    box-shadow: 0 0 0 2px #FFA04033 !important;
    outline: none !important;
    background: #252525 !important;
    color: #fff !important;
}
.step-content label,
.step-content .form-label {
    color: #fff !important;
}

/* Custom select arrow and hover for Payment Status */
.step-content select {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background: #2D2D2D url('data:image/svg+xml;utf8,<svg fill="%23FFA040" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>') no-repeat right 1rem center/1.2em 1.2em !important;
    padding-right: 2.5rem !important;
    cursor: pointer;
}
.step-content select:focus {
    border: 1.5px solid #FFA040 !important;
    box-shadow: 0 0 0 2px #FFA04033 !important;
    outline: none !important;
    background-color: #252525 !important;
}
/* Custom dropdown option hover (works in some browsers) */
.step-content select option:hover, .step-content select option:checked {
    background: #FFA040 !important;
    color: #fff !important;
}
</style>
@endpush

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<x-receptionist-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight text-center">
            {{ __('Offline Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="step-indicator flex items-center">
                        <div id="step1-indicator" class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold">1</div>
                        <div class="ml-2 text-sm font-medium text-gray-100">Select Rooms</div>
                                        </div>
                    <div class="h-px w-16 bg-gray-300"></div>
                    <div class="step-indicator flex items-center">
                        <div id="step2-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold">2</div>
                        <div class="ml-2 text-sm font-medium text-gray-100">Guest Details</div>
                                        </div>
                                    </div>
                                </div>

            <div class="flex gap-6">
                <!-- Main Content Area -->
                <div class="flex-1">
                    <!-- Step 1: Room Selection -->
                    <div id="step1" class="step-content active">
                        <div class="relative overflow-hidden shadow-xl sm:rounded-lg p-6" style="background:#2D2D2D;">
                            <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2" style="color:#FFA040;">Available Rooms</h3>
                                <p class="text-sm text-white">Select one or more rooms to book</p>
                            </div>

                            <!-- Room Type Filter -->
                            <div class="mb-6 flex flex-wrap gap-2">
                                <button type="button" onclick="selectRoomTypeFilter(this, 'all')" class="room-type-filter-btn selected px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">All Rooms</button>
                                <button type="button" onclick="selectRoomTypeFilter(this, 'standard')" class="room-type-filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Standard</button>
                                <button type="button" onclick="selectRoomTypeFilter(this, 'deluxe')" class="room-type-filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Deluxe</button>
                                <button type="button" onclick="selectRoomTypeFilter(this, 'suite')" class="room-type-filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Suite</button>
                            </div>

                            <!-- Room Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($rooms as $room)
                                <div class="room-card border-2 rounded-lg p-4 {{ $room->status === 'maintenance' ? 'maintenance' : 'cursor-pointer hover:border-orange-300' }}" 
                                     data-room-id="{{ $room->id }}"
                                     data-room-type="{{ $room->type }}"
                                     data-room-name="{{ $room->name }}"
                                     data-room-price="{{ $room->price_per_night }}"
                                     data-room-status="{{ $room->status }}"
                                     onclick="{{ $room->status !== 'maintenance' ? 'toggleRoomSelection(this)' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $room->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ ucfirst($room->type) }} Room</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($room->status) }}
                                    </span>
                                                </div>
                                                <div class="mt-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            {{ $room->capacity }} Persons
                                                        </div>
                                        <div class="mt-2 text-lg font-bold text-orange-600">
                                            Rp {{ number_format($room->price_per_night, 0, ',', '.') }}/night
                                                        </div>
                                        @if($room->status === 'maintenance')
                                        <div class="mt-2 text-sm text-red-600">
                                            This room is currently under maintenance
                                                    </div>
                                        @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                </div>

                    <!-- Step 2: Guest Details Form -->
                    <div id="step2" class="step-content">
                        <div class="relative overflow-hidden shadow-xl sm:rounded-lg p-6" style="background:#2D2D2D;">
                            <div style="position:absolute;left:0;top:0;height:100%;width:6px;background:#FFA040;"></div>
                            <div class="flex items-center mb-6">
                                <button onclick="goToStep(1)" class="mr-4 text-gray-600 hover:text-gray-900">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                        <div>
                                    <h3 class="text-lg font-semibold mb-2" style="color:#FFA040;">Guest Details</h3>
                                    <p class="text-sm text-gray-600">Fill in the guest information</p>
                                            </div>
                                        </div>

                            <form id="bookingForm" method="POST" action="{{ route('receptionist.offline-booking.store') }}" onsubmit="return validateForm(event)">
                                @csrf
                                <input type="hidden" name="booked_by" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="selected_rooms" id="selected_rooms_data">
                                
                                <!-- Date Selection -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="check_in_date" class="form-label required">Check-in Date</label>
                                        <div class="date-input-wrapper">
                                            <input type="text" 
                                                   id="check_in_date" 
                                                   name="check_in_date" 
                                                   class="form-input date-input" 
                                                   placeholder="Select check-in date" 
                                                   required 
                                                   readonly>
                                            <i class="fas fa-calendar calendar-icon"></i>
                                            </div>
                                        <div class="error-message">Please select a check-in date</div>
                                        </div>
                                    <div class="form-group">
                                        <label for="check_out_date" class="form-label required">Check-out Date</label>
                                        <div class="date-input-wrapper">
                                            <input type="text" 
                                                   id="check_out_date" 
                                                   name="check_out_date" 
                                                   class="form-input date-input" 
                                                   placeholder="Select check-out date" 
                                                   required 
                                                   readonly>
                                            <i class="fas fa-calendar calendar-icon"></i>
                                    </div>
                                        <div class="error-message">Please select a check-out date</div>
                            </div>
                        </div>

                                <!-- Guest Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="full_name" class="form-label required">Guest Full Name</label>
                                        <input type="text" 
                                               id="full_name" 
                                               name="full_name" 
                                               class="form-input" 
                                               placeholder="Enter guest's full name" 
                                               required>
                                        <div class="error-message">Please enter guest's full name</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label required">Phone Number</label>
                                        <input type="tel" 
                                               id="phone" 
                                               name="phone" 
                                               class="form-input" 
                                               placeholder="Enter guest's phone number" 
                                               required>
                                        <div class="error-message">Please enter a valid phone number</div>
                                </div>
                                    <div class="form-group">
                                        <label for="id_number" class="form-label required">ID Number</label>
                                        <input type="text" 
                                               id="id_number" 
                                               name="id_number" 
                                               class="form-input" 
                                               placeholder="Enter guest's ID number" 
                                               required>
                                        <div class="error-message">Please enter a valid ID number</div>
                                </div>
                                    <div class="form-group" style="position:relative;">
                                        <label for="payment_status" class="form-label required">Payment Status</label>
                                        <div style="position:relative;">
                                            <select id="payment_status" 
                                                    name="payment_status" 
                                                    class="form-input" 
                                                    required
                                                    style="background-image:none !important; padding-right:2.5rem !important;">
                                                <option value="">Select payment status</option>
                                                <option value="pending">Pending</option>
                                                <option value="paid">Paid</option>
                                                <option value="deposit">Deposit Paid</option>
                                            </select>
                                            <svg class="select-arrow" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);pointer-events:none;transition:transform 0.2s;fill:#FFA040;width:1.2em;height:1.2em;" viewBox="0 0 20 20"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>
                                        </div>
                                        <div class="error-message">Please select payment status</div>
                                    </div>
                                </div>

                                <!-- Special Requests -->
                                <div class="form-group">
                                    <label for="special_requests" class="form-label">Special Requests</label>
                                    <textarea id="special_requests" 
                                              name="special_requests" 
                                              class="form-input" 
                                              rows="3" 
                                              placeholder="Enter any special requests"></textarea>
                        </div>
                            </form>
                            </div>
                            </div>
                        </div>

                <!-- Sidebar - Selected Rooms Summary -->
                <div class="w-80">
                    <div class="sidebar" style="background:#2D2D2D;">
                        <div id="selectedRoomsSummary">
                            <h3 class="text-lg font-semibold p-4 border-b" style="color:#FFA040;">Selected Rooms</h3>
                            <div id="selectedRoomsList" class="p-4 space-y-4" style="color:#fff;">
                                <!-- Selected rooms will be listed here -->
                            </div>
                            <div class="sidebar-footer">
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between items-center text-sm text-gray-600">
                                        <span>Duration:</span>
                                        <span id="totalNights">0 night(s)</span>
                                    </div>
                                    <div class="flex justify-between items-center font-medium">
                                        <span>Total:</span>
                                        <span id="totalPrice" class="text-lg text-orange-500">Rp 0</span>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="submitBookingForm()" 
                                        class="w-full py-3 px-4 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all font-medium"
                                        id="actionButton"
                                        disabled>
                                    Continue to Guest Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
        // Global variables
        let selectedRooms = new Map();
        let currentStep = 1;
        let checkInPicker = null;
        let checkOutPicker = null;

        // Store booked dates cache
        window.allBookedDatesCache = {};
        window.unionBookedDatesCache = {};

        // Initialize Flatpickr
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
                altFormat: "Y-m-d",
                formatDate: (date) => {
                    return formatDateForDatabase(date);
                },
                parseDate: (dateStr) => {
                    const [year, month, day] = dateStr.split('-');
                    const date = new Date(year, parseInt(month) - 1, parseInt(day));
                    date.setHours(12, 0, 0, 0);
                    return date;
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                    const selectedRoomsArray = Array.from(selectedRooms.values());
                    
                    // For check-out calendar, disable dates before check-in
                    if (this.input.id === 'check_out_date' && checkInPicker.selectedDates[0]) {
                        const checkInDate = checkInPicker.selectedDates[0];
                        if (dayElem.dateObj < checkInDate) {
                            dayElem.classList.add('disabled-date');
                            return;
                        }
                    }
                    
                    // For display purposes, we need to check if this date is a check-in date
                    const isCheckInDate = (roomId) => {
                        if (window.allBookedDatesCache[roomId]) {
                            // Get the previous day
                            const prevDay = new Date(dayElem.dateObj);
                            prevDay.setDate(prevDay.getDate() - 1);
                            const prevDayStr = prevDay.toISOString().split('T')[0];
                            
                            // This date is a check-in date if it's booked and the previous day isn't
                            return window.allBookedDatesCache[roomId][dateStr] && 
                                   !window.allBookedDatesCache[roomId][prevDayStr];
                        }
                        return false;
                    };

                    // Check if this date is a check-out date
                    const isCheckOutDate = (roomId) => {
                        if (window.allBookedDatesCache[roomId]) {
                            // Get the next day
                            const nextDay = new Date(dayElem.dateObj);
                            nextDay.setDate(nextDay.getDate() + 1);
                            const nextDayStr = nextDay.toISOString().split('T')[0];
                            
                            // This date is a check-out date if it's booked but the next day isn't
                            return window.allBookedDatesCache[roomId][dateStr] && 
                                   !window.allBookedDatesCache[roomId][nextDayStr];
                        }
                        return false;
                    };
                    
                    if (selectedRoomsArray.length > 1) {
                        // For multiple rooms
                        const prevDay = new Date(dayElem.dateObj);
                        prevDay.setDate(prevDay.getDate() - 1);
                        const prevDayStr = prevDay.toISOString().split('T')[0];
                        
                        const nextDay = new Date(dayElem.dateObj);
                        nextDay.setDate(nextDay.getDate() + 1);
                        const nextDayStr = nextDay.toISOString().split('T')[0];
                        
                        if (dateStr in window.unionBookedDatesCache) {
                            // If it's a check-out date, don't mark as booked
                            if (!(nextDayStr in window.unionBookedDatesCache)) {
                                return;
                            }
                            // Otherwise mark as booked (including check-in dates)
                            dayElem.classList.add('fully-booked');
                        }
                    } else if (selectedRoomsArray.length === 1) {
                        // For single room
                        const roomId = selectedRoomsArray[0].id;
                        if (window.allBookedDatesCache[roomId] && 
                            window.allBookedDatesCache[roomId][dateStr]) {
                            
                            // If it's a check-out date, don't mark as booked
                            if (isCheckOutDate(roomId)) {
                                return;
                            }
                            
                            // Otherwise mark as booked (including check-in dates)
                            dayElem.classList.add('fully-booked');
                        }
                    }
                }
            };

            // Initialize check-in picker
            checkInPicker = flatpickr(checkInInput, {
                ...commonConfig,
                onChange: function(selectedDates) {
                    if (selectedDates[0]) {
                        const selectedDate = selectedDates[0];
                        
                        // Store the selected date directly without modification
                        this.input.value = formatDateForDatabase(selectedDate);
                        
                        if (checkOutPicker) {
                            const minCheckOut = new Date(selectedDate);
                            minCheckOut.setDate(minCheckOut.getDate() + 1);
                            checkOutPicker.set('minDate', minCheckOut);
                            
                            const checkOutDate = checkOutPicker.selectedDates[0];
                            if (checkOutDate) {
                                window.checkOutDate = checkOutDate;
                                const hasConflict = checkForDateRangeConflict(selectedDate, checkOutDate);
                                if (hasConflict) {
                                    // Store current calendar view before clearing
                                    const currentMonth = checkOutPicker.currentMonth;
                                    const currentYear = checkOutPicker.currentYear;
                                    
                                    checkOutPicker.clear();
                                    window.checkOutDate = null;
                                    
                                    // Restore calendar view after clearing
                                    checkOutPicker.jumpToDate(new Date(currentYear, currentMonth));
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Booking Conflict',
                                        text: 'One or more selected rooms are not available for the entire date range.',
                                        confirmButtonColor: '#f97316'
                                    });
                                }
                            }
                            // Automatically open check-out picker after selecting check-in date
                            setTimeout(() => {
                                checkOutPicker.open();
                            }, 100);
                        }
                updateBookingSummary();
                    }
                },
                onOpen: function() {
                    // If check-out is already open, close it
                    if (checkOutPicker && checkOutPicker.isOpen) {
                        checkOutPicker.close();
                    }
                }
            });

            // Initialize check-out picker
            checkOutPicker = flatpickr(checkOutInput, {
                ...commonConfig,
                onChange: function(selectedDates) {
                    if (selectedDates[0] && checkInPicker.selectedDates[0]) {
                        const selectedDate = selectedDates[0];
                        
                        // Store the selected date directly without modification
                        this.input.value = formatDateForDatabase(selectedDate);
                        
                        window.checkOutDate = selectedDate;
                        const hasConflict = checkForDateRangeConflict(checkInPicker.selectedDates[0], selectedDate);
                        if (hasConflict) {
                            // Store current calendar view before clearing
                            const currentMonth = this.currentMonth;
                            const currentYear = this.currentYear;
                            
                            this.clear();
                            window.checkOutDate = null;
                            
                            // Restore calendar view after clearing
                            this.jumpToDate(new Date(currentYear, currentMonth));
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Booking Conflict',
                                text: 'One or more selected rooms are not available for the entire date range.',
                                confirmButtonColor: '#f97316'
                            });
                        } else {
                    updateBookingSummary();
            }
                    }
                },
                onOpen: function() {
                    // If no check-in date is selected, close check-out and open check-in
                    if (!checkInPicker.selectedDates[0]) {
                        this.close();
                        setTimeout(() => {
                            checkInPicker.open();
                        }, 100);
                        // Optional: Show a gentle reminder
                        Swal.fire({
                            icon: 'info',
                            title: 'Pilih Tanggal Check-in',
                            text: 'Silakan pilih tanggal check-in terlebih dahulu',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                }
            });
        }

        // Function to check if there are any conflicts in the date range
        function checkForDateRangeConflict(startDate, endDate) {
            const selectedRoomsArray = Array.from(selectedRooms.values());
            
            // Create array of dates between start and end, EXCLUDING the end date
            const dates = [];
            const currentDate = new Date(startDate);
            const end = new Date(endDate);
            
            // Only check dates up to but not including the end date
            while (currentDate < end) {
                const dateStr = currentDate.toISOString().split('T')[0];
                dates.push(dateStr);
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            // Check each date for conflicts
            return dates.some(dateStr => {
                if (selectedRoomsArray.length > 1) {
                    // For multiple rooms, check if the date exists in unionBookedDates
                    // AND it's not a check-out date
                    const nextDay = new Date(dateStr);
                    nextDay.setDate(nextDay.getDate() + 1);
                    const nextDayStr = nextDay.toISOString().split('T')[0];
                    
                    return dateStr in window.unionBookedDatesCache && 
                           nextDayStr in window.unionBookedDatesCache;
                } else if (selectedRoomsArray.length === 1) {
                    // For single room, check that room's specific dates
                    const roomId = selectedRoomsArray[0].id;
                    if (!window.allBookedDatesCache[roomId]) return false;
                    
                    // Check if this date is booked AND it's not a check-out date
                    const nextDay = new Date(dateStr);
                    nextDay.setDate(nextDay.getDate() + 1);
                    const nextDayStr = nextDay.toISOString().split('T')[0];
                    
                    return window.allBookedDatesCache[roomId][dateStr] && 
                           window.allBookedDatesCache[roomId][nextDayStr];
                }
                return false;
            });
        }

        // Add helper function for consistent date formatting
        function formatDateForDatabase(date) {
            // Ensure we're working with a Date object
            if (!(date instanceof Date)) {
                date = new Date(date);
            }
            
            // Get the date components in local timezone
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            // Return in YYYY-MM-DD format
            return `${year}-${month}-${day}`;
        }

        // Function to update booked dates for all selected rooms
        async function updateBookedDates() {
            try {
                const selectedRoomsArray = Array.from(selectedRooms.values());
                if (selectedRoomsArray.length > 0) {
                    // Clear existing booked dates cache
                    window.allBookedDatesCache = {};
                    window.unionBookedDatesCache = {};

                    // Show loading state in calendar
                    if (checkInPicker) {
                        checkInPicker._input.placeholder = 'Loading dates...';
                    }
                    if (checkOutPicker) {
                        checkOutPicker._input.placeholder = 'Loading dates...';
                    }

                    // Fetch new booked dates for each room
                    for (const room of selectedRoomsArray) {
                        try {
                            const response = await fetch(`/get-booked-dates/${room.id}`);
                            if (!response.ok) {
                                throw new Error(`Failed to fetch booked dates for room ${room.id}`);
                            }
                            
                            const data = await response.json();
                            
                            if (data.unavailable_dates) {
                                // Update the global booked dates cache
                                window.allBookedDatesCache[room.id] = {};
                                data.unavailable_dates.forEach(date => {
                                    // Only add dates that are today or in the future
                                    const dateObj = new Date(date);
                                    const today = new Date();
                                    today.setHours(0, 0, 0, 0);
                                    
                                    if (dateObj >= today) {
                                        window.allBookedDatesCache[room.id][date] = true;
                                        // Also add to union for multiple room selection
                                        window.unionBookedDatesCache[date] = true;
                                    }
                                });
                            }
                        } catch (error) {
                            console.error(`Error fetching booked dates for room ${room.id}:`, error);
                            // Continue with other rooms even if one fails
                            continue;
                        }
                    }

                    // Reset placeholders
                    if (checkInPicker) {
                        checkInPicker._input.placeholder = 'Select check-in date';
                    }
                    if (checkOutPicker) {
                        checkOutPicker._input.placeholder = 'Select check-out date';
                    }

                    // Re-initialize date pickers with new data
                    if (checkInPicker && checkOutPicker) {
                        const currentCheckIn = checkInPicker.selectedDates[0];
                        const currentCheckOut = checkOutPicker.selectedDates[0];

                        // Re-initialize pickers
                        await initializeDatePickers();

                        // Restore selected dates if they were set and still valid
                        if (currentCheckIn && currentCheckOut) {
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            
                            // Only restore dates if they're not in the past
                            if (currentCheckIn >= today) {
                                const hasConflict = checkForDateRangeConflict(currentCheckIn, currentCheckOut);
                                if (!hasConflict) {
                                    checkInPicker.setDate(currentCheckIn);
                                    checkOutPicker.setDate(currentCheckOut);
                                }
                            }
                        }
                    }

                    // Update booking summary
                    updateBookingSummary();
                } else {
                    // If no rooms selected, just initialize the date pickers
                    initializeDatePickers();
                }
            } catch (error) {
                console.error('Error updating booked dates:', error);
                // Reset placeholders on error
                if (checkInPicker) {
                    checkInPicker._input.placeholder = 'Select check-in date';
                }
                if (checkOutPicker) {
                    checkOutPicker._input.placeholder = 'Select check-out date';
                }
                // Initialize date pickers even if there's an error
                initializeDatePickers();
            }
        }

        // Room Selection Functions
        function toggleRoomSelection(element) {
            if (element.dataset.roomStatus === 'maintenance') {
                return;
            }

            const roomId = element.dataset.roomId;
            const roomName = element.dataset.roomName;
            const roomPrice = parseFloat(element.dataset.roomPrice);

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                selectedRooms.delete(roomId);
            } else {
                element.classList.add('selected');
                selectedRooms.set(roomId, {
                    id: roomId,
                    name: roomName,
                    price_per_night: roomPrice
                });
            }

            updateSelectedRoomsSummary();
            updateBookedDates(); // Update calendar when rooms selection changes
        }

        function updateSelectedRoomsSummary() {
            const list = document.getElementById('selectedRoomsList');
            const totalPriceElement = document.getElementById('totalPrice');
            const totalNightsElement = document.getElementById('totalNights');
            const actionButton = document.getElementById('actionButton');
            const sidebarTitle = document.querySelector('#selectedRoomsSummary h3');

            // Update sidebar title based on current step
            if (sidebarTitle) {
                sidebarTitle.textContent = currentStep === 1 ? 'Selected Rooms' : 'Booking Details';
            }

            // Calculate nights if dates are selected
            const checkInDate = document.getElementById('check_in_date')?.value;
            const checkOutDate = document.getElementById('check_out_date')?.value;
            let nights = 0;

            if (checkInDate && checkOutDate) {
                const start = new Date(checkInDate);
                const end = new Date(checkOutDate);
                nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                totalNightsElement.textContent = `${nights} night(s)`;
            }

            if (selectedRooms.size > 0) {
                let html = '';
                let total = 0;

                selectedRooms.forEach(room => {
                    const roomTotal = room.price_per_night * (nights || 1);
                    html += `
                        <div class="selected-room-card rounded-lg p-4 relative" style="border:none;box-shadow:none;">
                            <div class="pr-8" style="border:none;box-shadow:none;">
                                <h4 class="font-medium" style="border:none;box-shadow:none;">${room.name}</h4>
                                <div class="space-y-1 mt-2" style="border:none;box-shadow:none;">
                                    <p class="text-sm" style="border:none;box-shadow:none;">
                                        Rp ${room.price_per_night.toLocaleString('id-ID')}/night
                                    </p>
                                    ${nights ? `
                                        <p class="text-sm" style="border:none;box-shadow:none;">× ${nights} night(s)</p>
                                        <p class="text-orange-500 font-medium" style="border:none;box-shadow:none;">
                                            Rp ${roomTotal.toLocaleString('id-ID')}
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                            <button type="button" 
                                    onclick="removeRoom('${room.id}')" 
                                    class="absolute top-2 right-2 text-gray-400 hover:text-red-500 p-1" style="border:none;box-shadow:none;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="border:none;box-shadow:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `;
                    total += roomTotal;
                });

                list.innerHTML = html;
                totalPriceElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                actionButton.disabled = false;
            } else {
                list.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No rooms selected</p>';
                totalPriceElement.textContent = 'Rp 0';
                totalNightsElement.textContent = '0 night(s)';
                actionButton.disabled = true;
            }
        }

        function removeRoom(roomId) {
            const roomElement = document.querySelector(`[data-room-id="${roomId}"]`);
            if (roomElement) {
                roomElement.classList.remove('selected');
            }
            selectedRooms.delete(roomId);
            updateSelectedRoomsSummary();
        }

        // Room Filtering
        function filterRooms(type) {
            const rooms = document.querySelectorAll('.room-card');
            rooms.forEach(room => {
                if (type === 'all' || room.dataset.roomType === type) {
                    room.style.display = 'block';
                } else {
                    room.style.display = 'none';
                }
            });
        }

        // Step Navigation
        function goToStep(step) {
            if (step === 2 && selectedRooms.size === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Rooms Selected',
                    text: 'Please select at least one room before proceeding.',
                    confirmButtonColor: '#f97316'
                });
                return;
            }

            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(`step${step}`).classList.add('active');

            document.querySelectorAll('.step-indicator div:first-child').forEach((indicator, index) => {
                if (index + 1 <= step) {
                    indicator.classList.remove('bg-gray-300', 'text-gray-600');
                    indicator.classList.add('bg-orange-500', 'text-white');
                } else {
                    indicator.classList.remove('bg-orange-500', 'text-white');
                    indicator.classList.add('bg-gray-300', 'text-gray-600');
                }
            });

            currentStep = step;
            
            // Update action button text and sidebar
            const actionButton = document.getElementById('actionButton');
            if (actionButton) {
                actionButton.textContent = step === 1 ? 'Continue to Guest Details' : 'Create Booking';
            }
            
            // Update the sidebar display
            updateSelectedRoomsSummary();
        }

        // Add event listeners for date changes
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');

            if (checkInInput && checkOutInput) {
                // Update summary when dates change
                checkInInput.addEventListener('change', () => {
                    updateSelectedRoomsSummary();
                });
                
                checkOutInput.addEventListener('change', () => {
                    updateSelectedRoomsSummary();
                });
            }

            // Initialize the summary
            updateSelectedRoomsSummary();
        });

        // Add new function to handle form submission
        function submitBookingForm() {
            if (currentStep === 1) {
                goToStep(2);
            } else {
                // Get the form
                const form = document.getElementById('bookingForm');
                
                // Validate form
                if (selectedRooms.size === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Rooms Selected',
                        text: 'Please select at least one room before proceeding.',
                        confirmButtonColor: '#f97316'
                    });
                    return;
                }

                // Validate required fields
                const requiredFields = ['check_in_date', 'check_out_date', 'full_name', 'phone', 'id_number', 'payment_status'];
                const missingFields = requiredFields.filter(field => !document.getElementById(field).value);
                
                if (missingFields.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please fill in all required fields: ' + missingFields.join(', '),
                        confirmButtonColor: '#f97316'
                    });
                    return;
                }

                // Show confirmation dialog
                Swal.fire({
                    title: 'Confirm Booking',
                    text: 'Are you sure you want to create this booking?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, create booking',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Creating Booking',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Calculate total price and nights
                        const checkInDate = new Date(document.getElementById('check_in_date').value);
                        const checkOutDate = new Date(document.getElementById('check_out_date').value);
                        const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                        
                        let totalPrice = 0;
                        const roomsData = Array.from(selectedRooms.values()).map(room => {
                            const subtotal = room.price_per_night * nights;
                            totalPrice += subtotal;
                            return {
                                ...room,
                                nights: nights,
                                subtotal: subtotal
                            };
                        });

                        // Set the selected rooms data in the hidden input
                        document.getElementById('selected_rooms_data').value = JSON.stringify(roomsData);

                        // Submit the form
                        form.submit();
                    }
                });
            }
        }

        // Update Booking Summary
        function updateBookingSummary() {
            const summaryElement = document.getElementById('bookingSummary');
            if (!summaryElement) return;

    const checkInDate = document.getElementById('check_in_date').value;
    const checkOutDate = document.getElementById('check_out_date').value;
            let totalPrice = 0;
            let nights = 0;

            if (checkInDate && checkOutDate) {
                const start = new Date(checkInDate);
                const end = new Date(checkOutDate);
                nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            }

            let html = `
                <div class="flex justify-between text-sm">
                    <span>Check-in:</span>
                    <span class="font-medium">${checkInDate || '-'}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Check-out:</span>
                    <span class="font-medium">${checkOutDate || '-'}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Duration:</span>
                    <span class="font-medium">${nights} night(s)</span>
                </div>
                <div class="mt-2 pt-2 border-t">
                    <div class="font-semibold">Selected Rooms:</div>
            `;

            selectedRooms.forEach(room => {
                const roomTotal = room.price_per_night * nights;
                totalPrice += roomTotal;
                html += `
                    <div class="flex justify-between text-sm mt-1">
                        <span>${room.name}</span>
                        <span>Rp ${roomTotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            });

            html += `
                <div class="mt-2 pt-2 border-t flex justify-between font-bold">
                    <span>Total Amount:</span>
                    <span class="text-orange-600">Rp ${totalPrice.toLocaleString('id-ID')}</span>
                </div>
            `;

            summaryElement.innerHTML = html;
        }

        // Form Submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (selectedRooms.size === 0) {
                alert('Please select at least one room');
                return;
            }

            // Validate required fields
            const requiredFields = ['check_in_date', 'check_out_date', 'full_name', 'phone', 'id_number', 'payment_status'];
            const missingFields = requiredFields.filter(field => !document.getElementById(field).value);
            
            if (missingFields.length > 0) {
                alert('Please fill in all required fields: ' + missingFields.join(', '));
                return;
            }

            // Calculate total price and nights
            const checkInDate = new Date(document.getElementById('check_in_date').value);
            const checkOutDate = new Date(document.getElementById('check_out_date').value);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            
            let totalPrice = 0;
            const roomsData = Array.from(selectedRooms.values()).map(room => {
                const subtotal = room.price_per_night * nights;
                totalPrice += subtotal;
            return {
                    ...room,
                    nights: nights,
                    subtotal: subtotal
                };
            });

            // Set the selected rooms data in the hidden input
            document.getElementById('selected_rooms_data').value = JSON.stringify(roomsData);

            // Submit the form
            this.submit();
        });

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date pickers and update booked dates
            updateBookedDates();
            
            // Add click handlers for the input wrappers
            const checkInWrapper = document.querySelector('#check_in_date').closest('.date-input-wrapper');
            const checkOutWrapper = document.querySelector('#check_out_date').closest('.date-input-wrapper');

            if (checkInWrapper) {
                checkInWrapper.addEventListener('click', function(e) {
                    if (!e.target.classList.contains('flatpickr-input')) {
                        checkInPicker.open();
                    }
                });
            }

            if (checkOutWrapper) {
                checkOutWrapper.addEventListener('click', function(e) {
                    if (!e.target.classList.contains('flatpickr-input')) {
                        if (!checkInPicker.selectedDates[0]) {
                            checkInPicker.open();
                            // Show reminder
                            Swal.fire({
                                icon: 'info',
                                title: 'Pilih Tanggal Check-in',
                                text: 'Silakan pilih tanggal check-in terlebih dahulu',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            checkOutPicker.open();
                        }
                    }
                });
            }

            // Listen for booking status changes
            window.addEventListener('booking-status-changed', async function() {
                console.log('Booking status changed, updating calendar...');
                await updateBookedDates();
            });

            // Listen for calendar refresh events
            window.addEventListener('refresh-calendar', async function() {
                console.log('Calendar refresh requested');
                await updateBookedDates();
            });
        });

function validateForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('bookingForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;

    // Reset previous validation states
    form.querySelectorAll('.form-input').forEach(input => {
        input.classList.remove('error');
        const errorMessage = input.nextElementSibling;
        if (errorMessage && errorMessage.classList.contains('error-message')) {
            errorMessage.classList.remove('visible');
        }
    });
        
        // Validate required fields
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('error');
            const errorMessage = field.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.classList.add('visible');
            }
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        }
    });

    // Validate phone number format
    const phoneField = document.getElementById('phone');
    if (phoneField.value.trim() && !/^[0-9]{10,13}$/.test(phoneField.value.trim())) {
        isValid = false;
        phoneField.classList.add('error');
        const errorMessage = phoneField.nextElementSibling;
        if (errorMessage) {
            errorMessage.textContent = 'Please enter a valid phone number (10-13 digits)';
            errorMessage.classList.add('visible');
        }
        if (!firstInvalidField) {
            firstInvalidField = phoneField;
        }
    }

    // Validate ID number format
    const idField = document.getElementById('id_number');
    if (idField.value.trim().length !== 16) {
        isValid = false;
        idField.classList.add('error');
        const errorMessage = idField.nextElementSibling;
        if (errorMessage) {
            errorMessage.textContent = 'ID Number must be exactly 16 digits';
            errorMessage.classList.add('visible');
        }
        if (!firstInvalidField) {
            firstInvalidField = idField;
        }
    }

    // Validate selected rooms
    const selectedRooms = Array.from(window.selectedRooms.values());
    if (selectedRooms.length === 0) {
        isValid = false;
                        Swal.fire({
                            icon: 'error',
            title: 'No Rooms Selected',
            text: 'Please select at least one room before creating the booking.',
                            confirmButtonColor: '#f97316'
                        });
        return false;
    }

    // Update selected rooms data
    document.getElementById('selected_rooms_data').value = JSON.stringify(selectedRooms);

    if (!isValid) {
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
        return false;
    }

    // Show confirmation dialog
    Swal.fire({
        title: 'Confirm Booking',
        text: 'Are you sure you want to create this booking?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, create booking',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}

// Add input event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const inputs = form.querySelectorAll('.form-input');
    const idField = document.getElementById('id_number');
    const idError = idField.nextElementSibling;

    // Only allow numbers and max 16 digits
    idField.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
        if (this.value.length !== 16) {
            idField.classList.add('error');
            if (idError) {
                idError.textContent = 'ID Number must be exactly 16 digits';
                idError.classList.add('visible');
            }
        } else {
            idField.classList.remove('error');
            if (idError) idError.classList.remove('visible');
        }
    });
    // Prevent non-numeric keydown
    idField.addEventListener('keydown', function(e) {
        if (
            // Allow: backspace, delete, tab, escape, enter, arrows
            [46, 8, 9, 27, 13, 37, 38, 39, 40].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl/cmd+A, Ctrl/cmd+C, Ctrl/cmd+V, Ctrl/cmd+X
            (e.ctrlKey === true || e.metaKey === true) && [65, 67, 86, 88].indexOf(e.keyCode) !== -1
        ) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                const errorMessage = this.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('error-message')) {
                    errorMessage.classList.remove('visible');
                }
            }
        });
    });
});

// Room Type Filter Selection
function selectRoomTypeFilter(btn, type) {
    document.querySelectorAll('.room-type-filter-btn').forEach(el => el.classList.remove('selected'));
    btn.classList.add('selected');
    filterRooms(type);
}

// Animate select arrow on Payment Status
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('payment_status');
    const arrow = document.querySelector('.select-arrow');
    if (select && arrow) {
        select.addEventListener('focus', function() {
            arrow.style.transform = 'translateY(-50%) rotate(180deg)';
        });
        select.addEventListener('blur', function() {
            arrow.style.transform = 'translateY(-50%) rotate(0deg)';
        });
    }
});

// Add this new function to handle form submission response
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(form);
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Created!',
                    text: 'The booking has been successfully created.',
                    confirmButtonColor: '#f97316'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.redirect || '/receptionist/bookings';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred while creating the booking.',
                    confirmButtonColor: '#f97316'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred. Please try again.',
                confirmButtonColor: '#f97316'
            });
        });
    });
});
</script>
@endpush
</x-receptionist-layout> 