@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard'), 'key' => 'dashboard'],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index'), 'key' => 'my_classes'],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index'), 'key' => 'my_schedule'],
        ['label' => 'Attendance', 'url' => route('teacher.attendance.index'), 'key' => 'attendance'],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index'), 'key' => 'student_progress'],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index'), 'key' => 'my_students'],
        ['label' => 'Materials', 'url' => route('teacher.materials.index'), 'key' => 'materials'],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'My Classes')
@section('page-title', 'My Classes')
@section('page-subtitle', 'Kelas yang telah di-assign ke Anda oleh Admin.')

@section('content')
    <section class="card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">Daftar Kelas</h3>
                <p class="ui-card-subtitle">Kelas yang tersedia untuk Anda</p>
            </div>
        </div>

        @if($classes->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Siswa</th>
                            <th>Jadwal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $class)
                            <tr>
                                <td>
                                    <span class="class-name">{{ $class->name }}</span>
                                </td>
                                <td>
                                    <span class="class-description">{{ $class->description ?: '-' }}</span>
                                </td>
                                <td>
                                    <span class="class-price">Rp{{ number_format($class->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($class->status === 'active')
                                        <span class="ui-badge ui-badge-success">
                                            <i data-lucide="check-circle"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="ui-badge ui-badge-neutral">
                                            <i data-lucide="x-circle"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="count-badge">
                                        <i data-lucide="users"></i>
                                        {{ $class->students_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="count-badge">
                                        <i data-lucide="calendar"></i>
                                        {{ $class->schedules_count ?? 0 }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i data-lucide="book-open"></i>
                </div>
                <h4>No classes assigned yet</h4>
                <p>Anda belum memiliki kelas yang di-assign. Hubungi Admin untuk informasi lebih lanjut.</p>
            </div>
        @endif
    </section>

    <style>
        .class-name {
            font-weight: 600;
            color: #0f172a;
        }

        .class-description {
            color: #64748b;
            font-size: 0.875rem;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .class-price {
            font-weight: 600;
            color: #059669;
        }

        .count-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.5rem;
            background: #f1f5f9;
            color: #475569;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .count-badge i {
            width: 0.875rem;
            height: 0.875rem;
        }

        .ui-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .ui-badge i {
            width: 0.75rem;
            height: 0.75rem;
        }

        @media (max-width: 768px) {
            .class-description {
                max-width: 150px;
            }
        }
    </style>
@endsection
