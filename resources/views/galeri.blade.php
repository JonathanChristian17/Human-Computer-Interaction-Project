@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#d1d1d1] flex flex-col relative" x-data="{ featured: 1, hover: false, animKey: 0 }">
    <!-- Centered Title & Description -->
    <div class="w-full flex flex-col items-center justify-center mt-20 mb-10">
        <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 mb-2 text-center drop-shadow-sm section-title" style="border-bottom: 6px solid #FFA040;">Cahaya Resort</h1>
        <div class="text-xl md:text-2xl text-gray-700 text-center font-light">UNWIND BY THE WATER. A LAKESIDE ESCAPE CRAFTED<br>FOR TIMELESS MOMENTS.</div>
    </div>
    <div class="flex flex-1 flex-col lg:flex-row">
        <!-- LEFT: GRID -->
        <div class="w-full lg:w-1/2 px-6 py-10 flex flex-col">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 flex-1">
                @for($i=1; $i<=16; $i++)
                <div class="relative group aspect-square overflow-hidden bg-white border border-[#E0E0E0]"
                    @mouseenter="featured = {{$i}}; hover = true; animKey++;"
                    @mouseleave="hover = false; animKey++">
                    <span class="absolute top-2 left-2 z-10 font-semibold text-lg text-[#40BFFF]">{{$i}}</span>
                    <img src="{{ asset('storage/images/gallery-' . (($i-1)%7+1) . '.jpg') }}"
                         alt="Gallery {{$i}}"
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-300"/>
                </div>
                @endfor
            </div>
        </div>
        <!-- RIGHT: FEATURED -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-[#c4c4c4] relative featured-area rounded-3xl">
            <div class="flex-1 flex flex-col justify-center items-center w-full h-full px-6 py-10">
                <div class="w-full max-w-2xl aspect-video mx-auto mb-8 relative overflow-hidden">
                    <template x-if="hover">
                        <img :key="animKey" :src="'/storage/images/gallery-' + ((featured-1)%7+1) + '.jpg'"
                             alt="Featured"
                             class="w-full h-full object-cover rounded-none opacity-80 transition duration-300 featured-clip"
                             x-init="$el.classList.remove('clip-anim'); void $el.offsetWidth; $el.classList.add('clip-anim')"
                        />
                    </template>
                    <template x-if="!hover">
                        <img :key="'default-'+animKey" src="{{ asset('storage/images/gallery-1.jpg') }}" alt="Featured" class="w-full h-full object-cover rounded-none opacity-80 transition duration-300 featured-clip"
                             x-init="$el.classList.remove('clip-anim'); void $el.offsetWidth; $el.classList.add('clip-anim')"
                        />
                    </template>
                </div>
                <div class="flex items-center justify-center w-full mt-8">
                    <span class="text-3xl md:text-4xl font-bold tracking-widest mr-6 text-white">2024</span>
                    <span class="block h-1 w-40 bg-black mx-4"></span>
                    <span class="text-3xl md:text-4xl font-bold tracking-widest ml-6 text-white">2025</span>
                </div>
            </div>
            <div class="absolute bottom-4 right-8 text-xs text-gray-500">© 2025 Cahaya Resort</div>
        </div>
    </div>
</div>

@push('styles')
<style>
    body { background:#d1d1d1; }
    .aspect-square { aspect-ratio: 1/1; }
    .aspect-video { aspect-ratio: 16/9; }
    img, .rounded-none { border-radius: 0 !important; box-shadow: none !important; }
    ::-webkit-scrollbar { width: 0; background: transparent; }
    .featured-clip {
        clip-path: polygon(50% 0%, 100% 0, 100% 50%, 100% 100%, 50% 100%, 0 100%, 0 50%, 0 0);
        transition: clip-path 0.8s cubic-bezier(.77,0,.18,1);
    }
    .clip-anim {
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
    .featured-area {
        background:#c4c4c4 !important;
        border-radius: 2rem !important;
    }
    .section-title {
        border-bottom: 6px solid #FFA040 !important;
        display: inline-block;
        padding-bottom: 0.2em;
    }
    .button-main {
        background: linear-gradient(90deg, #FFA040 0%, #40BFFF 100%) !important;
        color: #FFF !important;
        border: none !important;
    }
    .button-main:hover {
        background: linear-gradient(90deg, #40BFFF 0%, #FFA040 100%) !important;
        color: #FFF !important;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection 