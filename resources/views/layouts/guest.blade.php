<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $orgName ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Single project stylesheet -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="font-sans antialiased">
        <div class="auth-page">
            <div class="auth-card">
                <div class="auth-brand">
                    

                    <div class="auth-illustration">
                        <svg viewBox="0 0 220 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="110" cy="165" rx="78" ry="9" fill="#4c1d95" opacity="0.25"/>
                            <path d="M30 56 h40 l16 -16 h58 a10 10 0 0 1 10 10 v80 a10 10 0 0 1 -10 10 H30 a10 10 0 0 1 -10 -10 V66 a10 10 0 0 1 10 -10 z" fill="#8b5cf6"/>
                            <rect x="66" y="44" width="92" height="74" rx="6" fill="#ffffff"/>
                            <rect x="80" y="60" width="64" height="6" rx="3" fill="#c4b5fd"/>
                            <rect x="80" y="73" width="64" height="6" rx="3" fill="#ddd6fe"/>
                            <rect x="80" y="86" width="42" height="6" rx="3" fill="#ddd6fe"/>
                            <path d="M16 88 h188 l-18 60 a8 8 0 0 1 -8 6 H34 a8 8 0 0 1 -8 -6 z" fill="#c4b5fd" opacity="0.95"/>
                        </svg>
                    </div>

                    <div class="auth-brand__tagline">
                        Secure document archiving<br>
                        <span style="font-size:.8rem;opacity:.8">all your files in one safe place</span>
                    </div>
                </div>

                <div class="auth-form">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
