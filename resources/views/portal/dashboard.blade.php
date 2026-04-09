@extends('portal.layout')

@section('title', $portal['title'].' | ROFC')
@section('page-title', 'Dashboard Overview')

@section('content')
<section class="dashboard-hero" data-searchable>
    <div>
        <p class="eyebrow">Operational Snapshot</p>
        <h2>{{ $portal['title'] }}</h2>
        <p>Semua indikator utama akademik dan operasional tersedia dalam satu layar untuk eksekusi harian yang lebih cepat.</p>
    </div>
    <div class="hero-actions">
        @if ($portal['prefix'] === 'admin')
            <a href="{{ route('admin.classes.index') }}" class="ghost-btn" title="Kelola kelas">Manage Classes</a>
            <a href="{{ route('admin.registrations.index') }}" class="ghost-btn" title="Cek pendaftaran">Review Registrations</a>
        @else
            @foreach (array_slice($portal['menu'], 1, 2) as $item)
                <a href="{{ route($portal['prefix'].'.module', ['module' => $item['key']]) }}" class="ghost-btn">{{ $item['label'] }}</a>
            @endforeach
        @endif
    </div>
</section>

<section class="kpi-grid" data-searchable>
    @foreach ($stats as $stat)
        @php
            $icon = $stat['icon'] ?? 'activity';
        @endphp
        <x-ui.card class="card-loading" :title="$stat['label']">
            <div class="kpi-row">
                <div class="kpi-value">{{ $stat['value'] }}</div>
                <span class="kpi-icon"><i data-lucide="{{ $icon }}"></i></span>
            </div>
        </x-ui.card>
    @endforeach
</section>

<section class="split-grid-sa" data-searchable>
    <x-ui.card class="card-loading" title="Recent Activity" subtitle="Aktivitas terakhir di sistem">
        <ul class="insight-list">
            @foreach ($recentActivities as $activity)
                <li>
                    <span><i data-lucide="clock-3"></i>{{ $activity['title'] }}</span>
                    <strong>{{ $activity['time'] }}</strong>
                </li>
            @endforeach
        </ul>
    </x-ui.card>

    <x-ui.card class="card-loading" title="Notifications" subtitle="Status penting yang perlu ditindaklanjuti">
        <ul class="insight-list">
            @foreach ($notifications as $notification)
                @php
                    $type = strtolower($notification['type'] ?? 'neutral');
                    $badgeType = $type === 'success' ? 'success' : ($type === 'warning' ? 'warning' : ($type === 'danger' ? 'danger' : 'info'));
                @endphp
                <li>
                    <span><i data-lucide="bell"></i>{{ $notification['label'] }}</span>
                    <x-ui.badge :type="$badgeType">{{ strtoupper($type) }}</x-ui.badge>
                </li>
            @endforeach
        </ul>
    </x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
    <x-ui.card class="card-loading" title="Quick Actions" subtitle="Akses cepat menu yang sering dipakai">
        <div class="quick-actions">
            @foreach (array_slice($portal['menu'], 1, 6) as $item)
                @if ($portal['prefix'] === 'admin' && $item['key'] === 'classes')
                    <a href="{{ route('admin.classes.index') }}">{{ $item['label'] }}</a>
                @elseif ($portal['prefix'] === 'admin' && $item['key'] === 'teachers')
                    <a href="{{ route('admin.teachers.index') }}">{{ $item['label'] }}</a>
                @elseif ($portal['prefix'] === 'admin' && $item['key'] === 'students')
                    <a href="{{ route('admin.students.index') }}">{{ $item['label'] }}</a>
                @elseif ($portal['prefix'] === 'admin' && $item['key'] === 'registrations')
                    <a href="{{ route('admin.registrations.index') }}">{{ $item['label'] }}</a>
                @else
                    <a href="{{ route($portal['prefix'].'.module', ['module' => $item['key']]) }}">{{ $item['label'] }}</a>
                @endif
            @endforeach
        </div>
    </x-ui.card>

    <x-ui.card class="card-loading" title="Schedule & Reminder" subtitle="Checklist harian admin">
        <ul class="insight-list">
            @foreach ($reminders as $reminder)
                <li>
                    <span><i data-lucide="check-circle-2"></i>{{ $reminder['label'] }}</span>
                    <x-ui.badge type="neutral">TODO</x-ui.badge>
                </li>
            @endforeach
        </ul>
    </x-ui.card>
</section>
@endsection
