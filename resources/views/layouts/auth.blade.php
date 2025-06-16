<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Auth') - Akun Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body, html { 
            height: 100%; 
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('{{ asset('storage/images/AuthWallpaper.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
        }
        .overlay {
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.4);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 0;
        }
        .container {
            display: flex;
            flex-direction: row;
            height: 100vh;
            max-width: 1300px;
            margin: auto;
            gap: 40px;
            position: relative;
            z-index: 1;
            padding-left: 32px;
            padding-right: 32px;
        }
        .welcome-section,
        .login-section {
            padding: 60px 40px;
        }
        .welcome-section {
            flex: 1;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }
        .welcome-section h1 {
            font-size: 4em;
            font-weight: bold;
            margin-bottom: 24px;
            line-height: 1.1;
            margin-top: -48px;
        }
        .welcome-section p {
            font-size: 1.25em;
            margin-bottom: 32px;
            max-width: 500px;
        }
        .login-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            min-width: 350px;
            color: #fff;
            text-align: left;
        }
        .login-section h2 {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: -10px;
        }
        .login-section form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        .nebula-input {
            position: relative;
            width: 100%;
            margin: 30px 0 0 0;
        }
        .nebula-input .input {
            width: 100%;
            padding: 15px;
            border: 2px solid #2a2a3a;
            background: rgba(255,255,255,0.10);
            color: #fff;
            font-size: 16px;
            outline: none;
            border-radius: 8px;
            transition: all 0.4s ease-out;
            backdrop-filter: blur(8px);
        }
        .nebula-input .user-label {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            font-size: 1em;
            background: transparent;
            padding: 0 4px;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        .nebula-input .input:focus {
            border-color: #FFD600;
            background: rgba(0,0,0,0.4);
            color: #fff;
            box-shadow:
                0 5px 8px rgba(255, 214, 0, 0.3),
                0 10px 20px rgba(255, 214, 0, 0.2),
                0 15px 40px rgba(255, 214, 0, 0.15),
                0 20px 60px rgba(255, 214, 0, 0.1);
        }
        .nebula-input .input:focus ~ .user-label,
        .nebula-input .input:valid ~ .user-label {
            top: -24px;
            left: 8px;
            font-size: 0.85em;
            color: #FFD600;
            background: transparent;
            padding: 0 8px;
            transform: none;
        }
        .remember {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }
        .remember label {
            margin-left: 5px;
            font-size: 0.95em;
            color: #fff;
        }
        .login-section button[type="submit"] {
            background: #e4572e;
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 4px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background 0.2s;
            width: 100%;
        }
        .login-section button[type="submit"]:hover {
            background: #b33c1a;
        }
        .lost-password {
            color: #fff;
            font-size: 0.95em;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .terms {
            font-size: 0.9em;
            color: #fff;
            margin-top: 20px;
            text-align: left;
        }
        .terms a {
            color: #e4572e;
            text-decoration: none;
        }
        .button {
            padding: 10px;
            font-weight: bold;
            display: flex;
            position: relative;
            overflow: hidden;
            border-radius: 35px;
            align-items: center;
            border: solid white 2px;
            outline: none;
            margin: 24px 0 16px 0;
            width: 100%;
            justify-content: center;
        }
        .svg {
            height: 25px;
            margin-right: 10px;
            z-index: 6;
        }
        .button .text {
            z-index: 10;
            font-size: 14px;
        }
        .button:hover .text {
            animation: text forwards 0.3s;
        }
        @keyframes text {
            from {
                color: black;
            }
            to {
                color: white;
            }
        }
        .button:hover::before {
            content: "";
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            opacity: 0;
            border-radius: 300px;
            animation: wave1 2.5s ease-in-out forwards;
        }
        .button:hover::after {
            content: "";
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            opacity: 0;
            border-radius: 300px;
            animation: wave2 2.5s ease-in-out forwards;
        }
        @keyframes wave1 {
            0% {
                z-index: 1;
                background: #EB4335;
                width: 0;
                height: 0;
                opacity: 1;
            }
            1% {
                z-index: 1;
                background: #EB4335;
                width: 0;
                height: 0;
                opacity: 1;
            }
            25% {
                z-index: 1;
                background: #EB4335;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
            26% {
                z-index: 3;
                background: #34A853;
                width: 0;
                height: 0;
                opacity: 1;
            }
            50% {
                z-index: 3;
                background: #34A853;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
            70% {
                z-index: 3;
                background: #34A853;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
            100% {
                z-index: 3;
                background: #34A853;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
        }
        @keyframes wave2 {
            0% {
                z-index: 2;
                background: #FBBC05;
                width: 0;
                height: 0;
                opacity: 1;
            }
            11% {
                z-index: 2;
                background: #FBBC05;
                width: 0;
                height: 0;
                opacity: 1;
            }
            35% {
                z-index: 2;
                background: #FBBC05;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
            39% {
                z-index: 2;
                background: #FBBC05;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
            40% {
                z-index: 4;
                background: #4285F4;
                width: 0;
                height: 0;
                opacity: 1;
            }
            64% {
                z-index: 4;
                background: #4285F4;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
            100% {
                z-index: 4;
                background: #4285F4;
                width: 800px;
                height: 800px;
                opacity: 1;
            }
        }
        .button:hover .red {
            animation: disappear 0.1s forwards;
            animation-delay: 0.1s;
        }
        .button:hover .yellow {
            animation: disappear 0.1s forwards;
            animation-delay: 0.3s;
        }
        .button:hover .green {
            animation: disappear 0.1s forwards;
            animation-delay: 0.7s;
        }
        .button:hover .blue {
            animation: disappear 0.1s forwards;
            animation-delay: 1.1s;
        }
        @keyframes disappear {
            from {
                filter: brightness(1);
            }
            to {
                filter: brightness(100);
            }
        }
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
            .welcome-section, .login-section {
                min-width: unset;
                padding: 40px 20px;
                align-items: center;
                text-align: center;
            }
            .login-section {
                align-items: center;
            }
        }
        .error-message {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.2);
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        /* Brutalist Alert Styles */
        .brutalist-swal-alert {
            width: 340px;
            border: 4px solid #000;
            background: #fff;
            padding: 1.5rem;
            box-shadow: 10px 10px 0 #000;
            font-family: 'Arial', sans-serif;
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 99999;
            transform: translate(-50%, -50%);
            animation: fadeIn .2s;
            border-radius: 12px;
        }
        .brutalist-swal-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #000;
            padding-bottom: 1rem;
        }
        .brutalist-swal-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
            padding: 0.5rem;
            border-radius: 8px;
        }
        .brutalist-swal-icon.success { background: #22c55e; }
        .brutalist-swal-icon.danger  { background: #ef4444; }
        .brutalist-swal-icon.warning { background: #facc15; }
        .brutalist-swal-icon.success svg { fill: #fff; }
        .brutalist-swal-icon.danger svg  { fill: #fff; }
        .brutalist-swal-icon.warning svg { fill: #000; }
        .brutalist-swal-title {
            font-weight: 900;
            color: #000;
            font-size: 1.3rem;
            text-transform: uppercase;
        }
        .brutalist-swal-message {
            margin-top: 1rem;
            color: #000;
            font-size: 1rem;
            line-height: 1.4;
            border-bottom: 2px solid #000;
            padding-bottom: 1rem;
            font-weight: 600;
        }
        .brutalist-swal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.18);
            z-index: 99998;
        }
    </style>
    <script>
        function showBrutalistSwalAlert({ type = 'success', title = '', message = '', timer = null }) {
            document.querySelectorAll('.brutalist-swal-alert, .brutalist-swal-overlay').forEach(e => e.remove());
            const overlay = document.createElement('div');
            overlay.className = 'brutalist-swal-overlay';
            document.body.appendChild(overlay);
            
            let iconSvg = '';
            if (type === 'success') {
                iconSvg = `<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"></path></svg>`;
            } else if (type === 'danger') {
                iconSvg = `<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13l-1.41 1.41L12 13.41l-3.59 3.59L7 15l3.59-3.59L7 7.83 8.41 6.41 12 10.59l3.59-3.59L17 7.83l-3.59 3.59L17 15z"></path></svg>`;
            } else {
                iconSvg = `<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>`;
            }
            
            const alert = document.createElement('div');
            alert.className = 'brutalist-swal-alert';
            alert.innerHTML = `
                <div class="brutalist-swal-header">
                    <div class="brutalist-swal-icon ${type}">${iconSvg}</div>
                    <div class="brutalist-swal-title">${title}</div>
                </div>
                <div class="brutalist-swal-message">${message}</div>
            `;
            document.body.appendChild(alert);

            if (timer) {
                setTimeout(() => {
                    alert.remove();
                    overlay.remove();
                }, timer);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showBrutalistSwalAlert({
                    type: 'success',
                    title: 'Success',
                    message: '{{ session("success") }}',
                    timer: 3000
                });
            @endif

            @if(session('error'))
                showBrutalistSwalAlert({
                    type: 'danger',
                    title: 'Error',
                    message: '{{ session("error") }}',
                    timer: 3000
                });
            @endif
        });
    </script>
</head>
<body class="font-sans antialiased">
    <div class="overlay"></div>
    <div class="container">
        <!-- Left: Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome<br>To Cahaya Resort</h1>
            <p>Please log in to access your account and enjoy our services.</p>
        </div>
        <!-- Right: Auth Content -->
        <div class="login-section">
            @yield('content')
        </div>
    </div>
    @if(session('welcome'))
    <div id="welcome-popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm text-center shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Welcome!</h2>
            <p>{{ session('welcome') }}</p>
            <button onclick="document.getElementById('welcome-popup').style.display='none'"
                    class="mt-4 px-4 py-2 bg-orange-400 text-white rounded hover:bg-orange-500">
                Tutup
            </button>
        </div>
    </div>
    @endif
</body>
</html> 