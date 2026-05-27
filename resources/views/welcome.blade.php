<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ROFC Private Music') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Fallback Tailwind CSS if Vite is not running -->
            <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        @endif
        
        <style>
            body { 
                font-family: 'Plus Jakarta Sans', sans-serif; 
            }
            .hero-img-mask {
                -webkit-mask-image: linear-gradient(to right, transparent 0%, black 25%);
                mask-image: linear-gradient(to right, transparent 0%, black 25%);
            }
        </style>
    </head>
    <body class="antialiased bg-gradient-to-br from-[#eaf2ff] via-[#f7faff] to-white min-h-screen flex flex-col overflow-x-hidden">
        
        <!-- Navigation -->
        <header class="absolute top-0 left-0 right-0 z-50 py-5 px-6 lg:px-12">
            <div class="max-w-[1400px] mx-auto flex items-center justify-between">
                
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="font-extrabold text-xl leading-tight text-slate-800 border-b-2 border-[#1a56db] pb-0.5">
                        ROFC
                    </div>
                    <div class="flex flex-col border-l-2 border-gray-300 pl-3">
                        <span class="text-sm font-bold text-slate-800 leading-none">ROFC Private Music</span>
                        <span class="text-[11px] font-medium text-slate-500 leading-none mt-1.5 uppercase tracking-wider">Music for a Better Education</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-700">
                    <a href="#" class="text-[#1a56db] border-b-2 border-[#1a56db] pb-1">Beranda</a>
                    <a href="#" class="hover:text-[#1a56db] transition-colors pb-1">Tentang Kami</a>
                    <a href="#" class="hover:text-[#1a56db] transition-colors pb-1">Layanan</a>
                    <a href="#" class="hover:text-[#1a56db] transition-colors pb-1">Produk</a>
                    <a href="#" class="hover:text-[#1a56db] transition-colors pb-1">Galeri</a>
                    <a href="#" class="hover:text-[#1a56db] transition-colors pb-1">Artikel</a>
                    <a href="#" class="hover:text-[#1a56db] transition-colors pb-1">Kontak</a>
                </nav>

                <!-- Auth Action Buttons -->
                <div class="hidden lg:flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 text-sm font-bold text-[#1a56db] border-2 border-[#1a56db] rounded-full hover:bg-blue-50 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-bold text-[#1a56db] border border-gray-200 bg-white rounded-full hover:border-[#1a56db] hover:shadow-sm transition-all">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-bold text-white bg-[#1a56db] rounded-full hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile Hamburger Icon -->
                <button class="lg:hidden text-slate-800 p-2">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Main Hero Section -->
        <main class="flex-grow flex relative pt-24 lg:pt-0">
            
            <!-- Background Elements (Desktop) -->
            <div class="absolute inset-y-0 right-0 w-full lg:w-[55%] z-0 hidden lg:block">
                <img src="{{ asset('images/hero-drummer.jpg') }}" alt="Drummer Student" class="w-full h-full object-cover object-[center_top] hero-img-mask" />
            </div>

            <!-- Content Area -->
            <div class="max-w-[1400px] mx-auto w-full px-6 lg:px-12 flex items-center relative z-10 min-h-[calc(100vh-96px)] lg:min-h-screen">
                
                <!-- Left Column -->
                <div class="w-full lg:w-1/2 pt-12 pb-20 lg:py-24 flex flex-col justify-center">
                    
                    <span class="text-[#1a56db] font-bold tracking-widest text-sm mb-5 uppercase">
                        ROFC Private Music
                    </span>
                    
                    <h1 class="text-[40px] lg:text-[64px] font-extrabold text-slate-800 leading-[1.1] mb-6 tracking-tight">
                        Music for <br class="hidden lg:block" />
                        <span class="text-[#1a56db]">a Better Education</span>
                    </h1>
                    
                    <p class="text-slate-600 text-lg lg:text-xl mb-12 max-w-lg leading-relaxed font-medium">
                        ROFC Private Music hadir untuk mendukung sekolah dalam membangun program musik yang berkualitas, inspiratif, dan berkelanjutan.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 mb-16">
                        <a href="#" class="px-8 py-3.5 bg-[#1a56db] text-white font-bold rounded-full shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all hover:-translate-y-0.5">
                            Tentang Kami
                        </a>
                        <a href="#" class="px-8 py-3.5 bg-white text-slate-700 font-bold rounded-full border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all flex items-center gap-2 hover:-translate-y-0.5 shadow-sm">
                            <svg class="w-5 h-5 text-[#1a56db]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                            </svg>
                            Lihat Video
                        </a>
                    </div>
                    
                    <!-- Feature Cards -->
                    <div class="flex flex-col sm:flex-row gap-5 mt-auto">
                        
                        <!-- Card 1 -->
                        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-4 shadow-sm border border-white flex items-center gap-4 flex-1 hover:shadow-md transition-shadow cursor-default">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
                                M
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-extrabold text-slate-800">Program Musik</span>
                                <span class="text-xs font-medium text-slate-500 mt-0.5">Berkualitas</span>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-4 shadow-sm border border-white flex items-center gap-4 flex-1 hover:shadow-md transition-shadow cursor-default">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
                                S
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-extrabold text-slate-800">Pengembangan</span>
                                <span class="text-xs font-medium text-slate-500 mt-0.5">Bakat Siswa</span>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-4 shadow-sm border border-white flex items-center gap-4 flex-1 hover:shadow-md transition-shadow cursor-default">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
                                E
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-extrabold text-slate-800">Edukasi Musik</span>
                                <span class="text-xs font-medium text-slate-500 mt-0.5">Berkelanjutan</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Mobile Image fallback (below content) -->
            <div class="lg:hidden w-full absolute inset-0 -z-10 opacity-30">
                <img src="{{ asset('images/hero-drummer.jpg') }}" alt="Drummer" class="w-full h-full object-cover" />
            </div>
        </main>
    </body>
</html>
