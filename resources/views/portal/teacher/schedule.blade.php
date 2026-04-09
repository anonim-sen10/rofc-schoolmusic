@php $menuItems=[['label'=>'Dashboard','url'=>route('teacher.dashboard')],['label'=>'Attendance','url'=>route('teacher.attendance.index')],['label'=>'Student Progress','url'=>route('teacher.student-progress.index')],['label'=>'Materials','url'=>route('teacher.materials.index')],['label'=>'My Classes','url'=>route('teacher.my-classes.index')],['label'=>'My Students','url'=>route('teacher.my-students.index')],['label'=>'Schedule','url'=>route('teacher.schedule.index')]]; $panelTitle='Teacher Portal'; $homeRoute=route('teacher.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Schedule')
@section('page-title','Schedule Confirmation')
@section('content')
<section class="card">
    <h3>Jadwal Class Saya</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Jadwal</th>
                    <th>Jumlah Siswa</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->name }}</td>
                        <td>{{ $schedule->schedule ?? '-' }}</td>
                        <td>{{ $schedule->students_count }}</td>
                        <td>
                            @if($schedule->students->isNotEmpty())
                                {{ $schedule->students->pluck('name')->sort()->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $schedule->assignment_status ?? 'pending' }}</td>
                        <td>
                            @if(($schedule->assignment_status ?? 'pending') === 'pending')
                                <form method="POST" action="{{ route('teacher.schedule.respond', $schedule) }}" style="display:inline-block; margin-right: 0.35rem;">
                                    @csrf
                                    <input type="hidden" name="action" value="accepted">
                                    <button type="submit">Terima</button>
                                </form>
                                <form method="POST" action="{{ route('teacher.schedule.respond', $schedule) }}" style="display:inline-block;">
                                    @csrf
                                    <input type="hidden" name="action" value="rejected">
                                    <input type="text" name="note" placeholder="Alasan tolak (opsional)">
                                    <button type="submit">Tolak</button>
                                </form>
                            @else
                                {{ $schedule->assignment_note ?? '-' }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada jadwal class yang di-assign.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
