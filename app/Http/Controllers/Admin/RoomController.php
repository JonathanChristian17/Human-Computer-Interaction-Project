<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            $validated['image'] = $imageName;
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

            // Log activity
            Activity::log(
                Auth::id(),
                'Created new room',
                "Room {$room->room_number} ({$room->name}) has been created",
                'room_create',
                $room
            );

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

        $oldRoom = $room->replicate();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image) {
                $oldImagePath = public_path('storage/images/' . $room->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            $validated['image'] = $imageName;
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

        // Log activity with changes
        $changes = [];
        foreach ($updateData as $key => $value) {
            if ($oldRoom->$key != $value) {
                $changes[] = "$key: {$oldRoom->$key} → $value";
            }
        }

        if (!empty($changes)) {
            Activity::log(
                Auth::id(),
                'Updated room',
                "Room {$room->room_number} updated. Changes: " . implode(', ', $changes),
                'room_update',
                $room
            );
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil diperbarui! Nomor kamar: ' . $room->room_number);
    }

    public function destroy(Room $room)
    {
        $roomNumber = $room->room_number;
        
        if ($room->image) {
            $oldImagePath = public_path('storage/images/' . $room->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        $room->delete();

        // Log activity
        Activity::log(
            Auth::id(),
            'Deleted room',
            "Room {$roomNumber} has been deleted",
            'room_delete'
        );

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus!');
    }
}