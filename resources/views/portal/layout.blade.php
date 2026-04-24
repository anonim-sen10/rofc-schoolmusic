<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ROFC Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    @production
        <link rel="stylesheet" href="{{ asset('build/assets/portal-ZUNQwoHP.css') }}">
        <script type="module" src="{{ asset('build/assets/portal-CwMoeG5W.js') }}" defer></script>
    @else
        @vite(['resources/css/portal.css', 'resources/js/portal.js'])
    @endproduction
</head>
<body class="portal-body">
    @php
        $notifCount = $summary['registrations_pending'] ?? 0;
        $userName = auth()->user()?->name ?? 'User';
        $roleLabel = strtoupper(str_replace('_', ' ', $roleKey ?? 'user'));
    @endphp
    <div class="portal-shell">
        <aside class="portal-sidebar" data-portal-sidebar>
            <a href="{{ route($portal['prefix'].'.dashboard') }}" class="portal-brand">
                <span class="logo">RF</span>
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
                    <a href="{{ $url }}" class="{{ $active ? 'active' : '' }}" data-tooltip="{{ $item['label'] }}">
                        <i data-lucide="{{ $item['icon'] ?? 'circle' }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="portal-main">
            <x-portal.dashboard-header
                :title="trim($__env->yieldContent('page-title')) ?: 'Dashboard'"
                :subtitle="trim($__env->yieldContent('page-subtitle')) ?: 'ROFC Private Music Management Information System'"
                search-placeholder="Search in dashboard..."
                :user-name="$userName"
                :role-label="$roleLabel"
                :notification-count="$notifCount"
            />

            <main class="portal-content">
                @if (session('error'))
                    <section class="card" data-searchable>
                        <x-ui.badge type="danger">ERROR</x-ui.badge>
                        <p style="margin-top: 0.5rem;">{{ session('error') }}</p>
                    </section>
                @endif

                @yield('content')
            </main>

            <footer class="portal-footer">
                <p>&copy; {{ date('Y') }} ROFC Private Music</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.511.0/dist/umd/lucide.min.js"></script>
    <script>
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
</body>
</html>
