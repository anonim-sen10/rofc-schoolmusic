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
@endphp
@extends('portal.layouts.app')
@section('title', 'Teachers Management')
@section('page-title', 'Teachers Management')
@section('content')
<section class="card" data-searchable>
    <x-ui.badge type="info">INFO</x-ui.badge>
    <p style="margin-top: 0.5rem;">Role Admin hanya dapat melihat data guru. Pembuatan akun teacher dilakukan di portal Super Admin.</p>
</section>

<x-ui.card title="Daftar Guru" subtitle="Data pengajar aktif dan profil dasar" data-searchable>
    @if ($teachers->isNotEmpty())
        <x-ui.table :headers="['Nama', 'Email', 'Nomor HP', 'Jenis Kelamin', 'Agama', 'Instrument', 'Status']">
            @foreach($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->user?->email ?? '-' }}</td>
                    <td>{{ $teacher->phone ?? '-' }}</td>
                    <td>{{ $teacher->gender ?? '-' }}</td>
                    <td>{{ $teacher->religion ?? '-' }}</td>
                    <td>{{ $teacher->instrument ?? '-' }}</td>
                    <td>
                        <x-ui.badge :type="$teacher->is_active ? 'success' : 'warning'">
                            {{ $teacher->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </x-ui.badge>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    @else
        <x-ui.empty-state
            title="No teachers data yet"
            description="Data pengajar akan muncul di sini setelah akun guru dibuat oleh Super Admin."
            icon="music-2"
        />
    @endif
</x-ui.card>

<section class="split-grid-sa" data-searchable>
    <x-ui.card title="Quick Insight" subtitle="Ringkasan cepat untuk admin akademik">
        <ul class="insight-list">
            <li>
                <span><i data-lucide="users"></i>Total Teachers</span>
                <strong>{{ $teachers->count() }}</strong>
            </li>
            <li>
                <span><i data-lucide="user-check"></i>Active Teachers</span>
                <strong>{{ $teachers->where('is_active', true)->count() }}</strong>
            </li>
        </ul>
    </x-ui.card>

    <x-ui.card title="Action" subtitle="Menu terkait pengelolaan jadwal">
        <div class="quick-actions">
            <a href="{{ route('admin.schedule.index') }}">Manage Schedule</a>
            <a href="{{ route('admin.classes.index') }}">Open Classes</a>
        </div>
    </x-ui.card>
</section>
@endsection
