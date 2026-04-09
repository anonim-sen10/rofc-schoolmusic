@extends('portal.layout')

@section('title', 'Super Admin Dashboard | ROFC')
@section('page-title', 'Super Admin Data Center')

@section('content')
<section class="dashboard-hero" data-searchable>
    <div>
        <p class="eyebrow">Operations Intelligence</p>
        <h2>High-level performance in one screen.</h2>
        <p>Track growth, attendance quality, and cash movement in real time with clean visual storytelling.</p>
    </div>
    <div class="hero-actions">
        <a href="{{ route('super-admin.module', ['module' => 'registrations']) }}" class="ghost-btn" title="Review pending registration">Review Registrations</a>
        <a href="{{ route('super-admin.module', ['module' => 'reports']) }}" class="ghost-btn" title="Open reports">Open Reports</a>
    </div>
</section>

<section class="kpi-grid" data-searchable>
    @foreach ($kpis as $kpi)
        <x-ui.card class="kpi-card card-loading" :title="$kpi['label']">
            <div class="kpi-row">
                <div class="kpi-value">{{ $kpi['value'] }}</div>
                <span class="kpi-icon"><i data-lucide="{{ $kpi['icon'] }}"></i></span>
            </div>
            <div class="kpi-trend {{ $kpi['trend']['direction'] }}">
                <i data-lucide="{{ $kpi['trend']['direction'] === 'up' ? 'trending-up' : ($kpi['trend']['direction'] === 'down' ? 'trending-down' : 'minus') }}"></i>
                <span>{{ $kpi['trend']['label'] }} vs previous month</span>
            </div>
        </x-ui.card>
    @endforeach
</section>

<section class="chart-grid" data-searchable>
    <x-ui.card class="card-loading" title="Revenue Trend" subtitle="Paid revenue in the last 6 months">
        <canvas id="revenueChart" height="180"></canvas>
    </x-ui.card>
    <x-ui.card class="card-loading" title="Student Growth" subtitle="New students per month">
        <canvas id="studentGrowthChart" height="180"></canvas>
    </x-ui.card>
    <x-ui.card class="card-loading" title="Attendance Rate" subtitle="Present + Late over total attendance">
        <canvas id="attendanceRateChart" height="180"></canvas>
    </x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
    <x-ui.card class="card-loading" title="Latest Registrations" subtitle="Incoming leads from website">
        @if ($recentRegistrations->isNotEmpty())
            <x-ui.table :headers="['Name', 'Email', 'Status']">
                @foreach ($recentRegistrations as $row)
                    @php
                        $status = strtolower($row->status ?? 'pending');
                        $badgeType = $status === 'accepted' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning');
                    @endphp
                    <tr>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->email }}</td>
                        <td><x-ui.badge :type="$badgeType">{{ strtoupper($status) }}</x-ui.badge></td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state title="No registrations yet" description="New registrations will appear here when visitors submit the enrollment form." icon="user-plus" />
        @endif
    </x-ui.card>

    <x-ui.card class="card-loading" title="Latest Payments" subtitle="Most recent finance transactions">
        @if ($recentPayments->isNotEmpty())
            <x-ui.table :headers="['Date', 'Student', 'Amount', 'Status']">
                @foreach ($recentPayments as $row)
                    @php
                        $status = strtolower($row->status ?? 'pending');
                        $badgeType = $status === 'paid' ? 'success' : ($status === 'failed' ? 'danger' : 'warning');
                    @endphp
                    <tr>
                        <td>{{ optional($row->paid_at)->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $row->student?->name ?? '-' }}</td>
                        <td>Rp{{ number_format($row->amount, 0, ',', '.') }}</td>
                        <td><x-ui.badge :type="$badgeType">{{ strtoupper($status) }}</x-ui.badge></td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state title="No payments yet" description="Recent payments will appear after finance confirms transactions." icon="wallet" />
        @endif
    </x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
    <x-ui.card class="card-loading" title="Attendance Pulse" subtitle="Teacher and student attendance overview">
        <ul class="insight-list">
            <li>
                <span><i data-lucide="user-check"></i> Teacher Attendance Today</span>
                <strong>{{ $summary['teacher_attendance_today'] }}</strong>
            </li>
            <li>
                <span><i data-lucide="users"></i> Student Attendance Today</span>
                <strong>{{ $summary['student_attendance_today'] }}</strong>
            </li>
            <li>
                <span><i data-lucide="clock-3"></i> Pending Registrations</span>
                <strong>{{ $summary['registrations_pending'] }}</strong>
            </li>
            <li>
                <span><i data-lucide="receipt"></i> Unpaid Invoices</span>
                <strong>{{ $summary['invoices_unpaid'] }}</strong>
            </li>
        </ul>
    </x-ui.card>

    <x-ui.card class="card-loading" title="Latest Progress" subtitle="Newest coaching notes from teachers">
        @if ($recentProgress->isNotEmpty())
            <x-ui.table :headers="['Recorded', 'Student', 'Class', 'Topic', 'Score']">
                @foreach ($recentProgress as $row)
                    <tr>
                        <td>{{ optional($row->recorded_at)->format('Y-m-d') ?? optional($row->created_at)->format('Y-m-d') }}</td>
                        <td>#{{ $row->student_id }}</td>
                        <td>#{{ $row->class_id }}</td>
                        <td>{{ $row->topic ?? '-' }}</td>
                        <td><x-ui.badge type="info">{{ $row->score ?? '-' }}</x-ui.badge></td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state title="No progress records yet" description="Teacher progress logs will appear once sessions are recorded." icon="chart-line" />
        @endif
    </x-ui.card>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script id="dashboard-chart-data" type="application/json">@json($chartData)</script>
@endsection
