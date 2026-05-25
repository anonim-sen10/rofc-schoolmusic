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

<section class="schedule-section-sa" id="daily-schedule" data-searchable>
    <div class="schedule-card-sa">
        <div class="schedule-card-header">
            <div class="header-left">
                <span class="header-icon"><i data-lucide="calendar"></i></span>
                <div>
                    <h3 class="header-title">Jadwal Kelas Harian Pengajar</h3>
                    <p class="header-subtitle">Daftar sesi kelas terjadwal untuk tanggal terpilih</p>
                </div>
            </div>
            <div class="header-right">
                <form method="GET" action="{{ url()->current() }}#daily-schedule" id="schedule-date-form">
                    <label for="schedule-date-input" class="date-label">Pilih Tanggal:</label>
                    <div class="date-input-wrapper">
                        <input type="date" id="schedule-date-input" name="schedule_date" value="{{ $selectedDate }}" onchange="document.getElementById('schedule-date-form').submit()">
                        <i data-lucide="calendar" class="calendar-icon" style="position: absolute; left: 0.9rem; color: #64748b; width: 1rem; height: 1rem; pointer-events: none;"></i>
                    </div>
                </form>
            </div>
        </div>

        <div class="schedule-card-filters">
            <div class="filter-search-box">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" id="schedule-search" placeholder="Cari nama guru, siswa, atau kelas..." onkeyup="filterDailySchedule()">
            </div>
            <div class="filter-select-box">
                <label for="schedule-status-filter">Status:</label>
                <select id="schedule-status-filter" onchange="filterDailySchedule()">
                    <option value="all">Semua Status</option>
                    <option value="booked">Terjadwal (Booked)</option>
                    <option value="completed">Selesai (Completed)</option>
                    <option value="rescheduled">Rescheduled</option>
                    <option value="absent">Alpa (Absent)</option>
                </select>
            </div>
            <div class="schedule-stats-summary">
                <span class="stat-badge total">Total: <strong>{{ $todayScheduleStats['total'] }}</strong></span>
                <span class="stat-badge booked">Terjadwal: <strong>{{ $todayScheduleStats['booked'] }}</strong></span>
                <span class="stat-badge completed">Selesai: <strong>{{ $todayScheduleStats['completed'] }}</strong></span>
                <span class="stat-badge rescheduled">Rescheduled: <strong>{{ $todayScheduleStats['rescheduled'] }}</strong></span>
            </div>
        </div>

        <div class="schedule-table-wrap">
            @if($todaySchedules->isNotEmpty())
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Jam</th>
                            <th>Pengajar/Guru</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th style="width: 130px; text-align: center;">Status</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-table-body">
                        @foreach($todaySchedules as $session)
                            @php
                                $status = strtolower($session->status);
                                $badgeType = 'info';
                                if ($status === 'completed') {
                                    $badgeType = 'success';
                                } elseif ($status === 'rescheduled') {
                                    $badgeType = 'warning';
                                } elseif ($status === 'absent') {
                                    $badgeType = 'danger';
                                }
                                
                                // Check if attendance has proof/details
                                $hasAttendance = $session->attendance;
                                $attendanceStatus = $hasAttendance ? strtolower($hasAttendance->status) : null;
                                if ($attendanceStatus === 'absent') {
                                    $badgeType = 'danger';
                                }
                            @endphp
                            <tr class="schedule-row" 
                                data-teacher="{{ strtolower($session->teacher->name ?? '') }}" 
                                data-student="{{ strtolower($session->student->name ?? '') }}" 
                                data-class="{{ strtolower($session->musicClass->name ?? '') }}" 
                                data-status="{{ $status }}">
                                <td class="col-time">
                                    <div class="time-box">
                                        <i data-lucide="clock" class="time-icon"></i>
                                        <span>{{ substr((string)$session->time, 0, 5) }}</span>
                                    </div>
                                </td>
                                <td class="col-teacher">
                                    <div class="user-avatar-info">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($session->teacher->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <span class="user-name">{{ $session->teacher->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="col-student">
                                    <div class="user-avatar-info">
                                        <div class="avatar-circle student">
                                            {{ strtoupper(substr($session->student->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="user-name">{{ $session->student->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="col-class">
                                    <span class="class-badge">{{ $session->musicClass->name ?? '-' }}</span>
                                </td>
                                <td class="col-status" style="text-align: center;">
                                    <x-ui.badge :type="$badgeType">
                                        {{ strtoupper($status) }}
                                    </x-ui.badge>
                                </td>
                                <td class="col-actions" style="text-align: center;">
                                    @if($hasAttendance && ($hasAttendance->image_path || ($hasAttendance->latitude && $hasAttendance->longitude)))
                                        <div class="actions-wrapper">
                                            @if($hasAttendance->image_path)
                                                <a href="{{ asset('storage/' . $hasAttendance->image_path) }}" target="_blank" class="btn-action detail" title="Lihat Bukti Foto">
                                                    <i data-lucide="image"></i>
                                                </a>
                                            @endif
                                            @if($hasAttendance->latitude && $hasAttendance->longitude)
                                                <a href="https://www.google.com/maps?q={{ $hasAttendance->latitude }},{{ $hasAttendance->longitude }}" target="_blank" class="btn-action map" title="Lihat Lokasi GPS">
                                                    <i data-lucide="map-pin"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="no-actions">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="schedule-empty-state">
                    <div class="empty-icon-wrap">
                        <i data-lucide="calendar-x"></i>
                    </div>
                    <h4>Tidak ada jadwal mengajar</h4>
                    <p>Tidak ada sesi kelas yang dijadwalkan untuk tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
function filterDailySchedule() {
    const searchVal = document.getElementById('schedule-search').value.toLowerCase().trim();
    const statusVal = document.getElementById('schedule-status-filter').value;
    const rows = document.querySelectorAll('.schedule-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const teacher = row.getAttribute('data-teacher');
        const student = row.getAttribute('data-student');
        const className = row.getAttribute('data-class');
        const status = row.getAttribute('data-status');

        const matchesSearch = !searchVal || 
                              teacher.includes(searchVal) || 
                              student.includes(searchVal) || 
                              className.includes(searchVal);
                              
        const matchesStatus = statusVal === 'all' || status === statusVal;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const tableBody = document.getElementById('schedule-table-body');
    if (tableBody) {
        let noResultMsg = document.getElementById('schedule-no-results');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultMsg) {
                noResultMsg = document.createElement('tr');
                noResultMsg.id = 'schedule-no-results';
                noResultMsg.innerHTML = `
                    <td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8;">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i data-lucide="search-code" style="width: 32px; height: 32px; color: #cbd5e1;"></i>
                            <span style="font-size: 0.9rem; font-weight: 500;">Tidak ada jadwal yang cocok dengan kata kunci atau filter status.</span>
                        </div>
                    </td>
                `;
                tableBody.appendChild(noResultMsg);
                if (window.lucide) window.lucide.createIcons();
            }
        } else {
            if (noResultMsg) {
                noResultMsg.remove();
            }
        }
    }
}
</script>

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
                <span class="value">{{ $totalActivities }}</span>
            </div>
            <div class="insight-stat">
                <span class="label">Most Active User</span>
                <span class="value">
                    {{ $topUserName }}
                </span>
            </div>
            <div class="insight-stat">
                <span class="label">Today's Activities</span>
                <span class="value">{{ $todayActivities }}</span>
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
        color: var(--text);
    }

    /* Daily Schedule premium card styles */
    .schedule-section-sa {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    .schedule-card-sa {
        background: #ffffff;
        border-radius: 1.5rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .schedule-card-header {
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .schedule-card-header .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .schedule-card-header .header-icon {
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 8px 16px rgba(99, 102, 241, 0.15);
    }
    .schedule-card-header .header-icon i {
        width: 1.5rem;
        height: 1.5rem;
    }
    .schedule-card-header .header-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .schedule-card-header .header-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0.15rem 0 0 0;
        font-weight: 500;
    }
    .schedule-card-header .header-right form {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .schedule-card-header .date-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
    }
    .schedule-card-header .date-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .schedule-card-header input[type="date"] {
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
        outline: none;
    }
    .schedule-card-header input[type="date"]:focus {
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .schedule-card-header .calendar-icon {
        position: absolute;
        left: 0.9rem;
        color: #64748b;
        width: 1rem;
        height: 1rem;
        pointer-events: none;
    }

    .schedule-card-filters {
        padding: 1.25rem 2rem;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .filter-search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
        display: flex;
        align-items: center;
    }
    .filter-search-box input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        color: #1e293b;
        background: #ffffff;
        transition: all 0.2s;
        outline: none;
    }
    .filter-search-box input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .filter-search-box .search-icon {
        position: absolute;
        left: 0.9rem;
        color: #94a3b8;
        width: 1.1rem;
        height: 1.1rem;
        pointer-events: none;
    }
    .filter-select-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-select-box label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
    }
    .filter-select-box select {
        padding: 0.6rem 2.5rem 0.6rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        outline: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
    }
    .filter-select-box select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .schedule-stats-summary {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-left: auto;
    }
    .stat-badge {
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        background: #e2e8f0;
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .stat-badge strong {
        color: #0f172a;
    }
    .stat-badge.total { background: #f1f5f9; color: #475569; }
    .stat-badge.booked { background: #e0e7ff; color: #4338ca; }
    .stat-badge.booked strong { color: #4338ca; }
    .stat-badge.completed { background: #dcfce7; color: #15803d; }
    .stat-badge.completed strong { color: #15803d; }
    .stat-badge.rescheduled { background: #fef3c7; color: #d97706; }
    .stat-badge.rescheduled strong { color: #d97706; }

    .schedule-table-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
    }
    .schedule-table th {
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .schedule-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #334155;
    }
    .schedule-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .schedule-table tbody tr:hover {
        background-color: #f8fbff;
    }
    .schedule-table tbody tr:last-child td {
        border-bottom: none;
    }

    .time-box {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 800;
        color: #1e293b;
        background: #f1f5f9;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        font-variant-numeric: tabular-nums;
    }
    .time-icon {
        width: 0.9rem;
        height: 0.9rem;
        color: #64748b;
    }
    .user-avatar-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .avatar-circle {
        width: 2.25rem;
        height: 2.25rem;
        background: linear-gradient(135deg, #a5b4fc, #818cf8);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.9rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.1);
    }
    .avatar-circle.student {
        background: linear-gradient(135deg, #fbcfe8, #f472b6);
        box-shadow: 0 2px 4px rgba(244, 114, 182, 0.1);
    }
    .user-name {
        font-weight: 700;
        color: #0f172a;
    }
    .class-badge {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .actions-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-action {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    .btn-action.detail:hover {
        background: #eff6ff;
        color: #3b82f6;
        border-color: #bfdbfe;
    }
    .btn-action.map:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fecaca;
    }
    .btn-action i {
        width: 1rem;
        height: 1rem;
    }
    .no-actions {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .schedule-empty-state {
        padding: 4rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .empty-icon-wrap {
        width: 4rem;
        height: 4rem;
        background: #f8fafc;
        color: #94a3b8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        border: 1px solid #f1f5f9;
    }
    .empty-icon-wrap i {
        width: 2rem;
        height: 2rem;
    }
    .schedule-empty-state h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e293b;
    }
    .schedule-empty-state p {
        margin: 0.5rem 0 0 0;
        font-size: 0.9rem;
        color: #64748b;
        max-width: 320px;
        line-height: 1.5;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script id="dashboard-chart-data" type="application/json">@json($chartData)</script>
@endsection
