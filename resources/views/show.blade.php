@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl mb-4">Detail Kamar</h1>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold">{{ $room->name }}</h2>
        <p class="my-2">{{ $room->description }}</p>
        <p><strong>Harga:</strong> Rp{{ number_format($room->price, 0, ',', '.') }}</p>
        <p><strong>Kapasitas:</strong> {{ $room->capacity }} orang</p>
    </div>

    <a href="{{ route('rooms.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">Kembali ke Daftar Kamar</a>
</div>
@endsection
