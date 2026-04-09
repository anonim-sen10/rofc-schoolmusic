@props([
    'title' => 'Dashboard',
    'subtitle' => 'ROFC School Music Management Information System',
    'searchPlaceholder' => 'Search in dashboard...',
    'userName' => 'User',
    'roleLabel' => 'USER',
    'notificationCount' => 0,
    'showSidebarToggle' => true,
])

@php
    $initial = strtoupper(substr(trim((string) $userName), 0, 1) ?: 'U');
@endphp

<header class="portal-topbar fixed top-0 right-0 z-40 transition-transform duration-300 ease-out border-b border-slate-800 bg-slate-900/80 backdrop-blur" data-portal-header>
    <div class="mx-auto flex w-full items-center justify-between gap-3 px-4 py-3 lg:gap-4 lg:px-6">
        <div class="min-w-0 flex flex-1 items-center gap-3">
            @if ($showSidebarToggle)
                <button type="button" class="toggle shrink-0 md:hidden" data-portal-toggle aria-label="Toggle sidebar">
                    <i data-lucide="panel-left"></i>
                </button>
            @endif

            <div class="min-w-0">
                <h1 class="truncate text-lg font-semibold text-slate-100 lg:text-xl">{{ $title }}</h1>
                <p class="truncate text-xs text-slate-400/80">{{ $subtitle }}</p>
            </div>
        </div>

        <div class="hidden flex-1 justify-center md:flex">
            <label class="flex w-full max-w-md items-center gap-2 rounded-xl border border-slate-700 bg-slate-950/70 px-3 py-2 text-slate-300" title="Search in current page">
                <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                <input type="search" placeholder="{{ $searchPlaceholder }}" data-global-search class="w-full bg-transparent text-sm text-slate-200 outline-none placeholder:text-slate-500">
            </label>
        </div>

        <div class="flex shrink-0 items-center gap-3">
            <button type="button" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-950/70 text-slate-200" title="Notifications" aria-label="Notifications">
                <i data-lucide="bell" class="h-4 w-4"></i>
                @if ((int) $notificationCount > 0)
                    <span class="notif-dot">{{ (int) $notificationCount > 9 ? '9+' : (int) $notificationCount }}</span>
                @endif
            </button>

            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-950/70 px-2.5 py-1.5">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-500/30 text-sm font-semibold text-indigo-100">{{ $initial }}</span>
                <span class="hidden text-left sm:block">
                    <strong class="block truncate text-sm font-semibold text-slate-100">{{ $userName }}</strong>
                    <small class="block text-xs uppercase tracking-wide text-slate-400">{{ $roleLabel }}</small>
                </span>
            </div>
        </div>
    </div>

    <div class="px-4 pb-3 md:hidden lg:px-6">
        <label class="flex w-full items-center gap-2 rounded-xl border border-slate-700 bg-slate-950/70 px-3 py-2 text-slate-300" title="Search in current page">
            <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
            <input type="search" placeholder="{{ $searchPlaceholder }}" data-global-search class="w-full bg-transparent text-sm text-slate-200 outline-none placeholder:text-slate-500">
        </label>
    </div>
</header>
