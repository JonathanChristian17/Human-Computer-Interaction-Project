@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="flex flex-col justify-center items-center text-center px-6 md:px-12 py-20">
        <h1 class="text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg leading-tight max-w-4xl">
            Selamat Datang di <br />
            <span class="text-yellow-300">Penginapan Cahaya</span>
        </h1>
        <p class="mt-6 max-w-3xl text-white text-xl md:text-2xl drop-shadow-md leading-relaxed">
            Tempat nyaman untuk istirahat Anda dengan pelayanan terbaik dan harga terjangkau.
        </p>
        <a href="#rooms" class="mt-10 bg-yellow-400 text-indigo-900 font-semibold px-10 py-3 rounded-lg shadow-lg hover:bg-yellow-300 transition">
            Lihat Kamar Kami
        </a>
    </section>

    <!-- Rooms, About, Contact seperti sebelumnya -->
@endsection
