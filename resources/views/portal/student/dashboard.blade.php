@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Student Dashboard')
@section('page-title','Student Dashboard')
@section('page-subtitle', 'ROFC Private Music Management Information System - Student Workspace')
@section('content')
<section class="kpi-grid" data-searchable>
	<x-ui.card title="Total Sessions">
		<div class="kpi-row">
			<div class="kpi-value">{{ $totalSessions }}</div>
			<span class="kpi-icon"><i data-lucide="calendar"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Total Present">
		<div class="kpi-row">
			<div class="kpi-value" style="color: #10b981;">{{ $totalPresent }}</div>
			<span class="kpi-icon"><i data-lucide="user-check"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Total Absent">
		<div class="kpi-row">
			<div class="kpi-value" style="color: #ef4444;">{{ $totalAbsent }}</div>
			<span class="kpi-icon"><i data-lucide="user-x"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Status">
		<div class="kpi-row">
			<div class="kpi-value">ACTIVE</div>
			<span class="kpi-icon"><i data-lucide="shield-check"></i></span>
		</div>
	</x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
	<x-ui.card title="My Schedule" subtitle="Jadwal kelas yang Anda ikuti">
        @if($schedules->isNotEmpty())
            <div class="table-wrap">
                <table class="att-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Class</th>
                            <th>Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $sched)
                            <tr>
                                <td>{{ $sched->day }}</td>
                                <td>{{ \Carbon\Carbon::parse($sched->time)->format('H:i') }}</td>
                                <td>{{ $sched->musicClass->name ?? '-' }}</td>
                                <td>{{ $sched->teacher->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <x-ui.empty-state title="No schedule found" description="Belum ada jadwal yang di-assign untuk Anda." icon="calendar-x" />
        @endif
	</x-ui.card>

	<x-ui.card title="Attendance History" subtitle="Riwayat kehadiran sesi latihan Anda">
        @if($attendances->isNotEmpty())
            <div class="table-wrap">
                <table class="att-table">
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
                                    @php $status = strtolower($att->status); @endphp
                                    <span class="att-badge att-badge-{{ $status }}">
                                        @if($status === 'present') ✔
                                        @elseif($status === 'absent') ✖
                                        @elseif($status === 'reschedule') ↻
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <x-ui.empty-state title="No attendance records" description="Belum ada data absensi tercatat." icon="clipboard-x" />
        @endif
	</x-ui.card>
</section>

<section class="split-grid-sa" style="margin-top: 1.5rem;" data-searchable>
    <x-ui.card title="Payment" subtitle="Informasi pembayaran SPP">
        <div style="padding: 1rem; text-align: center; color: #64748b; background: #f8fafc; border-radius: 0.5rem; border: 1px dashed #cbd5e1;">
            <i data-lucide="clock" style="width: 24px; height: 24px; margin-bottom: 0.5rem; display: inline-block;"></i>
            <p style="font-size: 0.9rem; font-weight: 500;">Payment Feature: Coming Soon</p>
        </div>
    </x-ui.card>

    <x-ui.card title="Progress" subtitle="Catatan perkembangan belajar">
        <div style="padding: 1rem; text-align: center; color: #64748b; background: #f8fafc; border-radius: 0.5rem; border: 1px dashed #cbd5e1;">
            <i data-lucide="trending-up" style="width: 24px; height: 24px; margin-bottom: 0.5rem; display: inline-block;"></i>
            <p style="font-size: 0.9rem; font-weight: 500;">Progress Notes: No data yet / Coming Soon</p>
        </div>
    </x-ui.card>
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