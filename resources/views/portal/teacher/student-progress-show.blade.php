@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Attendance', 'url' => route('teacher.attendance.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'Student Progress')
@section('page-title', 'Student Progress')
@section('page-subtitle', 'Input progress untuk siswa yang dipilih dari halaman My Students.')

@section('content')
    <div class="split-grid">
        <section class="card">
            <h3>Input Progress</h3>

            <form class="module-form" method="POST" action="{{ route('teacher.student-progress.store') }}">
                @csrf

                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <label>
                    Class
                    <select name="class_id" required>
                        @foreach ($student->classes as $class)
                            <option value="{{ $class->id }}" @selected((int) old('class_id', $selectedClassId) === (int) $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label>
                    Student
                    <input type="text" value="{{ $student->name }}" readonly>
                </label>

                <label>
                    Topic
                    <input type="text" name="topic" value="{{ old('topic') }}" required>
                </label>

                <label>
                    Score
                    <input type="number" name="score" min="0" max="100" value="{{ old('score') }}">
                </label>

                <label>
                    Recorded At
                    <input type="date" name="recorded_at" value="{{ old('recorded_at', now()->format('Y-m-d')) }}" required>
                </label>

                <label>
                    Note
                    <textarea name="note">{{ old('note') }}</textarea>
                </label>

                <button type="submit">Simpan Progress</button>
            </form>
        </section>

        <section class="card">
            <h3>Recent Progress</h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Topic</th>
                            <th>Score</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentProgress as $progress)
                            <tr>
                                <td>{{ optional($progress->recorded_at)->format('Y-m-d') ?: '-' }}</td>
                                <td>{{ $progress->topic ?: '-' }}</td>
                                <td>{{ $progress->score ?? '-' }}</td>
                                <td>{{ $progress->note ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada progress.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
