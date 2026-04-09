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
@section('title', 'Schedule Management')
@section('page-title', 'Schedule Management')
@section('content')
<div class="split-grid">
    <section class="card">
        <h3>Tentukan Pengajar per Class</h3>
        <form class="module-form" method="POST" action="{{ route('admin.schedule.teacher') }}">
            @csrf
            <label>Class
                <select name="class_id" required>
                    <option value="">Pilih class</option>
                    @foreach($classList as $classItem)
                        <option value="{{ $classItem->id }}">{{ $classItem->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Pengajar
                <select name="teacher_id" required>
                    <option value="">Pilih pengajar</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit">Simpan Pengajar</button>
        </form>
    </section>
    <section class="card">
        <h3>Tentukan Siswa per Class</h3>
        <form class="module-form" method="POST" action="{{ route('admin.schedule.students') }}">
            @csrf
            <label>Class
                <select name="class_id" required>
                    <option value="">Pilih class</option>
                    @foreach($classList as $classItem)
                        <option value="{{ $classItem->id }}">{{ $classItem->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Siswa (boleh lebih dari satu)
                <select name="student_ids[]" multiple required size="8">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email ?? '-' }})</option>
                    @endforeach
                </select>
            </label>
            <button type="submit">Tambah Siswa ke Class</button>
        </form>
    </section>
</div>

<section class="card">
    <h3>Ringkasan Schedule</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Pengajar</th>
                    <th>Jumlah Siswa</th>
                    <th>Daftar Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classList as $classItem)
                    <tr>
                        <td>{{ $classItem->name }}</td>
                        <td>{{ $classItem->teacher?->name ?? '-' }}</td>
                        <td>{{ $classItem->students->count() }}</td>
                        <td>{{ $classItem->students->pluck('name')->implode(', ') ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada class.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
