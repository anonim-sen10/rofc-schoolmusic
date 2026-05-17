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
        gap: 0.2rem;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        font-size: 0.725rem;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .att-badge-present { background: #dcfce7; color: #15803d; }
    .att-badge-absent  { background: #fee2e2; color: #b91c1c; }
    .att-badge-reschedule { background: #ffedd5; color: #c2410c; }

    /* Map button */
    .btn-map {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #eff6ff;
        color: #2563eb;
        border: none;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.725rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .btn-map:hover { background: #dbeafe; color: #1d4ed8; }

    /* Table improvements */
    .att-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; table-layout: fixed; }
    .att-table th {
        text-align: left;
        padding: 0.45rem 0.5rem;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        font-size: 0.725rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    .att-table td {
        padding: 0.45rem 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .att-table tr:hover td { background: #f8fafc; }

    /* Name text truncation to prevent row height bloating */
    .att-name-text {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
        font-weight: 500;
        color: #0f172a;
    }

    /* Note interactive tooltip container */
    .note-container {
        position: relative;
        display: inline-block;
    }
    
    .note-preview {
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        display: inline-block;
        color: #64748b;
        font-size: 0.775rem;
        transition: color 0.15s ease;
    }
    
    .note-container:hover .note-preview {
        color: #0f172a;
    }

    /* Premium SaaS CSS Tooltip */
    .note-tooltip {
        visibility: hidden;
        width: 260px;
        background-color: #0f172a;
        color: #ffffff;
        text-align: left;
        border-radius: 8px;
        padding: 0.65rem 0.85rem;
        position: absolute;
        z-index: 100;
        bottom: 130%;
        right: 0;
        opacity: 0;
        transition: opacity 0.15s ease, transform 0.15s ease, visibility 0.15s;
        transform: translateY(4px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        font-size: 0.725rem;
        line-height: 1.4;
        white-space: normal;
        word-wrap: break-word;
        pointer-events: none;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    /* Arrow pointing to right edge of note cell */
    .note-tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        right: 15px;
        border-width: 5px;
        border-style: solid;
        border-color: #0f172a transparent transparent transparent;
    }
    
    .note-container:hover .note-tooltip {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }

    /* Empty state */
    .att-empty {
        text-align: center;
        padding: 2.5rem;
        color: #94a3b8;
    }
    .att-empty i { margin-bottom: 0.5rem; }
    .att-empty p { font-size: 0.85rem; }

    /* Summary cards */
    .att-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .att-summary-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.75rem 1rem;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .att-summary-card .count {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }
    .att-summary-card .label {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.15rem;
        font-weight: 500;
    }
    .att-summary-card.present  .count { color: #15803d; }
    .att-summary-card.absent   .count { color: #b91c1c; }
    .att-summary-card.resc     .count { color: #c2410c; }

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

    /* Premium Action Buttons */
    .btn-action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
    }
    .btn-action-icon:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .btn-action-icon.detail:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .btn-action-icon.edit:hover {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }
    .btn-action-icon.delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* Premium Modal Styles */
    .att-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999999;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .att-modal.show {
        display: flex;
        opacity: 1;
    }
    .att-modal-content {
        background: white;
        border-radius: 0.75rem;
        width: 90%;
        max-width: 440px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        transform: scale(0.95);
        transition: transform 0.2s ease;
    }
    .att-modal.show .att-modal-content {
        transform: scale(1);
    }
    .att-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .att-modal-header h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
    }
    .att-modal-header .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #64748b;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        transition: color 0.15s;
    }
    .att-modal-header .btn-close:hover {
        color: #0f172a;
    }
    .att-modal-body {
        padding: 1.25rem;
        font-size: 0.825rem;
    }
    .att-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }
</style>

<section class="card">
    <h3 style="margin-bottom: 1rem;">Attendance Monitoring</h3>

    <!-- FILTERS -->
    <form method="GET" action="{{ $attendanceRoute }}" class="att-filters">
        <div class="filter-group">
            <label for="att-date">Date</label>
            <input type="date" id="att-date" name="date" value="{{ $date }}">
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
                        <th style="width: 80px;">Date</th>
                        <th style="width: 70px;">Time (Sch)</th>
                        <th style="width: 75px;">Recorded At</th>
                        <th style="width: 105px;">Teacher</th>
                        <th style="width: 105px;">Student</th>
                        <th style="width: 65px;">Class</th>
                        <th style="width: 80px;">Status</th>
                        <th style="width: 44px;">Proof</th>
                        <th style="width: 70px;">Location</th>
                        <th>Notes</th>
                        <th style="width: 90px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $att)
                        @php $status = strtolower($att->status); @endphp
                        <tr>
                            <td>{{ $att->created_at->format('d M Y') }}</td>
                            <td style="white-space: nowrap;">{{ $att->schedule ? \Carbon\Carbon::parse($att->schedule->time)->format('H:i') : '-' }}</td>
                            <td style="white-space: nowrap;"><small class="text-slate-500">{{ $att->created_at->format('H:i:s') }}</small></td>
                            <td>
                                <span class="att-name-text" title="{{ $att->teacher->name ?? '' }}">
                                    {{ $att->teacher->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="att-name-text" title="{{ $att->student->name ?? '' }}">
                                    {{ $att->student->name ?? '-' }}
                                </span>
                            </td>
                            <td style="white-space: nowrap; font-weight: 500; color: #475569;">{{ $att->class->name ?? '-' }}</td>
                            <td>
                                <span class="att-badge att-badge-{{ $status }}">
                                    @if($status === 'present') ✔
                                    @elseif($status === 'absent') ✖
                                    @elseif($status === 'reschedule') ↻
                                    @endif
                                    {{ $status }}
                                </span>
                            </td>
                            <td>
                                @if($att->image_path)
                                    <a href="{{ asset('storage/' . $att->image_path) }}" target="_blank" class="att-proof-thumb" style="display: inline-flex;">
                                        <img src="{{ asset('storage/' . $att->image_path) }}" alt="Proof" 
                                             style="width: 26px; height: 26px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0; transition: transform 0.15s ease;"
                                             onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                @else
                                    <span class="text-slate-300 text-[10px]">No Photo</span>
                                @endif
                            </td>
                            <td>
                                @if($att->latitude && $att->longitude)
                                    <a href="https://www.google.com/maps?q={{ $att->latitude }},{{ $att->longitude }}"
                                       target="_blank" rel="noopener" class="btn-map">
                                        <i data-lucide="map-pin" style="width:12px;height:12px;"></i> Map
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($att->note)
                                    <div class="note-container">
                                        <span class="note-preview">{{ $att->note }}</span>
                                        <div class="note-tooltip">{{ $att->note }}</div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 0.25rem; align-items: center; justify-content: center;">
                                    <!-- Detail Button -->
                                    <button type="button" onclick="openModal('modal-detail-{{ $att->id }}')" class="btn-action-icon detail" title="Detail Kehadiran">
                                        <i data-lucide="eye" style="width:13px;height:13px;"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button type="button" onclick="openModal('modal-edit-{{ $att->id }}')" class="btn-action-icon edit" title="Edit Absensi">
                                        <i data-lucide="edit-2" style="width:13px;height:13px;"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ $prefix === 'super-admin' ? route('super-admin.attendance.destroy', $att->id) : route('admin.attendance.destroy', $att->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan absensi ini? Tindakan ini tidak dapat dibatalkan.')" 
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-icon delete" title="Hapus Catatan">
                                            <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                        </button>
                                    </form>
                                </div>
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

<!-- MODALS OUTSIDE CARD & TABLE WRAP TO PREVENT STACKING CONTEXT ISSUES -->
@foreach($attendances as $att)
    @php $status = strtolower($att->status); @endphp
    <!-- DETAIL MODAL -->
    <div id="modal-detail-{{ $att->id }}" class="att-modal">
        <div class="att-modal-content" style="text-align: left;">
            <header class="registration-modal-header">
                <div class="registration-modal-header-left">
                    <span class="registration-modal-icon"><i data-lucide="clipboard-list"></i></span>
                    <div>
                        <h3>Detail Kehadiran</h3>
                        <p>Informasi lengkap data presensi kelas</p>
                    </div>
                </div>
                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="closeModal('modal-detail-{{ $att->id }}')">
                    <i data-lucide="x"></i>
                </button>
            </header>
            <div class="registration-modal-body" style="padding: 1.25rem;">
                <div class="registration-modal-summary" style="margin-bottom: 1.25rem;">
                    <div class="registration-modal-summary-left">
                        <div class="registration-modal-avatar" style="background: #eff6ff; color: #2563eb; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                            {{ strtoupper(substr($att->student->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <p>Siswa</p>
                            <p class="registration-modal-summary-name" style="font-weight: 600; color: #0f172a; margin: 0;">{{ $att->student->name ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="att-badge att-badge-{{ $status }}">
                        @if($status === 'present') ✔
                        @elseif($status === 'absent') ✖
                        @elseif($status === 'reschedule') ↻
                        @endif
                        {{ strtoupper($status) }}
                    </span>
                </div>
                
                <section class="registration-modal-grid">
                    <article>
                        <p>Tanggal</p>
                        <p>{{ $att->created_at->format('d M Y') }}</p>
                    </article>
                    <article>
                        <p>Waktu (Jadwal)</p>
                        <p>{{ $att->schedule ? \Carbon\Carbon::parse($att->schedule->time)->format('H:i') : '-' }}</p>
                    </article>
                    <article>
                        <p>Tercatat Pada</p>
                        <p>{{ $att->created_at->format('H:i:s') }}</p>
                    </article>
                    <article>
                        <p>Kelas</p>
                        <p>{{ $att->class->name ?? '-' }}</p>
                    </article>
                    <article class="registration-modal-item-full">
                        <p>Guru Pengajar</p>
                        <p style="font-weight: 600; color: #0f172a;">{{ $att->teacher->name ?? '-' }}</p>
                    </article>
                    
                    <article class="registration-modal-item-full">
                        <p>Bukti Foto</p>
                        <p>
                            @if($att->image_path)
                                <div style="margin-top: 0.35rem;">
                                    <a href="{{ asset('storage/' . $att->image_path) }}" target="_blank" style="display: inline-block;">
                                        <img src="{{ asset('storage/' . $att->image_path) }}" alt="Proof" 
                                             style="width: 140px; height: 95px; border-radius: 8px; object-fit: cover; border: 1px solid #cbd5e1; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    </a>
                                    <div style="margin-top: 0.25rem;">
                                        <a href="{{ asset('storage/' . $att->image_path) }}" target="_blank" style="color: #2563eb; text-decoration: none; font-size: 0.75rem; font-weight: 500;">
                                            Buka Foto Ukuran Penuh &rarr;
                                        </a>
                                    </div>
                                </div>
                            @else
                                <span style="color:#94a3b8; font-style:italic;">Tidak ada bukti foto</span>
                            @endif
                        </p>
                    </article>

                    <article class="registration-modal-item-full">
                        <p>Lokasi Presensi</p>
                        <p>
                            @if($att->latitude && $att->longitude)
                                <div style="margin-top: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <a href="https://www.google.com/maps?q={{ $att->latitude }},{{ $att->longitude }}"
                                       target="_blank" rel="noopener" class="btn-map" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; border-radius: 6px;">
                                        <i data-lucide="map-pin" style="width:13px;height:13px;"></i> Lihat di Google Maps
                                    </a>
                                    <span style="font-size:0.75rem; color:#64748b;">({{ $att->latitude }}, {{ $att->longitude }})</span>
                                </div>
                            @else
                                <span style="color:#94a3b8; font-style:italic;">Tidak ada data lokasi GPS</span>
                            @endif
                        </p>
                    </article>

                    <article class="registration-modal-item-full">
                        <p>Catatan</p>
                        <p class="text-wrap-normal" style="margin-top: 0.25rem; font-size:0.8rem; line-height: 1.4; background:#f8fafc; padding:0.55rem 0.75rem; border-radius:6px; border:1px solid #e2e8f0; color:#334155;">
                            {{ $att->note ?: '-' }}
                        </p>
                    </article>
                </section>
            </div>
            <footer class="registration-modal-footer">
                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="closeModal('modal-detail-{{ $att->id }}')">Tutup</button>
            </footer>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="modal-edit-{{ $att->id }}" class="att-modal">
        <div class="att-modal-content" style="text-align: left;">
            <form action="{{ $prefix === 'super-admin' ? route('super-admin.attendance.update', $att->id) : route('admin.attendance.update', $att->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="att-modal-header">
                    <h4>Edit Absensi</h4>
                    <button type="button" onclick="closeModal('modal-edit-{{ $att->id }}')" class="btn-close">&times;</button>
                </div>
                <div class="att-modal-body">
                    <div style="margin-bottom: 1rem; background: #eff6ff; padding: 0.6rem 0.8rem; border-radius: 6px; border: 1px solid #bfdbfe; font-size: 0.775rem; color: #1e40af; line-height: 1.4;">
                        Mengubah presensi kelas <strong>{{ $att->class->name ?? '-' }}</strong> untuk siswa <strong>{{ $att->student->name ?? '-' }}</strong>.
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; font-weight:600; font-size:0.7rem; text-transform:uppercase; color:#475569; margin-bottom:0.35rem;">Status Kehadiran</label>
                        <select name="status" style="width:100%; padding:0.45rem 0.6rem; border:1px solid #cbd5e1; border-radius:6px; font-size:0.85rem; color:#0f172a; outline:none; transition:border-color 0.15s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                            <option value="present" @selected($status === 'present')>Present (Hadir)</option>
                            <option value="absent" @selected($status === 'absent')>Absent (Alpa)</option>
                            <option value="reschedule" @selected($status === 'reschedule')>Reschedule</option>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; font-size:0.7rem; text-transform:uppercase; color:#475569; margin-bottom:0.35rem;">Catatan</label>
                        <textarea name="note" rows="3" style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:6px; font-size:0.85rem; color:#0f172a; outline:none; font-family:inherit; resize:vertical; transition:border-color 0.15s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">{{ $att->note }}</textarea>
                    </div>
                </div>
                <div class="att-modal-footer">
                    <button type="button" onclick="closeModal('modal-edit-{{ $att->id }}')" class="btn-reset" style="padding: 0.4rem 1rem; font-size: 0.8rem; border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn-filter" style="padding: 0.4rem 1rem; font-size: 0.8rem; border-radius: 6px; background:#0f172a; color:white; border:none; cursor:pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
            // Trigger reflow to start transition
            modal.offsetHeight;
            modal.classList.add('show');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 200);
        }
    }

    // Close modal on escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.att-modal.show');
            modals.forEach(m => closeModal(m.id));
        }
    });

    // Close modal on clicking outside the modal content box
    document.querySelectorAll('.att-modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    });
</script>
@endsection
