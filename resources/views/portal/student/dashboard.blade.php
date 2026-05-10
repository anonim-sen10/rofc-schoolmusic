@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Student Dashboard')
@section('page-title','Student Dashboard')
@section('page-subtitle', 'ROFC Private Music Management Information System - Student Workspace')
@section('content')
<section class="kpi-grid" data-searchable>
    <div class="card card-loading">
        <div class="kpi-row">
            <div>
                <span class="eyebrow">Total Sessions</span>
                <div class="kpi-value">{{ $totalSessions }}</div>
            </div>
            <span class="kpi-icon"><i data-lucide="calendar"></i></span>
        </div>
    </div>
    <div class="card card-loading">
        <div class="kpi-row">
            <div>
                <span class="eyebrow">Present</span>
                <div class="kpi-value" style="color: var(--success);">{{ $totalPresent }}</div>
            </div>
            <span class="kpi-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);"><i data-lucide="user-check"></i></span>
        </div>
    </div>
    <div class="card card-loading">
        <div class="kpi-row">
            <div>
                <span class="eyebrow">Absent</span>
                <div class="kpi-value" style="color: var(--danger);">{{ $totalAbsent }}</div>
            </div>
            <span class="kpi-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);"><i data-lucide="user-x"></i></span>
        </div>
    </div>
    <div class="card card-loading">
        <div class="kpi-row">
            <div>
                <span class="eyebrow">Status</span>
                <div class="kpi-value">ACTIVE</div>
            </div>
            <span class="kpi-icon"><i data-lucide="shield-check"></i></span>
        </div>
    </div>
</section>

<section class="split-grid-sa" data-searchable>
    <div class="card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">My Schedule</h3>
                <p class="ui-card-subtitle">Jadwal kelas yang Anda ikuti</p>
            </div>
            <a href="{{ route('student.schedule.index') }}" class="btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Lihat Semua</a>
        </div>
        
        @if($schedules->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Schedule</th>
                            <th>Class</th>
                            <th>Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $sched)
                            <tr>
                                <td><strong>{{ $sched->session_date->translatedFormat('l, d M Y') }}</strong> <br><small class="muted">{{ \Carbon\Carbon::parse($sched->time)->format('H:i') }}</small></td>
                                <td>{{ $sched->musicClass->name ?? '-' }}</td>
                                <td>{{ $sched->teacher->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i data-lucide="calendar-x"></i></div>
                <h4>No schedule found</h4>
                <p>Belum ada jadwal yang di-assign untuk Anda.</p>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">Recent Attendance</h3>
                <p class="ui-card-subtitle">Riwayat kehadiran sesi latihan Anda</p>
            </div>
        </div>
        
        @if($attendances->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $att)
                            <tr>
                                <td>{{ $att->created_at->format('d M Y') }}</td>
                                <td>{{ $att->schedule ? \Carbon\Carbon::parse($att->schedule->time)->format('H:i') : '-' }}</td>
                                <td>
                                    @php 
                                        $status = strtolower($att->status);
                                        $badgeClass = match($status) {
                                            'present' => 'ui-badge-success',
                                            'absent' => 'ui-badge-danger',
                                            'late' => 'ui-badge-warning',
                                            default => 'ui-badge-neutral'
                                        };
                                    @endphp
                                    <span class="ui-badge {{ $badgeClass }}">
                                        {{ strtoupper($status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i data-lucide="clipboard-x"></i></div>
                <h4>No attendance records</h4>
                <p>Belum ada data absensi tercatat.</p>
            </div>
        @endif
    </div>
</section>

<section class="split-grid-sa" style="margin-top: 1rem;" data-searchable>
    <div class="card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">Payment</h3>
                <p class="ui-card-subtitle">Informasi pembayaran SPP</p>
            </div>
        </div>
        <div class="empty-state">
            <div class="empty-state-icon"><i data-lucide="wallet"></i></div>
            <h4>Payment History</h4>
            <p>Fitur pembayaran akan segera hadir di portal siswa.</p>
        </div>
    </div>

    <div class="card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">Progress Updates</h3>
                <p class="ui-card-subtitle">Catatan perkembangan belajar dari guru</p>
            </div>
        </div>
        <div class="empty-state">
            <div class="empty-state-icon"><i data-lucide="activity"></i></div>
            <h4>No progress yet</h4>
            <p>Belum ada catatan perkembangan dari guru pengajar.</p>
        </div>
    </div>
</section>

<style>
    /* Reuse styles from admin attendance if possible, otherwise define here */
    .att-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .att-table th { text-align: left; padding: 0.75rem; background: #f1f5f9; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; }
    .att-table td { padding: 0.75rem; border-bottom: 1px solid #f1f5f9; color: #334155; }
    
    .att-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
    .att-badge-present { background: #dcfce7; color: #166534; }
    .att-badge-absent  { background: #fee2e2; color: #991b1b; }
    .att-badge-reschedule { background: #ffedd5; color: #9a3412; }
</style>
@endsection