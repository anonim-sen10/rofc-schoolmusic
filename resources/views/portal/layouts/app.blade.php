@php
    $resolvedRoleKey = $roleKey ?? (auth()->user()->primaryRole() ?? 'custom_role');
    $resolvedPanelTitle = $panelTitle ?? ($portal['title'] ?? 'ROFC Portal');
    $resolvedHomeRoute = $homeRoute ?? (($portal['prefix'] ?? null) ? route($portal['prefix'].'.dashboard') : route('portal.redirect'));
    $userName = auth()->user()?->name ?? 'User';
    $legacyMenuItems = $menuItems ?? [];
    $roleLabel = strtoupper(str_replace('_', ' ', $resolvedRoleKey));
    $notifCount = $summary['registrations_pending'] ?? 0;
    $iconMap = [
        'dashboard' => 'layout-dashboard',
        'classes' => 'book-open',
        'teachers' => 'music-2',
        'students' => 'graduation-cap',
        'registrations' => 'clipboard-list',
        'schedule' => 'calendar-days',
        'blog' => 'newspaper',
        'gallery' => 'image',
        'events' => 'calendar',
        'testimonials' => 'message-square-quote',
        'invoices' => 'receipt',
        'payments' => 'wallet',
        'expenses' => 'hand-coins',
        'reports' => 'bar-chart-3',
        'materials' => 'folder-open',
        'attendance' => 'user-check',
        'progress' => 'activity',
        'profile' => 'user-round',
    ];

    $normalizedMenu = collect($legacyMenuItems)->map(function (array $item) use ($iconMap) {
        $key = strtolower($item['key'] ?? str_replace(' ', '_', $item['label'] ?? 'menu'));

        return [
            'key' => $key,
            'label' => $item['label'] ?? ucfirst($key),
            'url' => $item['url'] ?? '#',
            'icon' => $item['icon'] ?? ($iconMap[$key] ?? 'circle'),
        ];
    });

@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ROFC Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/portal.css', 'resources/js/portal.js'])
</head>
<body class="portal-body">
    <div class="portal-shell">
        <aside class="portal-sidebar" data-portal-sidebar>
            <a href="{{ $resolvedHomeRoute }}" class="portal-brand">
                <span class="logo">RF</span>
                <span>
                    <strong>{{ $resolvedPanelTitle }}</strong>
                    <small>Role Based Access</small>
                </span>
            </a>

            <nav class="portal-menu">
                @foreach ($normalizedMenu as $item)
                    <a href="{{ $item['url'] }}" class="{{ request()->url() === $item['url'] ? 'active' : '' }}" data-tooltip="{{ $item['label'] }}">
                        <i data-lucide="{{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="portal-main">
            <x-portal.dashboard-header
                :title="trim($__env->yieldContent('page-title')) ?: 'Dashboard'"
                :subtitle="trim($__env->yieldContent('page-subtitle')) ?: 'ROFC School Music Management Information System'"
                search-placeholder="Search in dashboard..."
                :user-name="$userName"
                :role-label="$roleLabel"
                :notification-count="$notifCount"
            />

            <main class="portal-content">
                @if (session('success'))
                    <section class="card" data-searchable>
                        <x-ui.badge type="success">SUCCESS</x-ui.badge>
                        <p style="margin-top: 0.5rem;">{{ session('success') }}</p>
                    </section>
                @endif

                @if (session('error'))
                    <section class="card" data-searchable>
                        <x-ui.badge type="danger">ERROR</x-ui.badge>
                        <p style="margin-top: 0.5rem;">{{ session('error') }}</p>
                    </section>
                @endif

                @if ($errors->any())
                    <section class="card" data-searchable>
                        <x-ui.badge type="danger">ERROR</x-ui.badge>
                        <ul class="list">
                            @foreach ($errors->all() as $error)
                                <li><span>{{ $error }}</span></li>
                            @endforeach
                        </ul>
                    </section>
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

    <script src="https://cdn.jsdelivr.net/npm/lucide@0.511.0/dist/umd/lucide.min.js"></script>
    <script>
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
</body>
</html>
