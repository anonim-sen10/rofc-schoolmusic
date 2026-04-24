<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ROFC Private Music')</title>
    <meta name="description" content="ROFC Private Music - sekolah musik modern untuk anak, remaja, dan dewasa.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .main-nav .mobile-only-link {
            display: none !important;
        }

        @media (max-width: 980px) {
            .main-nav.open .mobile-only-link {
                display: block !important;
            }
        }
    </style>
</head>
<body class="rofc-body {{ request()->routeIs('home') ? 'is-home' : '' }}" style="background: #f3f6fb;">
    @php
        $navigation = [
            ['label' => 'Beranda', 'route' => 'home'],
            ['label' => 'Tentang Kami', 'route' => 'about'],
            ['label' => 'Layanan', 'route' => 'programs'],
            ['label' => 'Produk', 'route' => 'teachers'],
            ['label' => 'Galeri', 'route' => 'gallery'],
            ['label' => 'Artikel', 'route' => 'blog'],
            ['label' => 'Kontak', 'route' => 'contact'],
        ];
    @endphp

    <header class="site-header" style="background: rgba(255, 255, 255, 0.92); border-bottom: 1px solid rgba(164, 185, 217, 0.5); backdrop-filter: blur(10px);">
        <div class="container nav-wrap">
            <a href="{{ route('home') }}" class="brand-mark" aria-label="ROFC Private Music home">
                <span class="brand-badge">SM</span>
                <span>
                    <strong>ROFC Private Music</strong>
                    <small>Music for a Better Education</small>
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

                @auth
                    <a href="{{ route('portal.redirect') }}" class="mobile-only-link">Masuk</a>
                    <a href="{{ route('register') }}" class="mobile-only-link">Daftar Sekarang</a>
                @else
                    <a href="{{ route('login') }}" class="mobile-only-link">Masuk</a>
                    <a href="{{ route('register') }}" class="mobile-only-link">Daftar Sekarang</a>
                @endauth
            </nav>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('portal.redirect') }}" class="btn btn-header-outline desktop-cta">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary desktop-cta">Daftar Sekarang</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-header-outline desktop-cta">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary desktop-cta">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <h3>ROFC Private Music</h3>
                <p>Wadah belajar musik yang kreatif, nyaman, dan profesional untuk semua usia.</p>
            </div>
            <div>
                <h4>Kontak Cepat</h4>
                <p>WhatsApp: +62 812-3456-7890</p>
                <p>Email: hello@rofcprivatemusic.com</p>
            </div>
            <div>
                <h4>Alamat</h4>
                <p>Jl. Harmoni Musik No. 25, Jakarta Selatan</p>
                <p>Senin - Sabtu, 09.00 - 20.00 WIB</p>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>&copy; {{ date('Y') }} ROFC Private Music. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
