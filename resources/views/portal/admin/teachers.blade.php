@php
$menuItems = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Classes', 'url' => route('admin.classes.index')],
    ['label' => 'Teachers', 'url' => route('admin.teachers.index')],
    ['label' => 'Students', 'url' => route('admin.students.index')],
    ['label' => 'Registrations', 'url' => route('admin.registrations.index')],
    ['label' => 'Schedule', 'url' => route('admin.schedule.index')],
];
$panelTitle = 'Admin Dashboard';
$homeRoute = route('admin.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'Teachers Management')
@section('page-title', 'Teachers Management')
@section('content')
<section class="card">
    <p>Role Admin hanya dapat melihat data guru. Pembuatan akun teacher dilakukan di portal Super Admin.</p>
</section>

<section class="card">
    <h3>Daftar Guru</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                    <th>Instrument</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->user?->email ?? '-' }}</td>
                    <td>{{ $teacher->phone ?? '-' }}</td>
                    <td>{{ $teacher->gender ?? '-' }}</td>
                    <td>{{ $teacher->religion ?? '-' }}</td>
                    <td>{{ $teacher->instrument }}</td>
                    <td>{{ $teacher->is_active ? 'active' : 'inactive' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada data guru.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
