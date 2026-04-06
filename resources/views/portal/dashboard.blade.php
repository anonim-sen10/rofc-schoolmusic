@extends('portal.layout')

@section('title', $portal['title'].' | ROFC')
@section('page-title', 'Dashboard Overview')

@section('content')
<section class="stats-grid">
    @foreach ($stats as $stat)
        <article class="card stat">
            <p>{{ $stat['label'] }}</p>
            <h2>{{ $stat['value'] }}</h2>
        </article>
    @endforeach
</section>

<section class="split-grid">
    <article class="card">
        <h3>Recent Activity</h3>
        <ul class="list">
            @foreach ($recentActivities as $activity)
                <li>
                    <span>{{ $activity['title'] }}</span>
                    <small>{{ $activity['time'] }}</small>
                </li>
            @endforeach
        </ul>
    </article>
    <article class="card">
        <h3>Notifications</h3>
        <ul class="list">
            @foreach ($notifications as $notification)
                <li>
                    <span>{{ $notification['label'] }}</span>
                    <small>{{ strtoupper($notification['type']) }}</small>
                </li>
            @endforeach
        </ul>
    </article>
</section>

<section class="split-grid">
    <article class="card">
        <h3>Quick Actions</h3>
        <div class="quick-actions">
            @foreach (array_slice($portal['menu'], 1, 4) as $item)
                <a href="{{ route($portal['prefix'].'.module', ['module' => $item['key']]) }}">{{ $item['label'] }}</a>
            @endforeach
        </div>
    </article>
    <article class="card">
        <h3>Schedule & Reminder</h3>
        <ul class="list">
            @foreach ($reminders as $reminder)
                <li>
                    <span>{{ $reminder['label'] }}</span>
                </li>
            @endforeach
        </ul>
    </article>
</section>
@endsection
