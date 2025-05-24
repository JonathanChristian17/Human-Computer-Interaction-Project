<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', 'available')
                    ->select('id', 'name', 'price_per_night', 'capacity', 'image')
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

        return view('landingpage', compact('rooms'));
    }
} 