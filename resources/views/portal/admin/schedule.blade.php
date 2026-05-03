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
@section('title', 'Schedule Management')
@section('page-title', 'Schedule Management')
@section('content')
<div class="split-grid-sa" data-searchable>
    <x-ui.card title="Tentukan Pengajar per Class" subtitle="Assignment pengajar utama tiap kelas">
        <form class="module-form module-form-grid" method="POST" action="{{ route('admin.schedule.teacher') }}">
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
            <div class="form-actions">
                <button type="submit">Simpan Pengajar</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Tentukan Siswa per Class" subtitle="Attach siswa aktif ke kelas terkait">
        <form class="module-form module-form-grid" method="POST" action="{{ route('admin.schedule.students') }}">
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
            <div class="form-actions">
                <button type="submit">Tambah Siswa ke Class</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </x-ui.card>
</div>

<x-ui.card title="Ringkasan Schedule" subtitle="Status assignment terbaru per kelas" data-searchable>
    @if ($classList->isNotEmpty())
        <x-ui.table :headers="['Class', 'Pengajar', 'Status Jadwal', 'Jumlah Siswa', 'Daftar Siswa', 'Catatan Respon']">
            @foreach($classList as $classItem)
                @php
                    $assignmentStatus = strtolower($classItem->assignment_status ?? 'pending');
                    $assignmentBadge = $assignmentStatus === 'accepted' ? 'success' : ($assignmentStatus === 'rejected' ? 'danger' : 'warning');
                @endphp
                <tr>
                    <td>{{ $classItem->name }}</td>
                    <td>{{ $classItem->teacher?->name ?? '-' }}</td>
                    <td><x-ui.badge :type="$assignmentBadge">{{ strtoupper($assignmentStatus) }}</x-ui.badge></td>
                    <td>{{ $classItem->students->count() }}</td>
                    <td>{{ $classItem->students->pluck('name')->implode(', ') ?: '-' }}</td>
                    <td>{{ $classItem->assignment_note ?? '-' }}</td>
                </tr>
            @endforeach
        </x-ui.table>
    @else
        <x-ui.empty-state title="No schedule data yet" description="Buat kelas dan assignment guru terlebih dahulu agar ringkasan tampil." icon="calendar-days" />
    @endif
</x-ui.card>
@endsection
