@php
    // Use the app layout for consistency if available, or just include the navbar
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: #000;
        }
    </style>
</head>
<body class="min-h-screen bg-black text-white relative overflow-hidden">
    <!-- Space SVG accents -->
    <svg class="absolute left-10 top-32 opacity-80" width="64" height="64" viewBox="0 0 64 64"><circle cx="32" cy="32" r="24" stroke="#FFA040" stroke-width="3" fill="none"/><ellipse cx="32" cy="32" rx="18" ry="6" stroke="#fff" stroke-width="2" fill="none"/></svg>
    <svg class="absolute right-20 bottom-20 opacity-80" width="48" height="48" viewBox="0 0 48 48"><circle cx="24" cy="24" r="12" fill="#FFA040"/><circle cx="38" cy="10" r="4" fill="#fff"/></svg>
    <svg class="absolute right-10 top-24 opacity-60" width="20" height="20" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4" fill="#fff"/></svg>
    <svg class="absolute left-24 bottom-32 opacity-60" width="20" height="20" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3.5" fill="#FFA040"/></svg>
    <!-- White stars (dots) -->
    <div class="absolute inset-0 pointer-events-none z-0">
        <div class="absolute top-10 left-1/4 w-1.5 h-1.5 bg-white rounded-full opacity-80"></div>
        <div class="absolute top-1/2 left-1/3 w-1 h-1 bg-white rounded-full opacity-60"></div>
        <div class="absolute top-1/3 left-2/3 w-1.5 h-1.5 bg-white rounded-full opacity-70"></div>
        <div class="absolute top-3/4 left-3/4 w-2 h-2 bg-white rounded-full opacity-90"></div>
        <div class="absolute top-2/5 left-4/5 w-1 h-1 bg-white rounded-full opacity-60"></div>
        <div class="absolute top-1/6 left-1/6 w-1 h-1 bg-white rounded-full opacity-70"></div>
        <div class="absolute top-3/5 left-1/2 w-1 h-1 bg-white rounded-full opacity-80"></div>
    </div>
    <div class="flex flex-col items-center justify-center min-h-screen pt-32 pb-16 z-10 relative">
        <div class="text-center">
            <div class="text-[120px] md:text-[180px] font-extrabold leading-none mb-2" style="font-family: 'Orbitron', sans-serif; color: white; -webkit-text-stroke: 4px #fff; text-stroke: 4px #fff; letter-spacing: 0.05em;">
                <span style="color:transparent; -webkit-text-stroke: 4px #fff; text-stroke: 4px #fff;">404</span>
            </div>
            <div class="text-2xl md:text-3xl font-bold mb-2" style="color:#FFA040">Page Not Found!</div>
            <div class="text-lg md:text-xl text-white mb-8">The requested page could not be found on the server.</div>
            <a href="{{ url('/') }}" class="inline-block border-2 border-[#FFA040] text-[#FFA040] hover:bg-[#FFA040] hover:text-black font-bold px-8 py-3 rounded-full transition-all duration-200">GO HOME</a>
        </div>
    </div>
</body>
</html> 