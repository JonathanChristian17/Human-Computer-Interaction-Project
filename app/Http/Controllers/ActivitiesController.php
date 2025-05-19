<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()
    {
        $activities = [
            [
                'name' => 'Pantai Pasir Putih',
                'description' => 'Nikmati keindahan pantai berpasir putih dengan pemandangan Danau Toba yang memukau. Cocok untuk bersantai dan berenang.',
                'distance' => '10 menit',
                'image' => 'https://source.unsplash.com/random/800x600/?white-sand-beach'
            ],
            [
                'name' => 'Pemandian Air Panas Aek Rangat',
                'description' => 'Pemandian air panas alami dengan kandungan mineral yang baik untuk kesehatan. Sempurna untuk relaksasi.',
                'distance' => '15 menit',
                'image' => 'https://source.unsplash.com/random/800x600/?hot-spring'
            ],
            [
                'name' => 'Bukit Pahoda',
                'description' => 'Spot terbaik untuk melihat matahari terbit dan terbenam dengan pemandangan Danau Toba yang spektakuler.',
                'distance' => '20 menit',
                'image' => 'https://source.unsplash.com/random/800x600/?hill-viewpoint'
            ],
            [
                'name' => 'Museum Huta Bolon',
                'description' => 'Pelajari sejarah dan budaya Batak melalui koleksi artefak dan rumah adat tradisional.',
                'distance' => '25 menit',
                'image' => 'https://source.unsplash.com/random/800x600/?traditional-museum'
            ],
            [
                'name' => 'Water Sports',
                'description' => 'Berbagai aktivitas air seperti jet ski, banana boat, dan kayak tersedia untuk pengalaman seru di Danau Toba.',
                'distance' => '5 menit',
                'image' => 'https://source.unsplash.com/random/800x600/?water-sports'
            ],
            [
                'name' => 'Pasar Tradisional Pangururan',
                'description' => 'Kunjungi pasar tradisional untuk merasakan suasana lokal dan membeli oleh-oleh khas Samosir.',
                'distance' => '10 menit',
                'image' => 'https://source.unsplash.com/random/800x600/?traditional-market'
            ]
        ];

        return view('activities.index', compact('activities'));
    }
} 