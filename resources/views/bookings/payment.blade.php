@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('bookings.riwayat') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Booking Form
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Payment Details</h2>

            <!-- Error Message Display -->
            <div id="payment-error" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"></div>

            <!-- Payment Form -->
            <form id="payment-form" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <!-- Booking Summary -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-lg mb-4">Booking Summary</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Room(s):</span> {{ $booking->rooms->pluck('name')->implode(', ') }}</p>
                        <p><span class="font-medium">Check-in:</span> {{ $booking->check_in_date->format('d M Y') }}</p>
                        <p><span class="font-medium">Check-out:</span> {{ $booking->check_out_date->format('d M Y') }}</p>
                        <p><span class="font-medium">Guest:</span> {{ $booking->full_name }}</p>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Virtual Account Options -->
                        <label class="relative border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="bank_transfer" class="sr-only" required>
                            <div class="flex items-center">
                                <img src="{{ asset('images/bank_transfer.png') }}" alt="Bank Transfer" class="h-8 w-auto">
                                <span class="ml-3">Bank Transfer</span>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent rounded-lg pointer-events-none"></div>
                        </label>

                        <!-- E-Wallet Options -->
                        <label class="relative border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="gopay" class="sr-only">
                            <div class="flex items-center">
                                <img src="{{ asset('images/gopay.png') }}" alt="GoPay" class="h-8 w-auto">
                                <span class="ml-3">GoPay</span>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent rounded-lg pointer-events-none"></div>
                        </label>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border-t pt-4 mt-6">
                    <h4 class="font-semibold mb-4">Payment Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($booking->total_price - $booking->tax - $booking->deposit, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span>Rp {{ number_format($booking->tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Security Deposit</span>
                            <span>Rp {{ number_format($booking->deposit, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold pt-2 border-t">
                            <span>Total</span>
                            <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Include Midtrans Snap library -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<!-- Include payment form handling -->
@vite(['resources/js/payment-form.js'])
@endpush
@endsection