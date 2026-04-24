@extends('admin.layout')

@section('title', 'Admin Dashboard | ROFC Private Music')
@section('page-title', 'Dashboard Overview')

@section('content')
<section class="stats-cards">
    @foreach ($stats as $stat)
        <article class="stat-card">
            <p>{{ $stat['label'] }}</p>
            <h3>{{ $stat['value'] }}</h3>
            <span class="delta">{{ $stat['delta'] }}</span>
        </article>
    @endforeach
</section>

<section class="grid-two">
    <article class="admin-card">
        <h2>Students per Class</h2>
        <div class="chart-wrap"><canvas id="studentsPerClassChart"></canvas></div>
    </article>
    <article class="admin-card">
        <h2>Registrations per Month</h2>
        <div class="chart-wrap"><canvas id="registrationsPerMonthChart"></canvas></div>
    </article>
</section>

<section class="grid-two">
    <article class="admin-card">
        <h2>Classes Popularity</h2>
        <div class="chart-wrap"><canvas id="classPopularityChart"></canvas></div>
    </article>
    <article class="admin-card">
        <h2>Recent Registrations</h2>
        <ul class="activity-list">
            @foreach ($recentRegistrations as $registration)
                <li>
                    <div>
                        <strong>{{ $registration['name'] }}</strong><br>
                        <small>{{ $registration['program'] }} - {{ $registration['date'] }}</small>
                    </div>
                    <span class="status {{ $registration['status'] }}">{{ ucfirst($registration['status']) }}</span>
                </li>
            @endforeach
        </ul>
    </article>
</section>

<section class="grid-three">
    <article class="admin-card">
        <h3>Latest Events</h3>
        <ul class="activity-list">
            @foreach ($recentEvents as $event)
                <li>
                    <span>{{ $event['title'] }}</span>
                    <small>{{ $event['date'] }}</small>
                </li>
            @endforeach
        </ul>
    </article>
    <article class="admin-card">
        <h3>Latest Blog Posts</h3>
        <ul class="activity-list">
            @foreach ($recentPosts as $post)
                <li>
                    <span>{{ $post['title'] }}</span>
                    <span class="status {{ $post['status'] }}">{{ ucfirst($post['status']) }}</span>
                </li>
            @endforeach
        </ul>
    </article>
    <article class="admin-card">
        <h3>New Students</h3>
        <ul class="activity-list">
            @foreach ($newStudents as $student)
                <li>
                    <span>{{ $student['name'] }}</span>
                    <small>{{ $student['class'] }}</small>
                </li>
            @endforeach
        </ul>
    </article>
</section>
@endsection

@push('scripts')
<script>
    const chartTextColor = '#e6eaf4';
    const chartGridColor = 'rgba(255,255,255,0.08)';

    new Chart(document.getElementById('studentsPerClassChart'), {
        type: 'bar',
        data: {
            labels: ['Drum', 'Piano', 'Guitar', 'Vocal', 'Violin', 'Theory'],
            datasets: [{
                label: 'Students',
                data: [95, 120, 82, 74, 46, 69],
                backgroundColor: '#d2ab63',
                borderRadius: 8,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: chartTextColor } } },
            scales: {
                x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
                y: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
            },
        },
    });

    new Chart(document.getElementById('registrationsPerMonthChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Registrations',
                data: [24, 29, 33, 40, 37, 45],
                borderColor: '#5f8dff',
                backgroundColor: 'rgba(95,141,255,0.2)',
                tension: 0.35,
                fill: true,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: chartTextColor } } },
            scales: {
                x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
                y: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
            },
        },
    });

    new Chart(document.getElementById('classPopularityChart'), {
        type: 'doughnut',
        data: {
            labels: ['Piano', 'Drum', 'Guitar', 'Vocal', 'Others'],
            datasets: [{
                data: [31, 24, 19, 16, 10],
                backgroundColor: ['#d2ab63', '#5f8dff', '#45c28a', '#ea5b6f', '#8f95a7'],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: chartTextColor } } },
        },
    });
</script>
@endpush
