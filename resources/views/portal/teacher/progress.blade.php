@php $menuItems=[['label'=>'Dashboard','url'=>route('teacher.dashboard')],['label'=>'Attendance','url'=>route('teacher.attendance.index')],['label'=>'Student Progress','url'=>route('teacher.student-progress.index')],['label'=>'Materials','url'=>route('teacher.materials.index')],['label'=>'My Classes','url'=>route('teacher.my-classes.index')],['label'=>'My Students','url'=>route('teacher.my-students.index')],['label'=>'Schedule','url'=>route('teacher.schedule.index')]]; $panelTitle='Teacher Portal'; $homeRoute=route('teacher.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Student Progress')
@section('page-title','Student Progress Notes')
@section('content')
<div class="split-grid"><section class="card"><h3>Input Progress</h3><form class="module-form" method="POST" action="{{ route('teacher.student-progress.store') }}">@csrf
<label>Class<select name="class_id" required>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></label>
<label>Student<select name="student_id" required>@foreach($students as $student)<option value="{{ $student->id }}">{{ $student->name }}</option>@endforeach</select></label>
<label>Topic<input type="text" name="topic"></label>
<label>Score<input type="text" name="score"></label>
<label>Recorded At<input type="date" name="recorded_at" value="{{ now()->format('Y-m-d') }}"></label>
<label>Note<textarea name="note"></textarea></label>
<button type="submit">Simpan</button></form></section>
<section class="card"><h3>Recent Progress</h3><div class="table-wrap"><table><thead><tr><th>Date</th><th>Topic</th><th>Student ID</th><th>Score</th></tr></thead><tbody>@foreach($records as $row)<tr><td>{{ optional($row->recorded_at)->format('Y-m-d') }}</td><td>{{ $row->topic }}</td><td>{{ $row->student_id }}</td><td>{{ $row->score }}</td></tr>@endforeach</tbody></table></div></section></div>
@endsection