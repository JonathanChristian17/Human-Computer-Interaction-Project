@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl mb-4">Daftar Kamar</h1>

    @if(session('success'))
        <div class="bg-green-200 p-2 mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('rooms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Kamar Baru</a>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">Nama</th>
                    <th class="border border-gray-300 p-2">Deskripsi</th>
                    <th class="border border-gray-300 p-2">Harga</th>
                    <th class="border border-gray-300 p-2">Kapasitas</th>
                    <th class="border border-gray-300 p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $room->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $room->description }}</td>
                    <td class="border border-gray-300 p-2">Rp{{ number_format($room->price, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 p-2">{{ $room->capacity }}</td>
                    <td class="border border-gray-300 p-2 space-x-2">
                        <a href="{{ route('rooms.show', $room->id) }}" class="text-blue-600 hover:underline">Detail</a>
                        <a href="{{ route('rooms.edit', $room->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
