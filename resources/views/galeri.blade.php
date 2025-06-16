@extends('layouts.app')

@section('content')
<div class="gallery-page min-h-screen bg-pattern flex flex-col relative overflow-hidden" x-data="{ featured: 1, hover: false, animKey: 0 }">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-[#1a1a1a]/80 to-transparent z-0"></div>
    <div class="absolute top-0 right-0 w-1/3 h-screen bg-gradient-to-l from-[#c8a164]/20 to-transparent z-0"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-screen bg-gradient-to-t from-[#c8a164]/20 to-transparent z-0"></div>
    
    <!-- Luxury Patterns -->
    <div class="absolute inset-0 opacity-10 pattern-luxury"></div>
    
    <!-- Hero Section with entrance animations -->
    <div class="w-full flex flex-col items-center justify-center mt-24 mb-16 px-4 relative z-10">
        <div class="relative mb-2 animate-fade-in-down">
            <!-- Decorative lines with slide animations -->
            <div class="absolute -left-20 top-1/2 w-16 h-[2px] bg-gradient-to-r from-[#c8a164] to-transparent animate-slide-in-left"></div>
            <div class="absolute -right-20 top-1/2 w-16 h-[2px] bg-gradient-to-l from-[#c8a164] to-transparent animate-slide-in-right"></div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-center luxury-text pb-4 animate-title">
                Cahaya Resort
            </h1>
            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-48 h-1 bg-gradient-to-r from-transparent via-[#c8a164] to-transparent animate-fade-in"></div>
        </div>
        <div class="text-xl md:text-2xl text-[#c8a164] text-center font-light mt-8 leading-relaxed tracking-wider animate-fade-in-up">
            <span class="px-8 py-2 border-t border-b border-[#c8a164]/30">
                UNWIND BY THE WATER. A LAKESIDE ESCAPE CRAFTED<br>
                FOR TIMELESS MOMENTS.
            </span>
        </div>
    </div>

    <div class="flex flex-1 flex-col lg:flex-row gap-12 px-8 lg:px-16 pb-16 relative z-10">
        <!-- LEFT: Grid Gallery with stagger animation -->
        <div class="w-full lg:w-1/2 flex flex-col animate-fade-in-left">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 flex-1">
                @for($i=1; $i<=16; $i++)
                <div class="relative group aspect-square overflow-hidden rounded-xl transform transition-all duration-500 hover:scale-105 luxury-card animate-fade-in-up"
                    style="animation-delay: {{$i * 0.1}}s"
                    @mouseenter="featured = {{$i}}; hover = true; animKey++;"
                    @mouseleave="hover = false; animKey++">
                    <span class="absolute top-4 left-4 z-10 font-semibold text-lg bg-black/50 text-[#c8a164] w-10 h-10 flex items-center justify-center rounded-full border border-[#c8a164]/30 backdrop-blur-sm">{{$i}}</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <img src="{{ asset('storage/images/gallery-' . (($i-1)%7+1) . '.jpg') }}"
                         alt="Gallery {{$i}}"
                         class="w-full h-full object-cover transition duration-700 group-hover:scale-110"/>
                    <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="text-[#c8a164] text-sm font-light">Pangururan</div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- RIGHT: Featured Area with entrance animation -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center relative luxury-featured-area animate-fade-in-right">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm rounded-3xl"></div>
            <div class="absolute inset-0 pattern-overlay rounded-3xl opacity-5"></div>
            
            <div class="flex-1 flex flex-col justify-center items-center w-full h-full px-8 py-12 relative z-10">
                <div class="w-full max-w-2xl aspect-video mx-auto mb-12 relative overflow-hidden rounded-2xl luxury-frame animate-fade-in-up" style="animation-delay: 0.5s">
                    <template x-if="hover">
                        <img :key="animKey" :src="'/storage/images/gallery-' + ((featured-1)%7+1) + '.jpg'"
                             alt="Featured"
                             class="w-full h-full object-cover opacity-90 transition duration-700 featured-clip"
                             x-init="$el.classList.remove('clip-anim'); void $el.offsetWidth; $el.classList.add('clip-anim')"
                        />
                    </template>
                    <template x-if="!hover">
                        <img :key="'default-'+animKey" src="{{ asset('storage/images/gallery-1.jpg') }}" alt="Featured" 
                             class="w-full h-full object-cover opacity-90 transition duration-700 featured-clip"
                             x-init="$el.classList.remove('clip-anim'); void $el.offsetWidth; $el.classList.add('clip-anim')"
                        />
                    </template>
                </div>
                <div class="flex items-center justify-center w-full mt-10 animate-fade-in-up" style="animation-delay: 0.7s">
                    <div class="flex items-center space-x-12">
                        <span class="text-4xl md:text-5xl font-light tracking-widest text-[#c8a164]">2024</span>
                        <div class="h-[2px] w-32 bg-gradient-to-r from-[#c8a164] via-[#dfc298] to-[#c8a164]"></div>
                        <span class="text-4xl md:text-5xl font-light tracking-widest text-[#c8a164]">2025</span>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-6 right-8 text-sm text-[#c8a164]/80 font-light tracking-wider animate-fade-in" style="animation-delay: 1s">© 2025 Cahaya Resort</div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Styles specific to gallery page */
    .gallery-page {
        background: #1a1a1a;
        min-height: 100vh;
        color: #c8a164;
    }
    
    .gallery-page .bg-pattern {
        background-color: #1a1a1a;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c8a164' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .gallery-page .pattern-luxury {
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23c8a164' fill-opacity='0.1'%3E%3Cpath d='M50 0l50 50-50 50L0 50z'/%3E%3C/g%3E%3C/svg%3E");
    }

    .gallery-page .pattern-overlay {
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23c8a164' fill-opacity='0.15'%3E%3Cpath d='M0 0h40v40H0V0zm20 20h20v20H20V20zM0 20h20v20H0V20z'/%3E%3C/g%3E%3C/svg%3E");
    }
    
    .gallery-page .luxury-text {
        background: linear-gradient(to right, #c8a164, #dfc298, #c8a164);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .gallery-page .luxury-card {
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.3);
        border: 1px solid rgba(200, 161, 100, 0.1);
    }

    .gallery-page .luxury-frame {
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        border: 1px solid rgba(200, 161, 100, 0.2);
    }

    .gallery-page .luxury-featured-area {
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        border: 1px solid rgba(200, 161, 100, 0.1);
        border-radius: 1.5rem;
    }
    
    .gallery-page .aspect-square { aspect-ratio: 1/1; }
    .gallery-page .aspect-video { aspect-ratio: 16/9; }
    
    .gallery-page .featured-clip {
        clip-path: polygon(50% 0%, 100% 0, 100% 50%, 100% 100%, 50% 100%, 0 100%, 0 50%, 0 0);
        transition: all 0.8s cubic-bezier(.77,0,.18,1);
    }
    
    .gallery-page .clip-anim {
        clip-path: polygon(50% 0%, 50% 0%, 50% 100%, 50% 100%, 50% 100%, 50% 100%, 50% 0%, 50% 0%);
        animation: clipOpen 0.8s cubic-bezier(.77,0,.18,1) forwards;
    }
    
    @keyframes clipOpen {
        0% {
            clip-path: polygon(50% 0%, 50% 0%, 50% 100%, 50% 100%, 50% 100%, 50% 100%, 50% 0%, 50% 0%);
        }
        100% {
            clip-path: polygon(50% 0%, 100% 0, 100% 50%, 100% 100%, 50% 100%, 0 100%, 0 50%, 0 0);
        }
    }

    /* Custom scrollbar only for gallery page */
    .gallery-page::-webkit-scrollbar {
        width: 8px;
        background: #1a1a1a;
    }

    .gallery-page::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #c8a164, #dfc298);
        border-radius: 4px;
    }

    .gallery-page .grid img {
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (hover: hover) {
        .gallery-page .grid .group:hover img {
            transform: scale(1.1);
        }
    }

    /* New Animation Keyframes */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes titleAnimation {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }
        50% {
            opacity: 0.5;
            transform: scale(1.1);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Animation Classes */
    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-up {
        animation: fadeInUp 1s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-down {
        animation: fadeInDown 1s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-left {
        animation: fadeInLeft 1s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-right {
        animation: fadeInRight 1s ease-out forwards;
        opacity: 0;
    }

    .animate-slide-in-left {
        animation: slideInLeft 1.2s ease-out forwards;
        opacity: 0;
    }

    .animate-slide-in-right {
        animation: slideInRight 1.2s ease-out forwards;
        opacity: 0;
    }

    .animate-title {
        animation: titleAnimation 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }

    /* Make sure all animated elements start invisible */
    [class*="animate-"] {
        will-change: transform, opacity;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

@endsection 