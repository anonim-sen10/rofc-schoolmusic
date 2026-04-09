@php
$menuItems = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Classes', 'url' => route('admin.classes.index')],
    ['label' => 'Teachers', 'url' => route('admin.teachers.index')],
    ['label' => 'Students', 'url' => route('admin.students.index')],
    ['label' => 'Registrations', 'url' => route('admin.registrations.index')],
    ['label' => 'Schedule', 'url' => route('admin.schedule.index')],
    ['label' => 'Gallery', 'url' => route('admin.module', ['module' => 'gallery'])],
    ['label' => 'Blog', 'url' => route('admin.module', ['module' => 'blog'])],
    ['label' => 'Events', 'url' => route('admin.module', ['module' => 'events'])],
    ['label' => 'Testimonials', 'url' => route('admin.module', ['module' => 'testimonials'])],
];
$panelTitle = 'Admin Dashboard';
$homeRoute = route('admin.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'Classes Management')
@section('page-title', 'Classes Management')
@section('content')
<section class="dashboard-hero" data-searchable>
    <div>
        <p class="eyebrow">Academic Operation</p>
        <h2>Classes Management</h2>
        <p>Kelola kelas aktif, assignment guru, dan status operasional kelas dari satu halaman.</p>
    </div>
    <div class="hero-actions">
        <a href="{{ route('admin.teachers.index') }}" class="ghost-btn">See Teachers</a>
        <a href="{{ route('admin.schedule.index') }}" class="ghost-btn">Open Schedule</a>
    </div>
</section>

<div class="split-grid-sa" data-searchable>
    <x-ui.card title="Tambah Kelas" subtitle="Buat kelas baru untuk operasional akademik">
        <form class="module-form module-form-grid" method="POST" action="{{ route('admin.classes.store') }}">
            @csrf
            <label>Nama Kelas <input type="text" name="name" required></label>
            <label>Deskripsi <textarea name="description" rows="3"></textarea></label>
            <label>Harga <input type="number" step="0.01" name="price" required></label>
            <label>Jadwal <input type="text" name="schedule"></label>
            <label>Teacher
                <select name="teacher_id"><option value="">-</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach</select>
            </label>
            <label>Status
                <select name="status"><option value="active">active</option><option value="inactive">inactive</option></select>
            </label>
            <div class="form-actions">
                <button type="submit">Simpan</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Daftar Kelas" subtitle="Kelas yang sudah terdaftar di sistem">
        @if ($classList->isNotEmpty())
            <x-ui.table :headers="['Nama', 'Teacher', 'Harga', 'Status', 'Action']">
                @foreach($classList as $classItem)
                    <tr>
                        <td>{{ $classItem->name }}</td>
                        <td>{{ $classItem->teacher?->name ?? '-' }}</td>
                        <td>Rp{{ number_format($classItem->price, 0, ',', '.') }}</td>
                        <td>
                            <x-ui.badge :type="$classItem->status === 'active' ? 'success' : 'warning'">
                                {{ strtoupper($classItem->status) }}
                            </x-ui.badge>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.classes.destroy', $classItem) }}" onsubmit="return confirm('Hapus class ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state title="No class data yet" description="Buat kelas baru untuk mulai mengelola jadwal akademik." icon="book-open" />
        @endif
    </x-ui.card>
</div>
@endsection
