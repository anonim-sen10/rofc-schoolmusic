<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ROFC Admin Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js', 'resources/js/admin.js'])
</head>
<body class="admin-body">
    @php
        $menu = [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'DB'],
            ['route' => 'admin.classes', 'label' => 'Classes', 'icon' => 'CL'],
            ['route' => 'admin.teachers', 'label' => 'Teachers', 'icon' => 'TC'],
            ['route' => 'admin.students', 'label' => 'Students', 'icon' => 'ST'],
            ['route' => 'admin.registrations', 'label' => 'Registrations', 'icon' => 'RG'],
            ['route' => 'admin.gallery', 'label' => 'Gallery', 'icon' => 'GL'],
            ['route' => 'admin.events', 'label' => 'Events', 'icon' => 'EV'],
            ['route' => 'admin.blog', 'label' => 'Blog', 'icon' => 'BL'],
            ['route' => 'admin.testimonials', 'label' => 'Testimonials', 'icon' => 'TS'],
            ['route' => 'admin.users', 'label' => 'Users', 'icon' => 'US'],
            ['route' => 'admin.settings', 'label' => 'Settings', 'icon' => 'SE'],
        ];
    @endphp

    <div class="admin-shell">
        <aside class="admin-sidebar" data-admin-sidebar>
            <a href="{{ route('admin.dashboard') }}" class="admin-logo">
                <span class="logo-badge">ROFC</span>
                <span>
                    <strong>Private Music</strong>
                    <small>Admin Panel</small>
                </span>
            </a>

            <nav class="admin-menu">
                @foreach ($menu as $item)
                    <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                        <span class="menu-icon">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
                <a href="{{ route('home') }}">
                    <span class="menu-icon">LG</span>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-label="Toggle sidebar">Menu</button>
                <div class="topbar-meta">
                    <div>
                        <p class="muted">ROFC Private Music Management System</p>
                        <h1>@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="topbar-user">
                        <span>Admin</span>
                        <small>Super Admin</small>
                    </div>
                </div>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>

            <footer class="admin-footer">
                <p>&copy; {{ date('Y') }} ROFC Private Music. Internal Dashboard.</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
