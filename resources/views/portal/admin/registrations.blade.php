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
@section('title', 'Registrations')
@section('page-title', 'Registrations Approval')
@section('content')
<x-ui.card title="Daftar Pendaftaran" subtitle="Semua submission dari form pendaftaran" data-searchable>
    @if ($registrations->isNotEmpty())
        <x-ui.table :headers="['Nama', 'Email', 'Mulai', 'Durasi', 'Status', 'Action']">
            @foreach($registrations as $registration)
                @php
                    $status = strtolower($registration->status ?? 'pending');
                    $badgeType = $status === 'accepted' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning');
                @endphp
                <tr>
                    <td>{{ $registration->full_name }}</td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ $registration->start_date ? \Carbon\Carbon::parse($registration->start_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $registration->duration_months ?? 0 }} Bulan</td>
                    <td><x-ui.badge :type="$badgeType">{{ strtoupper($status) }}</x-ui.badge></td>
                    <td>
                        @if ($status !== 'accepted')
                            <form class="module-form" method="POST" action="{{ route('admin.registrations.approve', $registration->id) }}" onsubmit="return confirm('Approve registrasi ini? Akun user dan student akan dibuat otomatis.');" style="margin-bottom: 0.5rem;">
                                @csrf
                                <button type="submit">Approve</button>
                            </form>
                        @endif

                        <form class="module-form" method="POST" action="{{ route('admin.registrations.status', $registration) }}">
                            @csrf
                            @method('PATCH')
                            <label>
                                <select name="status">
                                    <option value="pending" @selected($status === 'pending')>pending</option>
                                    <option value="accepted" @selected($status === 'accepted')>accepted</option>
                                    <option value="rejected" @selected($status === 'rejected')>rejected</option>
                                </select>
                            </label>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    @else
        <x-ui.empty-state title="No registrations yet" description="Data pendaftaran baru akan muncul setelah calon siswa submit formulir website." icon="clipboard-list" />
    @endif
</x-ui.card>

<section class="card" data-searchable>
    <x-ui.badge type="info">TIP</x-ui.badge>
    <p style="margin-top: 0.5rem;">Status <strong>accepted</strong> akan otomatis membuat atau memperbarui data siswa aktif.</p>
</section>
@endsection
