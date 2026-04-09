@php
    $resolvedRoleKey = $roleKey ?? (auth()->user()->primaryRole() ?? 'custom_role');
    $resolvedPanelTitle = $panelTitle ?? ($portal['title'] ?? 'ROFC Portal');
    $resolvedHomeRoute = $homeRoute ?? (($portal['prefix'] ?? null) ? route($portal['prefix'].'.dashboard') : route('portal.redirect'));
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
            <header class="portal-topbar">
                <div class="topbar-left">
                    <button type="button" class="toggle" data-portal-toggle aria-label="Toggle sidebar">
                        <i data-lucide="panel-left"></i>
                    </button>
                    <div>
                        <p class="muted">ROFC School Music Management Information System</p>
                        <h1>@yield('page-title')</h1>
                    </div>
                </div>

                <div class="topbar-tools">
                    <label class="global-search" title="Search in current page">
                        <i data-lucide="search"></i>
                        <input type="search" placeholder="Search in dashboard..." data-global-search>
                    </label>

                    @if (in_array($resolvedRoleKey, ['admin', 'super_admin'], true))
                        @if (Route::has('admin.students.index'))
                            <a href="{{ route('admin.students.index') }}" class="quick-btn" title="Open students">
                                <i data-lucide="user-plus"></i>
                                <span>Students</span>
                            </a>
                        @endif
                        @if (Route::has('admin.classes.index'))
                            <a href="{{ route('admin.classes.index') }}" class="quick-btn" title="Open classes">
                                <i data-lucide="plus-circle"></i>
                                <span>Classes</span>
                            </a>
                        @endif
                    @endif

                    <button type="button" class="icon-btn" title="Notifications" aria-label="Notifications">
                        <i data-lucide="bell"></i>
                        @if ($notifCount > 0)
                            <span class="notif-dot">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                        @endif
                    </button>

                    <div class="user-box">
                        <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span>
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ $roleLabel }}</small>
                        </span>
                    </div>
                </div>
            </header>

            <main class="portal-content">
                @if (session('success'))
                    <section class="card" data-searchable>
                        <x-ui.badge type="success">SUCCESS</x-ui.badge>
                        <p style="margin-top: 0.5rem;">{{ session('success') }}</p>
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
