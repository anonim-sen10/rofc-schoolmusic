@extends('portal.layout')

@section('title', 'Super Admin Dashboard | ROFC')
@section('page-title', 'Super Admin Data Center')
@section('page-subtitle', 'ROFC Private Music Management Information System - Super Admin Control')

@section('content')
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

<section class="split-grid-sa" data-searchable>
    <x-ui.card class="card-loading" title="System Activity Feed" subtitle="Audit trail of recent system changes">
        <ul class="activity-feed">
            @forelse ($recentActivities as $activity)
                <li class="activity-item">
                    <div class="activity-icon {{ $activity->action }}">
                        @php
                            $icon = $activity->action === 'created' ? 'plus' : ($activity->action === 'updated' ? 'edit-3' : 'trash-2');
                        @endphp
                        <i data-lucide="{{ $icon }}"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">{{ $activity->description }}</p>
                        <p class="activity-meta">By <strong>{{ $activity->user?->name ?? 'System' }}</strong> • {{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <x-ui.empty-state title="No activities logged" description="Activities will appear here as users interact with the system." icon="history" />
            @endforelse
        </ul>
    </x-ui.card>

    <x-ui.card class="card-loading" title="Quick Insights" subtitle="Global system status overview">
        <div class="insight-stats-grid">
            <div class="insight-stat">
                <span class="label">Total Records Logged</span>
                <span class="value">{{ \DB::table('activities')->count() }}</span>
            </div>
            <div class="insight-stat">
                <span class="label">Most Active User</span>
                <span class="value">
                    @php
                        $topUser = \DB::table('activities')
                            ->select('user_id', \DB::raw('count(*) as total'))
                            ->groupBy('user_id')
                            ->orderByDesc('total')
                            ->first();
                        $topUserName = $topUser ? (\App\Models\User::find($topUser->user_id)?->name ?? 'System') : '-';
                    @endphp
                    {{ $topUserName }}
                </span>
            </div>
            <div class="insight-stat">
                <span class="label">Today's Activities</span>
                <span class="value">{{ \DB::table('activities')->whereDate('created_at', today())->count() }}</span>
            </div>
        </div>
    </x-ui.card>
</section>

<style>
    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        padding: 0.5rem;
    }
    .activity-item {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        shrink: 0;
    }
    .activity-icon i {
        width: 16px;
        height: 16px;
    }
    .activity-icon.created { background: #ecfdf5; color: #059669; }
    .activity-icon.updated { background: #eff6ff; color: #2563eb; }
    .activity-icon.deleted { background: #fef2f2; color: #dc2626; }
    
    .activity-title {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }
    .activity-meta {
        font-size: 11px;
        color: #64748b;
    }
    
    .insight-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1.5rem;
        padding: 0.5rem;
    }
    .insight-stat {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .insight-stat .label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .insight-stat .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script id="dashboard-chart-data" type="application/json">@json($chartData)</script>
@endsection
