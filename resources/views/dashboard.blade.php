@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border border-gray-700/50">
        <div class="p-6 sm:p-8">
            <h2 class="text-3xl font-extrabold text-white mb-8">Dashboard</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Welcome Message --}}
                <div class="bg-gray-700/60 p-6 rounded-xl border border-gray-600/50 hover:shadow-lg transition">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-amber-400 mr-3" fill="none" stroke="currentColor" stroke-width="2" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="text-xl font-semibold text-white">Welcome</h3>
                    </div>
                    <p class="text-gray-300">Halo, <span class="font-medium text-amber-400" data-user-name>{{ Auth::user()->name }}</span>! Anda telah berhasil login.</p>
                </div>

                {{-- Email --}}
                <div class="bg-gray-700/60 p-6 rounded-xl border border-gray-600/50 hover:shadow-lg transition">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-amber-400 mr-3" fill="none" stroke="currentColor" stroke-width="2" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 0l-4-4m4 4l-4 4" />
                        </svg>
                        <h3 class="text-xl font-semibold text-white">Email Anda</h3>
                    </div>
                    <p class="text-gray-300">{{ Auth::user()->email }}</p>
                </div>

                {{-- Created At --}}
                <div class="bg-gray-700/60 p-6 rounded-xl border border-gray-600/50 hover:shadow-lg transition">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-amber-400 mr-3" fill="none" stroke="currentColor" stroke-width="2" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 9h10m2 0a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5" />
                        </svg>
                        <h3 class="text-xl font-semibold text-white">Akun Dibuat</h3>
                    </div>
                    <p class="text-gray-300">{{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
            </div>
            
            {{-- Edit Profile Button --}}
            <div class="mt-10 pt-6 border-t border-gray-700/50 text-right">
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 border border-transparent rounded-xl font-semibold text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 transition">
                   <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round" 
                           d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-4.586-9.414a2 2 0 112.828 2.828L11 19H6v-5l9.414-9.414z" />
                   </svg>
                   Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
