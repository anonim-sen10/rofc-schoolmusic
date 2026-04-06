@php $menuItems=[['label'=>'Dashboard','url'=>route('teacher.dashboard')],['label'=>'Attendance','url'=>route('teacher.attendance.index')],['label'=>'Student Progress','url'=>route('teacher.student-progress.index')],['label'=>'Materials','url'=>route('teacher.materials.index')],['label'=>'My Classes','url'=>route('teacher.my-classes.index')],['label'=>'My Students','url'=>route('teacher.my-students.index')],['label'=>'Schedule','url'=>route('teacher.schedule.index')]]; $panelTitle='Teacher Portal'; $homeRoute=route('teacher.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Materials')
@section('page-title','Materials Upload')
@section('content')
<div class="split-grid"><section class="card"><h3>Upload Material</h3><form class="module-form" method="POST" enctype="multipart/form-data" action="{{ route('teacher.materials.store') }}">@csrf
<label>Class<select name="class_id"><option value="">General</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></label>
<label>Title<input type="text" name="title" required></label>
<label>Description<textarea name="description"></textarea></label>
<label>File<input type="file" name="file" required></label>
<button type="submit">Upload</button></form></section>
<section class="card"><h3>My Materials</h3><div class="table-wrap"><table><thead><tr><th>Title</th><th>Class ID</th><th>File</th></tr></thead><tbody>@foreach($materials as $row)<tr><td>{{ $row->title }}</td><td>{{ $row->class_id ?? 'General' }}</td><td>@if($row->file_path)<a href="{{ asset('storage/'.$row->file_path) }}" target="_blank">Download</a>@endif</td></tr>@endforeach</tbody></table></div></section></div>
@endsection