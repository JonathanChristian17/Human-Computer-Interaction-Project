<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        // Dump request data for debugging
        \Log::info('Room creation request data:', $request->all());

        $validated = $request->validate([
            'room_number' => 'required|unique:rooms,room_number',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'description' => 'required',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:available,maintenance'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_available'] = $validated['status'] === 'available';

        // Log validated data before creation
        \Log::info('Validated data for room creation:', $validated);

        try {
            // Ensure price is treated as integer
            $price = (int) str_replace('.', '', $request->price_per_night);
            
            $room = Room::create([
                'room_number' => $validated['room_number'],
                'name' => $validated['name'],
                'type' => $validated['type'],
                'description' => $validated['description'],
                'price_per_night' => $price,
                'capacity' => $validated['capacity'],
                'image' => $validated['image'],
                'status' => $validated['status'],
                'is_available' => $validated['is_available']
            ]);

            \Log::info('Room created successfully:', ['room_id' => $room->id]);

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Kamar berhasil ditambahkan! Nomor kamar: ' . $room->room_number);
        } catch (\Exception $e) {
            \Log::error('Error creating room:', [
                'error' => $e->getMessage(),
                'validated_data' => $validated
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Gagal menambahkan kamar. Silakan coba lagi.']);
        }
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'number' => 'required|unique:rooms,room_number,' . $room->id,
            'name' => 'required|string|max:255',
            'type' => 'required',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $imagePath;
        }

        // Ensure price is treated as integer
        $price = (int) str_replace('.', '', $request->price_per_night);

        // Map number to room_number and include name
        $updateData = [
            'room_number' => $validated['number'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'price_per_night' => $price,
            'capacity' => $validated['capacity']
        ];

        if (isset($validated['image'])) {
            $updateData['image'] = $validated['image'];
        }

        $room->update($updateData);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil diperbarui! Nomor kamar: ' . $room->room_number);
    }

    public function destroy(Room $room)
    {
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }
        
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus!');
    }
}