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
            <a href="{{ $homeRoute }}" class="portal-brand">
                <span class="logo">ROFC</span>
                <span>
                    <strong>{{ $panelTitle }}</strong>
                    <small>{{ strtoupper(str_replace('_', ' ', auth()->user()->primaryRole() ?? '-')) }}</small>
                </span>
            </a>
            <nav class="portal-menu">
                @foreach ($menuItems as $item)
                    <a href="{{ $item['url'] }}" class="{{ request()->url() === $item['url'] ? 'active' : '' }}">{{ $item['label'] }}</a>
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
                    <small>{{ auth()->user()->email }}</small>
                </div>
            </header>

            <main class="portal-content">
                @if (session('success'))
                    <div class="card" style="border-color:#2b6f4c;color:#b9ffd8;">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="card" style="border-color:#8b2f43;color:#ffd2db;">{{ $errors->first() }}</div>
                @endif
                @yield('content')
            </main>

            <footer class="portal-footer">
                <p>&copy; {{ date('Y') }} ROFC School Music</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </footer>
        </div>
    </div>
</body>
</html>
