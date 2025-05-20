@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Aktivitas & Tempat Wisata</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Jelajahi berbagai tempat menarik di sekitar Penginapan Cahaya, Pangururan, Samosir. Semua destinasi dapat dijangkau dengan mudah dari hotel.</p>
            <div class="w-20 h-1 bg-amber-400 mx-auto mt-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($activities as $activity)
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-xl transform hover:-translate-y-2 transition-all duration-300">
                <div class="relative h-64">
                    <img src="{{ $activity['image'] }}" alt="{{ $activity['name'] }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-4 right-4">
                        <span class="bg-amber-400/90 text-gray-900 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $activity['distance'] }} dari hotel
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-2">{{ $activity['name'] }}</h3>
                    <p class="text-gray-400">{{ $activity['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Call to Action -->
        <div class="mt-16 text-center">
            <p class="text-gray-400 mb-6">Butuh bantuan merencanakan kunjungan ke tempat-tempat wisata?</p>
            <a href="#" class="inline-flex items-center px-6 py-3 bg-amber-400 text-gray-900 rounded-lg font-semibold hover:bg-amber-300 transition-colors duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Hubungi Resepsionis
            </a>
        </div>
    </div>
</div>
@endsection 