@props([
    'title' => 'Dashboard',
    'subtitle' => 'ROFC Private Music Management Information System',
    'searchPlaceholder' => 'Search in dashboard...',
    'userName' => 'User',
    'roleLabel' => 'USER',
    'notificationCount' => 0,
    'showSidebarToggle' => true,
])

@php
    $initial = strtoupper(substr(trim((string) $userName), 0, 1) ?: 'U');
@endphp

<header class="portal-topbar" data-portal-header>
    <div class="portal-topbar-inner">
        <div class="topbar-left">
            @if ($showSidebarToggle)
                <button type="button" class="toggle" data-portal-toggle aria-label="Toggle sidebar">
                    <i data-lucide="panel-left"></i>
                </button>
            @endif

            <div>
                <h1>{{ $title }}</h1>
                <p class="muted">{{ $subtitle }}</p>
            </div>
        </div>

        <div class="topbar-tools">
            <label class="global-search desktop-search" title="Search in current page">
                <i data-lucide="search"></i>
                <input type="search" placeholder="{{ $searchPlaceholder }}" data-global-search>
            </label>

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

    <div class="mobile-search-wrap">
        <label class="global-search" title="Search in current page">
            <i data-lucide="search"></i>
            <input type="search" placeholder="{{ $searchPlaceholder }}" data-global-search>
        </label>
    </div>
</header>
