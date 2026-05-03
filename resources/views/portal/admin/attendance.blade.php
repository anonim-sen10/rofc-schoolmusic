@php
$prefix = $routePrefix ?? 'admin';
$isSuperAdmin = $prefix === 'super-admin';

if ($isSuperAdmin) {
    // Super Admin uses the portal.layout with $portal menu; we'll set the same sidebar as admin
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('super-admin.dashboard')],
        ['label' => 'Users', 'url' => route('super-admin.module', ['module' => 'users'])],
        ['label' => 'Classes', 'url' => route('super-admin.module', ['module' => 'classes'])],
        ['label' => 'Teachers', 'url' => route('super-admin.module', ['module' => 'teachers'])],
        ['label' => 'Students', 'url' => route('super-admin.module', ['module' => 'students'])],
        ['label' => 'Registrations', 'url' => route('super-admin.module', ['module' => 'registrations'])],
        ['label' => 'Schedule', 'url' => route('super-admin.module', ['module' => 'schedule'])],
        ['label' => 'Attendance Monitoring', 'url' => route('super-admin.attendance.index'), 'icon' => 'check-circle'],
        ['label' => 'Reschedule Requests', 'url' => route('super-admin.module', ['module' => 'reschedule']), 'icon' => 'refresh-cw'],
        ['label' => 'Finance', 'url' => route('super-admin.module', ['module' => 'finance'])],
        ['label' => 'Reports', 'url' => route('super-admin.module', ['module' => 'reports'])],
        ['label' => 'Blog', 'url' => route('super-admin.module', ['module' => 'blog'])],
        ['label' => 'Gallery', 'url' => route('super-admin.module', ['module' => 'gallery'])],
        ['label' => 'Events', 'url' => route('super-admin.module', ['module' => 'events'])],
        ['label' => 'Testimonials', 'url' => route('super-admin.module', ['module' => 'testimonials'])],
        ['label' => 'Settings', 'url' => route('super-admin.module', ['module' => 'settings'])],
        ['label' => 'Logs', 'url' => route('super-admin.module', ['module' => 'logs'])],
    ];
    $panelTitle = 'Super Admin Dashboard';
    $homeRoute = route('super-admin.dashboard');
} else {
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Classes', 'url' => route('admin.classes.index')],
        ['label' => 'Teachers', 'url' => route('admin.teachers.index')],
        ['label' => 'Students', 'url' => route('admin.students.index')],
        ['label' => 'Registrations', 'url' => route('admin.registrations.index')],
        ['label' => 'Schedule', 'url' => route('admin.schedule.index')],
        ['label' => 'Attendance Monitoring', 'url' => route('admin.attendance.index'), 'icon' => 'check-circle'],
        ['label' => 'Reschedule Requests', 'url' => route('admin.module', ['module' => 'reschedule']), 'icon' => 'refresh-cw'],
        ['label' => 'Gallery', 'url' => route('admin.module', ['module' => 'gallery'])],
        ['label' => 'Blog', 'url' => route('admin.module', ['module' => 'blog'])],
        ['label' => 'Events', 'url' => route('admin.module', ['module' => 'events'])],
        ['label' => 'Testimonials', 'url' => route('admin.module', ['module' => 'testimonials'])],
    ];
    $panelTitle = 'Admin Dashboard';
    $homeRoute = route('admin.dashboard');
}

$attendanceRoute = $isSuperAdmin ? route('super-admin.attendance.index') : route('admin.attendance.index');
@endphp
@extends('portal.layouts.app')
@section('title', 'Attendance Monitoring')
@section('page-title', 'Attendance Monitoring')
@section('content')

<style>
    .att-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }
    .att-filters .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .att-filters label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .att-filters select,
    .att-filters input[type="date"] {
        padding: 0.5rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        background: white;
        font-size: 0.9rem;
        min-width: 180px;
        color: #0f172a;
    }
    .att-filters select:focus,
    .att-filters input[type="date"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
    }
    .att-filters .btn-filter {
        background: #0f172a;
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 0.375rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
    }
    .att-filters .btn-filter:hover {
        background: #1e293b;
    }
    .att-filters .btn-reset {
        background: transparent;
        color: #64748b;
        border: 1px solid #cbd5e1;
        padding: 0.5rem 1.25rem;
        border-radius: 0.375rem;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
    }
    .att-filters .btn-reset:hover {
        background: #f1f5f9;
        color: #334155;
    }

    /* Status badges */
    .att-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .att-badge-present { background: #dcfce7; color: #166534; }
    .att-badge-absent  { background: #fee2e2; color: #991b1b; }
    .att-badge-reschedule { background: #ffedd5; color: #9a3412; }

    /* Map button */
    .btn-map {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.3rem 0.7rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-map:hover { background: #2563eb; }

    /* Table improvements */
    .att-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .att-table th {
        text-align: left;
        padding: 0.75rem;
        background: #f1f5f9;
        border-bottom: 2px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    .att-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .att-table tr:hover td { background: #f8fafc; }

    /* Empty state */
    .att-empty {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }
    .att-empty i { margin-bottom: 0.75rem; }
    .att-empty p { font-size: 0.95rem; }

    /* Summary cards */
    .att-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .att-summary-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem 1.25rem;
        text-align: center;
    }
    .att-summary-card .count {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }
    .att-summary-card .label {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    .att-summary-card.present  .count { color: #166534; }
    .att-summary-card.absent   .count { color: #991b1b; }
    .att-summary-card.resc     .count { color: #9a3412; }

    /* Note tooltip */
    .note-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: help;
    }

    /* Pagination */
    .att-pagination {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }
    .att-pagination nav { display: flex; gap: 0.25rem; align-items: center; }
    .att-pagination nav a,
    .att-pagination nav span {
        display: inline-block;
        padding: 0.4rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        font-size: 0.85rem;
        text-decoration: none;
        color: #475569;
        transition: all 0.15s;
    }
    .att-pagination nav a:hover { background: #f1f5f9; }
    .att-pagination nav span[aria-current] { background: #0f172a; color: white; border-color: #0f172a; }
    .att-pagination nav span.disabled { opacity: 0.4; cursor: not-allowed; }
</style>

<section class="card">
    <h3 style="margin-bottom: 1rem;">Attendance Monitoring</h3>

    <!-- FILTERS -->
    <form method="GET" action="{{ $attendanceRoute }}" class="att-filters">
        <div class="filter-group">
            <label for="att-date">Date</label>
            <input type="date" id="att-date" name="date" value="{{ $date ?? now()->toDateString() }}">
        </div>
        <div class="filter-group">
            <label for="att-teacher">Teacher</label>
            <select id="att-teacher" name="teacher_id">
                <option value="">All Teachers</option>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}" @selected(($teacherId ?? '') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="att-class">Class</label>
            <select id="att-class" name="class_id">
                <option value="">All Classes</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" @selected(($classId ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group" style="flex-direction: row; gap: 0.5rem;">
            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ $attendanceRoute }}" class="btn-reset">Reset</a>
        </div>
    </form>

    <!-- SUMMARY CARDS -->
    @php
        $totalCount   = $attendances->total();
        $presentCount = $attendances->getCollection()->where('status', 'present')->count();
        $absentCount  = $attendances->getCollection()->where('status', 'absent')->count();
        $rescCount    = $attendances->getCollection()->where('status', 'reschedule')->count();
    @endphp
    <div class="att-summary">
        <div class="att-summary-card">
            <div class="count">{{ $totalCount }}</div>
            <div class="label">Total Records</div>
        </div>
        <div class="att-summary-card present">
            <div class="count">{{ $presentCount }}</div>
            <div class="label">✔ Present</div>
        </div>
        <div class="att-summary-card absent">
            <div class="count">{{ $absentCount }}</div>
            <div class="label">✖ Absent</div>
        </div>
        <div class="att-summary-card resc">
            <div class="count">{{ $rescCount }}</div>
            <div class="label">↻ Reschedule</div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrap">
        @if($attendances->count() > 0)
            <table class="att-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Teacher</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $att)
                        <tr>
                            <td>{{ $att->created_at->format('d M Y') }}</td>
                            <td>{{ $att->schedule ? \Carbon\Carbon::parse($att->schedule->time)->format('H:i') : '-' }}</td>
                            <td>{{ $att->schedule->teacher->name ?? '-' }}</td>
                            <td>{{ $att->schedule->student->user->name ?? ($att->schedule->student->name ?? '-') }}</td>
                            <td>{{ $att->schedule->class->name ?? '-' }}</td>
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
                            <td>
                                @if($att->latitude && $att->longitude)
                                    <a href="https://www.google.com/maps?q={{ $att->latitude }},{{ $att->longitude }}"
                                       target="_blank" rel="noopener" class="btn-map">
                                        <i data-lucide="map-pin" style="width:14px;height:14px;"></i> View Map
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($att->note)
                                    <span class="note-preview" title="{{ $att->note }}">{{ $att->note }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($attendances->hasPages())
                <div class="att-pagination">
                    {{ $attendances->appends(request()->query())->links('pagination::simple-default') }}
                </div>
            @endif
        @else
            <div class="att-empty">
                <i data-lucide="clipboard-x" style="width:48px;height:48px;display:inline-block;"></i>
                <p>No attendance data available for the selected filters.</p>
            </div>
        @endif
    </div>
</section>
@endsection
