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
    @php
        $notifCount = $summary['registrations_pending'] ?? 0;
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
                    <label class="global-search" title="Search modules and tables">
                        <i data-lucide="search"></i>
                        <input type="search" placeholder="Search in dashboard..." data-global-search>
                    </label>

                    @if (in_array($roleKey, ['admin', 'super_admin'], true))
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
                            <small>{{ strtoupper(str_replace('_', ' ', $roleKey)) }}</small>
                        </span>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.511.0/dist/umd/lucide.min.js"></script>
    <script>
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
</body>
</html>
