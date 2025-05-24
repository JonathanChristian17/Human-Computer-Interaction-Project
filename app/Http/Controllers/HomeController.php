<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('landingpage', compact('rooms'));
    }

    public function dashboard()
    {
        return redirect()->route('landing');
    }
} 