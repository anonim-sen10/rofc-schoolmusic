<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ROFC School Music')</title>
    <meta name="description" content="ROFC School Music - sekolah musik modern untuk anak, remaja, dan dewasa.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="rofc-body">
    @php
        $navigation = [
            ['label' => 'Home', 'route' => 'home'],
            ['label' => 'About', 'route' => 'about'],
            ['label' => 'Programs', 'route' => 'programs'],
            ['label' => 'Teachers', 'route' => 'teachers'],
            ['label' => 'Gallery', 'route' => 'gallery'],
            ['label' => 'Events', 'route' => 'events'],
            ['label' => 'Blog', 'route' => 'blog'],
            ['label' => 'Contact', 'route' => 'contact'],
        ];
    @endphp

    <header class="site-header">
        <div class="container nav-wrap">
            <a href="{{ route('home') }}" class="brand-mark" aria-label="ROFC School Music home">
                <span class="brand-badge">ROFC</span>
                <span>
                    <strong>School Music</strong>
                    <small>Creative. Professional. Inspiring.</small>
                </span>
            </a>

            <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="primary-nav">
                Menu
            </button>

            <nav class="main-nav" id="primary-nav" data-menu>
                @foreach ($navigation as $item)
                    <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <a href="{{ route('register') }}" class="btn btn-gold desktop-cta">Daftar Sekarang</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <h3>ROFC School Music</h3>
                <p>Wadah belajar musik yang kreatif, nyaman, dan profesional untuk semua usia.</p>
            </div>
            <div>
                <h4>Kontak Cepat</h4>
                <p>WhatsApp: +62 812-3456-7890</p>
                <p>Email: hello@rofcschoolmusic.com</p>
            </div>
            <div>
                <h4>Alamat</h4>
                <p>Jl. Harmoni Musik No. 25, Jakarta Selatan</p>
                <p>Senin - Sabtu, 09.00 - 20.00 WIB</p>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>&copy; {{ date('Y') }} ROFC School Music. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
