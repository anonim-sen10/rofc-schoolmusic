@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Attendance', 'url' => route('teacher.attendance.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title','Teacher Dashboard')
@section('page-title','Teacher Dashboard')
@section('page-subtitle', 'ROFC Private Music Management Information System')
@section('content')

{{-- Today's Schedule Section --}}
<section class="card">
    <div class="ui-card-header">
        <div>
            <h3 class="ui-card-title">Today's Schedule</h3>
            <p class="ui-card-subtitle">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    @if($todaySchedules->isNotEmpty())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todaySchedules as $schedule)
                        <tr>
                            <td>
                                <span class="time-badge">{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}</span>
                            </td>
                            <td>
                                <div class="student-info">
                                    <strong>{{ $schedule->student?->name ?: 'N/A' }}</strong>
                                </div>
                            </td>
                            <td>{{ $schedule->musicClass?->name ?: 'N/A' }}</td>
                            <td>
                                <span class="address-text">{{ $schedule->student?->address ?: '-' }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('teacher.attendance.index') }}" class="action-btn action-btn--primary">
                                        <i data-lucide="user-check"></i>
                                        <span>Mark Attendance</span>
                                    </a>
                                    <a href="{{ route('teacher.student-progress.input', $schedule->student_id) }}" class="action-btn action-btn--secondary">
                                        <i data-lucide="pencil-line"></i>
                                        <span>Add Progress</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i data-lucide="calendar-x"></i>
            </div>
            <h4>No schedule for today</h4>
            <p>You don't have any scheduled lessons for today. Check back tomorrow!</p>
        </div>
    @endif
</section>

{{-- Pending Reschedule Requests Section --}}
@if($pendingRescheduleRequests->isNotEmpty())
<section class="card" style="border-left: 4px solid #eab308; margin-top: 1.5rem;">
    <div class="ui-card-header">
        <div>
            <h3 class="ui-card-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="refresh-cw" class="w-5 h-5 text-yellow-600"></i>
                Reschedule Requests
            </h3>
            <p class="ui-card-subtitle">Permintaan pindah jadwal dari siswa Anda yang menunggu persetujuan.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 0.75rem; background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.025em; border-bottom: 1px solid #e2e8f0;">Student</th>
                    <th style="text-align: left; padding: 0.75rem; background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.025em; border-bottom: 1px solid #e2e8f0;">Old Slot</th>
                    <th style="text-align: left; padding: 0.75rem; background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.025em; border-bottom: 1px solid #e2e8f0;">New Slot</th>
                    <th style="text-align: left; padding: 0.75rem; background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.025em; border-bottom: 1px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRescheduleRequests as $request)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem;"><strong>{{ $request->student->name }}</strong></td>
                        <td style="padding: 0.75rem; font-size: 0.875rem; color: #475569;">{{ ucfirst($request->oldSchedule->day) }} {{ substr($request->oldSchedule->time, 0, 5) }}</td>
                        <td style="padding: 0.75rem; font-size: 0.875rem; color: #475569;">{{ ucfirst($request->newSchedule->day) }} {{ substr($request->newSchedule->time, 0, 5) }}</td>
                        <td style="padding: 0.75rem;">
                            <span style="background-color: #fef9c3; color: #854d0e; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 9999px; font-weight: 600; white-space: nowrap;">
                                Pending Admin Approval
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

{{-- Quick Stats (Optional, minimal) --}}
@if($todaySchedules->isNotEmpty())
<section class="quick-stats">
    <div class="stat-item">
        <span class="stat-label">Total Lessons Today</span>
        <span class="stat-value">{{ $todaySchedules->count() }}</span>
    </div>
    <div class="stat-item">
        <span class="stat-label">Completed</span>
        <span class="stat-value">{{ $completedCount }}</span>
    </div>
    <div class="stat-item">
        <span class="stat-label">Pending</span>
        <span class="stat-value">{{ $todaySchedules->count() - $completedCount }}</span>
    </div>
</section>
@endif

<style>
.time-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
}

.student-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.student-info strong {
    color: #0f172a;
    font-weight: 600;
}

.address-text {
    color: #64748b;
    font-size: 0.875rem;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.8125rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-btn i {
    width: 0.875rem;
    height: 0.875rem;
}

.action-btn--primary {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
    border: 1px solid transparent;
}

.action-btn--primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
}

.action-btn--secondary {
    background: #fff;
    color: #374151;
    border: 1px solid #e5e7eb;
}

.action-btn--secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.quick-stats {
    display: flex;
    gap: 1.5rem;
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(248, 250, 252, 0.5);
    border-radius: 0.75rem;
    border: 1px solid #f1f5f9;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 500;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .quick-stats {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>
@endsection
