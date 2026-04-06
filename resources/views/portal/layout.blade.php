<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ROFC Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/portal.css', 'resources/js/portal.js'])
</head>
<body class="portal-body">
    <div class="portal-shell">
        <aside class="portal-sidebar" data-portal-sidebar>
            <a href="{{ route($portal['prefix'].'.dashboard') }}" class="portal-brand">
                <span class="logo">ROFC</span>
                <span>
                    <strong>{{ $portal['title'] }}</strong>
                    <small>Role Based Access</small>
                </span>
            </a>

            <nav class="portal-menu">
                @foreach ($portal['menu'] as $item)
                    @php
                        $isDashboard = $item['key'] === 'dashboard';
                        $url = $isDashboard
                            ? route($portal['prefix'].'.dashboard')
                            : route($portal['prefix'].'.module', ['module' => $item['key']]);
                        $active = $isDashboard
                            ? request()->routeIs($portal['prefix'].'.dashboard')
                            : request()->routeIs($portal['prefix'].'.module') && request()->route('module') === $item['key'];
                    @endphp
                    <a href="{{ $url }}" class="{{ $active ? 'active' : '' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </aside>

        <div class="portal-main">
            <header class="portal-topbar">
                <button type="button" class="toggle" data-portal-toggle>Menu</button>
                <div>
                    <p class="muted">ROFC School Music Management Information System</p>
                    <h1>@yield('page-title')</h1>
                </div>
                <div class="user-box">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ strtoupper(str_replace('_', ' ', $roleKey)) }}</small>
                </div>
            </header>

            <main class="portal-content">
                @yield('content')
            </main>

            <footer class="portal-footer">
                <p>&copy; {{ date('Y') }} ROFC School Music</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </footer>
        </div>
    </div>
</body>
</html>
