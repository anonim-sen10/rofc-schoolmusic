@php
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

// Fetch requests
$requests = \App\Models\RescheduleRequest::with(['student', 'oldSchedule', 'newSchedule.teacher'])->latest()->get();
@endphp
@extends('portal.layouts.app')
@section('title', 'Reschedule Requests')
@section('page-title', 'Reschedule Approval')
@section('content')
<x-ui.card title="Daftar Reschedule" subtitle="Permintaan pindah jadwal dari siswa" data-searchable>
    @if ($requests->isNotEmpty())
        <x-ui.table :headers="['Student', 'Old Slot', 'New Slot', 'Teacher', 'Status', 'Action']">
            @foreach($requests as $req)
                @php
                    $status = strtolower($req->status);
                    $badgeType = $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning');
                @endphp
                <tr>
                    <td>{{ $req->student->name }}</td>
                    <td>{{ $req->oldSchedule->day }} {{ substr((string)$req->oldSchedule->time, 0, 5) }}</td>
                    <td>{{ $req->newSchedule->day }} {{ substr((string)$req->newSchedule->time, 0, 5) }}</td>
                    <td>{{ $req->newSchedule->teacher->name ?? '-' }}</td>
                    <td><x-ui.badge :type="$badgeType">{{ strtoupper($status) }}</x-ui.badge></td>
                    <td>
                        @if ($status === 'pending')
                            <div style="display:flex; gap:0.5rem;">
                                <form action="{{ route('admin.reschedule.approve', $req->id) }}" method="POST" onsubmit="return confirm('Approve reschedule ini?')">
                                    @csrf
                                    <button type="submit" class="btn-res-approve" title="Approve">
                                        <i data-lucide="check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reschedule.reject', $req->id) }}" method="POST" onsubmit="return confirm('Reject reschedule ini?')">
                                    @csrf
                                    <button type="submit" class="btn-res-reject" title="Reject">
                                        <i data-lucide="x"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-muted">No Actions</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    @else
        <x-ui.empty-state title="No reschedule requests" description="Belum ada permintaan reschedule dari siswa." icon="refresh-cw" />
    @endif
</x-ui.card>

<style>
    .btn-res-approve, .btn-res-reject {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 0;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-res-approve { background: rgba(34, 197, 94, 0.15); color: #86efac; }
    .btn-res-approve:hover { background: #166534; color: #fff; }
    .btn-res-reject { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }
    .btn-res-reject:hover { background: #991b1b; color: #fff; }
    .btn-res-approve i, .btn-res-reject i { width: 16px; height: 16px; }
    .text-muted { color: var(--muted); font-style: italic; font-size: 0.85rem; }
</style>
@endsection
