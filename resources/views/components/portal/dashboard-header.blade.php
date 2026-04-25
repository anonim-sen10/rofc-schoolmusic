@props([
    'title' => 'Dashboard',
    'subtitle' => 'ROFC Private Music Management Information System',
    'userName' => 'User',
    'roleLabel' => 'USER',
    'notificationCount' => 0,
    'showSidebarToggle' => true,
    'showNavbarLogo' => false,
    'brandLogoUrl' => null,
])

@php
    $initial = strtoupper(substr(trim((string) $userName), 0, 1) ?: 'U');
@endphp

<header class="portal-topbar" data-portal-header>
    <div class="portal-topbar-inner">
        <div class="topbar-left">
            @if ($showSidebarToggle)
                <button type="button" class="toggle" data-portal-sidebar-toggle aria-label="Toggle sidebar">
                    <i data-lucide="panel-left"></i>
                </button>
            @endif

            @if ($showNavbarLogo && $brandLogoUrl)
                <a href="{{ route('portal.redirect') }}" class="topbar-brand" aria-label="SchoolMusic Dashboard Home">
                    <img src="{{ $brandLogoUrl }}" alt="ROFC Music School" class="topbar-brand-logo">
                </a>
            @endif

            <div>
                <h1>{{ $title }}</h1>
                <p class="muted">{{ $subtitle }}</p>
            </div>
        </div>

        <div class="topbar-tools">
            <button type="button" class="icon-btn" title="Notifications" aria-label="Notifications">
                <i data-lucide="bell"></i>
                @if ((int) $notificationCount > 0)
                    <span class="notif-dot">{{ (int) $notificationCount > 9 ? '9+' : (int) $notificationCount }}</span>
                @endif
            </button>

            <details class="profile-dropdown">
                <summary class="user-box" aria-label="Open profile menu">
                    <span class="avatar">{{ $initial }}</span>
                    <span>
                        <strong>{{ $userName }}</strong>
                        <small>{{ $roleLabel }}</small>
                    </span>
                    <i data-lucide="chevron-down"></i>
                </summary>
                <div class="profile-menu">
                    <span class="profile-menu-role">{{ $roleLabel }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-menu-logout">Logout</button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</header>
