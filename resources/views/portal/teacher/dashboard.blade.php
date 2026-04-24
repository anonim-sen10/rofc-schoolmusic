@php $menuItems=[['label'=>'Dashboard','url'=>route('teacher.dashboard')],['label'=>'Attendance','url'=>route('teacher.attendance.index')],['label'=>'Student Progress','url'=>route('teacher.student-progress.index')],['label'=>'Materials','url'=>route('teacher.materials.index')],['label'=>'My Classes','url'=>route('teacher.my-classes.index')],['label'=>'My Students','url'=>route('teacher.my-students.index')],['label'=>'Schedule','url'=>route('teacher.schedule.index')]]; $panelTitle='Teacher Portal'; $homeRoute=route('teacher.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Teacher Dashboard')
@section('page-title','Teacher Dashboard')
@section('page-subtitle', 'ROFC Private Music Management Information System - Teacher Workspace')
@section('content')
<section class="dashboard-hero" data-searchable>
	<div>
		<p class="eyebrow">Teaching Snapshot</p>
		<h2>Teacher Dashboard</h2>
		<p>Selamat datang, {{ $teacher->name }}. Pantau kelas aktif, absensi harian, dan progres siswa secara cepat.</p>
	</div>
	<div class="hero-actions">
		<a href="{{ route('teacher.attendance.index') }}" class="ghost-btn">Input Attendance</a>
		<a href="{{ route('teacher.student-progress.index') }}" class="ghost-btn">Update Progress</a>
	</div>
</section>

<section class="kpi-grid" data-searchable>
	<x-ui.card title="My Classes">
		<div class="kpi-row">
			<div class="kpi-value">{{ $classCount }}</div>
			<span class="kpi-icon"><i data-lucide="book-open"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="My Students">
		<div class="kpi-row">
			<div class="kpi-value">{{ $studentCount }}</div>
			<span class="kpi-icon"><i data-lucide="graduation-cap"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Attendance Today">
		<div class="kpi-row">
			<div class="kpi-value">{{ $attendanceCount }}</div>
			<span class="kpi-icon"><i data-lucide="user-check"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Progress Notes">
		<div class="kpi-row">
			<div class="kpi-value">{{ $progressCount }}</div>
			<span class="kpi-icon"><i data-lucide="notebook-pen"></i></span>
		</div>
	</x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
	<x-ui.card title="Assigned Classes" subtitle="Kelas yang sudah di-assign dan accepted">
		@if ($assignedClasses->isNotEmpty())
			<ul class="insight-list">
				@foreach ($assignedClasses as $class)
					<li>
						<span><i data-lucide="music-2"></i>{{ $class->name }}</span>
						<strong>{{ $class->schedule ?: '-' }}</strong>
					</li>
				@endforeach
			</ul>
		@else
			<x-ui.empty-state title="No class assignment" description="Belum ada kelas yang di-assign ke akun teacher ini." icon="calendar-x" />
		@endif
	</x-ui.card>

	<x-ui.card title="Teaching Status" subtitle="Ringkasan aktivitas pengajaran">
		<ul class="insight-list">
			<li>
				<span><i data-lucide="badge-check"></i>Teacher Attendance</span>
				<x-ui.badge :type="$hasTeacherAttendanceToday ? 'success' : 'warning'">{{ $hasTeacherAttendanceToday ? 'DONE' : 'PENDING' }}</x-ui.badge>
			</li>
			<li>
				<span><i data-lucide="clipboard-list"></i>Latest Progress Records</span>
				<strong>{{ $latestProgress->count() }}</strong>
			</li>
			<li>
				<span><i data-lucide="folder-open"></i>Learning Materials</span>
				<a href="{{ route('teacher.materials.index') }}" class="ghost-btn">Open</a>
			</li>
		</ul>
	</x-ui.card>
</section>
@endsection