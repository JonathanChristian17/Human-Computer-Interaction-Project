@extends('layouts.app')

@section('content')
<!-- Hero Section with Curved Bottom -->
<div class="relative bg-gray-900 h-[500px] overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('storage/images/hero-gallery.jpg') }}" alt="Gallery Hero" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative h-full flex items-center justify-center text-center px-4">
        <div class="max-w-4xl">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">Gallery Cahaya Resort</h1>
            <p class="text-xl text-gray-200">see a few picture of Cahaya Resort</p>
        </div>
    </div>

    <!-- Curved Bottom -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>

<!-- Gallery Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Masonry Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Large Image 1 -->
        <div class="col-span-1 md:col-span-2 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-1.jpg') }}" alt="Gallery Image" class="w-full h-[400px] object-cover">
        </div>

        <!-- Regular Image 1 -->
        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-2.jpg') }}" alt="Gallery Image" class="w-full h-[300px] object-cover">
        </div>

        <!-- Regular Image 2 -->
        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-3.jpg') }}" alt="Gallery Image" class="w-full h-[300px] object-cover">
        </div>

        <!-- Large Image 2 -->
        <div class="col-span-1 md:col-span-2 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-4.jpg') }}" alt="Gallery Image" class="w-full h-[400px] object-cover">
        </div>

        <!-- Regular Image 3 -->
        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-5.jpg') }}" alt="Gallery Image" class="w-full h-[300px] object-cover">
        </div>

        <!-- Regular Image 4 -->
        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-6.jpg') }}" alt="Gallery Image" class="w-full h-[300px] object-cover">
        </div>

        <!-- Large Image 3 -->
        <div class="col-span-1 lg:col-span-3 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
            <img src="{{ asset('storage/images/gallery-7.jpg') }}" alt="Gallery Image" class="w-full h-[500px] object-cover">
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Experience Cahaya Resort?</h2>
            <p class="text-lg text-gray-600 mb-8">Book your stay now and create unforgettable memories</p>
            <button onclick="showRooms()" class="bg-orange-500 text-white px-8 py-3 rounded-lg hover:bg-orange-600 transition duration-300 font-semibold">
                Book Now
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Add smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Add hover effect for gallery images */
    .gallery-item {
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.02);
    }
</style>
@endpush
@endsection 