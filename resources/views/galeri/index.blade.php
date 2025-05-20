@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('content')
<div class="py-16 bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">What's in Samosir</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Temukan keindahan dan keunikan Samosir melalui berbagai destinasi wisata yang menakjubkan. Mulai dari pantai, air panas, hingga situs budaya.</p>
            <div class="w-20 h-1 bg-amber-400 mx-auto mt-6"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <!-- Map Container -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-xl h-[600px]">
                <div id="map" class="w-full h-full"></div>
            </div>

            <!-- Tourist Spots List -->
            <div class="space-y-6">
                <div class="bg-gray-800 rounded-xl p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Destinasi Wisata Populer</h3>
                    <div class="space-y-4" id="tourist-spots">
                        <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition cursor-pointer" 
                             onclick="showRoute(2.6505, 98.9273, 'Pantai Pasir Putih')">
                            <h4 class="text-lg font-semibold text-white mb-2">Pantai Pasir Putih</h4>
                            <p class="text-gray-400 text-sm mb-2">Nikmati keindahan pantai berpasir putih dengan pemandangan Danau Toba yang memukau.</p>
                            <div class="flex items-center text-amber-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>10 menit dari hotel</span>
                            </div>
                        </div>

                        <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition cursor-pointer"
                             onclick="showRoute(2.6584, 98.9162, 'Aek Rangat')">
                            <h4 class="text-lg font-semibold text-white mb-2">Pemandian Air Panas Aek Rangat</h4>
                            <p class="text-gray-400 text-sm mb-2">Relaksasi di pemandian air panas alami dengan kandungan mineral yang menyehatkan.</p>
                            <div class="flex items-center text-amber-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>15 menit dari hotel</span>
                            </div>
                        </div>

                        <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition cursor-pointer"
                             onclick="showRoute(2.6432, 98.9321, 'Bukit Pahoda')">
                            <h4 class="text-lg font-semibold text-white mb-2">Bukit Pahoda</h4>
                            <p class="text-gray-400 text-sm mb-2">Spot terbaik untuk melihat matahari terbit dan terbenam dengan pemandangan Danau Toba.</p>
                            <div class="flex items-center text-amber-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>20 menit dari hotel</span>
                            </div>
                        </div>

                        <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition cursor-pointer"
                             onclick="showRoute(2.6478, 98.9198, 'Museum Huta Bolon')">
                            <h4 class="text-lg font-semibold text-white mb-2">Museum Huta Bolon</h4>
                            <p class="text-gray-400 text-sm mb-2">Pelajari sejarah dan budaya Batak melalui koleksi artefak dan rumah adat tradisional.</p>
                            <div class="flex items-center text-amber-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>25 menit dari hotel</span>
                            </div>
                        </div>

                        <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition cursor-pointer"
                             onclick="showRoute(2.6523, 98.9245, 'Pasar Pangururan')">
                            <h4 class="text-lg font-semibold text-white mb-2">Pasar Tradisional Pangururan</h4>
                            <p class="text-gray-400 text-sm mb-2">Kunjungi pasar tradisional untuk merasakan suasana lokal dan membeli oleh-oleh khas Samosir.</p>
                            <div class="flex items-center text-amber-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>10 menit dari hotel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Travel Tips -->
        <div class="bg-gray-800 rounded-xl p-8 mt-8">
            <h3 class="text-2xl font-bold text-white mb-6">Tips Perjalanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-700/50 rounded-lg p-6">
                    <div class="text-amber-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Waktu Terbaik</h4>
                    <p class="text-gray-400">Kunjungi tempat wisata pagi hari (8-10 pagi) atau sore hari (3-5 sore) untuk menghindari terik matahari.</p>
                </div>

                <div class="bg-gray-700/50 rounded-lg p-6">
                    <div class="text-amber-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2M6 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0M18 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0M18 7l-6-2m0-2l-3 1m3-1l-3 9a5.002 5.002 0 006.001 0M18 7l-3-1m3 1l3 9a5.002 5.002 0 006.001 0M18 7l-6-2"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Transportasi</h4>
                    <p class="text-gray-400">Sewa sepeda motor atau mobil tersedia di hotel. Kami juga bisa membantu memesan transportasi umum.</p>
                </div>

                <div class="bg-gray-700/50 rounded-lg p-6">
                    <div class="text-amber-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Informasi Penting</h4>
                    <p class="text-gray-400">Bawa air minum, topi, dan sunscreen. Beberapa tempat wisata mungkin memerlukan biaya masuk tambahan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Initialize map centered at Hotel Cahaya Pangururan
    const hotelLocation = [2.6512, 98.9252]; // Hotel coordinates
    const map = L.map('map').setView(hotelLocation, 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add hotel marker
    const hotelMarker = L.marker(hotelLocation)
        .addTo(map)
        .bindPopup('Hotel Cahaya Pangururan')
        .openPopup();

    let currentRouteLayer = null;

    function showRoute(lat, lng, locationName) {
        // Remove existing route if any
        if (currentRouteLayer) {
            map.removeLayer(currentRouteLayer);
        }

        // Add destination marker
        const destinationMarker = L.marker([lat, lng])
            .addTo(map)
            .bindPopup(locationName);

        // Draw a simple line between hotel and destination
        currentRouteLayer = L.polyline([hotelLocation, [lat, lng]], {
            color: '#F59E0B',
            weight: 3,
            opacity: 0.8
        }).addTo(map);

        // Fit map bounds to show both markers
        const bounds = L.latLngBounds([hotelLocation, [lat, lng]]);
        map.fitBounds(bounds, { padding: [50, 50] });

        // Open Google Maps in new tab for actual directions
        const googleMapsUrl = `https://www.google.com/maps/dir/${hotelLocation[0]},${hotelLocation[1]}/${lat},${lng}`;
        window.open(googleMapsUrl, '_blank');
    }
</script>
@endpush 