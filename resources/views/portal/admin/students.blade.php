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
@section('title', 'Students Management')
@section('page-title', 'Students Management')
@section('content')
<section class="dashboard-hero" data-searchable>
    <div>
        <p class="eyebrow">Academic Operation</p>
        <h2>Students Management</h2>
        <p>Kelola data siswa aktif, assignment kelas, dan status akademik secara terpusat.</p>
    </div>
    <div class="hero-actions">
        <a href="{{ route('admin.classes.index') }}" class="ghost-btn">Open Classes</a>
        <a href="{{ route('admin.registrations.index') }}" class="ghost-btn">Registrations</a>
    </div>
</section>

<div class="split-grid-sa" data-searchable>
    <x-ui.card title="Tambah Siswa" subtitle="Registrasi manual siswa baru">
        <form class="module-form module-form-grid" method="POST" action="{{ route('admin.students.store') }}">
            @csrf
            <label>Nama <input type="text" name="name" required></label>
            <label>Umur <input type="number" name="age"></label>
            <label>Phone <input type="text" name="phone"></label>
            <label>Email <input type="email" name="email"></label>
            <label>Address <textarea name="address" rows="2"></textarea></label>
            <label>Kelas
                <select multiple name="class_ids[]">@foreach($classList as $classItem)<option value="{{ $classItem->id }}">{{ $classItem->name }}</option>@endforeach</select>
            </label>
            <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
            <div class="form-actions">
                <button type="submit">Simpan</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Daftar Siswa" subtitle="Data siswa dan assignment kelas">
        @if ($students->isNotEmpty())
            <x-ui.table :headers="['Nama', 'Email', 'Kelas', 'Status', 'Action']">
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email ?? '-' }}</td>
                        <td>{{ $student->classes->pluck('name')->join(', ') ?: '-' }}</td>
                        <td>
                            <x-ui.badge :type="$student->is_active ? 'success' : 'warning'">
                                {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                            </x-ui.badge>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state title="No student data yet" description="Tambahkan siswa baru atau terima pendaftaran dari menu Registrations." icon="graduation-cap" />
        @endif
    </x-ui.card>
</div>
@endsection
