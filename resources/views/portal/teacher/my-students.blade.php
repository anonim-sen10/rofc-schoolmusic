@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'Attendance', 'url' => route('teacher.attendance.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Schedule', 'url' => route('teacher.schedule.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'My Students')
@section('page-title', 'My Students')
@section('page-subtitle', 'Daftar siswa yang terhubung dengan kelas teacher login.')

@section('content')
    @if ($selectedStudent)
        <section class="card" style="margin-bottom:0.8rem;">
            <h3>Detail Siswa</h3>
            <ul class="insight-list">
                <li>
                    <span>Nama</span>
                    <strong>{{ $selectedStudent->name }}</strong>
                </li>
                <li>
                    <span>Kelas</span>
                    <strong>{{ $selectedStudent->classes->pluck('name')->join(', ') ?: '-' }}</strong>
                </li>
                <li>
                    <span>Status</span>
                    <span class="ui-badge {{ $selectedStudent->is_active ? 'ui-badge-success' : 'ui-badge-warning' }}">
                        {{ $selectedStudent->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </li>
            </ul>
        </section>
    @endif

    <section class="card">
        <h3>Students Table</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Student</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->classes->pluck('name')->join(', ') ?: '-' }}</td>
                            <td>
                                <span class="ui-badge {{ $student->is_active ? 'ui-badge-success' : 'ui-badge-warning' }}">
                                    {{ $student->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="hero-actions">
                                    <a class="ghost-btn" href="{{ route('teacher.my-students.index', ['student_id' => $student->id]) }}">Lihat Detail</a>
                                    <a class="ghost-btn" href="{{ route('teacher.student-progress.input', ['student_id' => $student->id]) }}">Input Progress</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada siswa untuk kelas teacher ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
