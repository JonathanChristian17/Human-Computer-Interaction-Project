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
            <label class="block font-semibold mb-1">Nama Kamar</label>
            <input type="text" name="name" class="border p-2 w-full" value="{{ old('name', $room->name) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Deskripsi</label>
            <textarea name="description" class="border p-2 w-full">{{ old('description', $room->description) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Harga</label>
            <input type="number" name="price" class="border p-2 w-full" value="{{ old('price', $room->price) }}" required>
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
