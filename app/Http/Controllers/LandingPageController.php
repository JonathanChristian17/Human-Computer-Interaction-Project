<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get available room types
        $roomTypes = Room::select('type')
            ->distinct()
            ->pluck('type')
            ->toArray();

        $rooms = Room::select('id', 'name', 'price_per_night', 'capacity', 'image', 'type', 'status')
                    ->get()
                    ->map(function($room) {
                        // Jika image kosong atau null, gunakan default image
                        if (empty($room->image)) {
                            $room->image = 'room-' . $room->id . '.jpg';
                        }
                        // Pastikan tidak ada 'storage/images/' di path gambar
                        $room->image = str_replace('storage/images/', '', $room->image);
                        return $room;
                    });

        return view('landingpage', compact('rooms', 'roomTypes'));
    }
} 