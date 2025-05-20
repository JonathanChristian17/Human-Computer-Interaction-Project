@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl mb-4">Edit Kamar</h1>

    @if ($errors->any())
        <div class="bg-red-200 p-3 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nomor Kamar</label>
            <input type="text" name="room_number" class="border p-2 w-full" value="{{ old('room_number', $room->room_number) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nama Kamar</label>
            <input type="text" name="name" class="border p-2 w-full" value="{{ old('name', $room->name) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tipe Kamar</label>
            <select name="type" class="border p-2 w-full" required>
                <option value="standard" {{ old('type', $room->type) == 'standard' ? 'selected' : '' }}>Standard</option>
                <option value="deluxe" {{ old('type', $room->type) == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                <option value="suite" {{ old('type', $room->type) == 'suite' ? 'selected' : '' }}>Suite</option>
                <option value="family" {{ old('type', $room->type) == 'family' ? 'selected' : '' }}>Family</option>
                <option value="luxury" {{ old('type', $room->type) == 'luxury' ? 'selected' : '' }}>Luxury</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Deskripsi</label>
            <textarea name="description" class="border p-2 w-full" required>{{ old('description', $room->description) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Harga per Malam</label>
            <input type="number" name="price_per_night" class="border p-2 w-full" value="{{ old('price_per_night', $room->price_per_night) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Kapasitas</label>
            <input type="number" name="capacity" class="border p-2 w-full" value="{{ old('capacity', $room->capacity) }}" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('rooms.index') }}" class="ml-4">Batal</a>
    </form>
</div>
@endsection 