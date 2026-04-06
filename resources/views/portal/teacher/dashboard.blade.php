@php $menuItems=[['label'=>'Dashboard','url'=>route('teacher.dashboard')],['label'=>'Attendance','url'=>route('teacher.attendance.index')],['label'=>'Student Progress','url'=>route('teacher.student-progress.index')],['label'=>'Materials','url'=>route('teacher.materials.index')],['label'=>'My Classes','url'=>route('teacher.my-classes.index')],['label'=>'My Students','url'=>route('teacher.my-students.index')],['label'=>'Schedule','url'=>route('teacher.schedule.index')]]; $panelTitle='Teacher Portal'; $homeRoute=route('teacher.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Teacher Dashboard')
@section('page-title','Teacher Dashboard')
@section('content')
<section class="stats-grid"><article class="card stat"><p>My Classes</p><h2>{{ $classCount }}</h2></article><article class="card stat"><p>My Students</p><h2>{{ $studentCount }}</h2></article><article class="card stat"><p>Attendance Today</p><h2>{{ $attendanceCount }}</h2></article><article class="card stat"><p>Progress Notes</p><h2>{{ $progressCount }}</h2></article></section>
@endsection