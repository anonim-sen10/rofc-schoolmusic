@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Student Dashboard')
@section('page-title','Student Dashboard')
@section('page-subtitle', 'ROFC Private Music Management Information System - Student Workspace')
@section('content')
@php
	$classCollection = collect($classes);
@endphp
<section class="dashboard-hero" data-searchable>
	<div>
		<p class="eyebrow">Learning Snapshot</p>
		<h2>Student Dashboard</h2>
		<p>Halo {{ $student->name }}, semua informasi kelas, pembayaran, dan progres belajar ada di sini.</p>
	</div>
	<div class="hero-actions">
		<a href="{{ route('student.my-class.index') }}" class="ghost-btn">My Class</a>
		<a href="{{ route('student.payment.index') }}" class="ghost-btn">Payment</a>
	</div>
</section>

<section class="kpi-grid" data-searchable>
	<x-ui.card title="My Classes">
		<div class="kpi-row">
			<div class="kpi-value">{{ $classCount }}</div>
			<span class="kpi-icon"><i data-lucide="book-open"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Recent Payments">
		<div class="kpi-row">
			<div class="kpi-value">{{ $payments->count() }}</div>
			<span class="kpi-icon"><i data-lucide="wallet"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Progress Notes">
		<div class="kpi-row">
			<div class="kpi-value">{{ $progress->count() }}</div>
			<span class="kpi-icon"><i data-lucide="activity"></i></span>
		</div>
	</x-ui.card>
	<x-ui.card title="Status">
		<div class="kpi-row">
			<div class="kpi-value">{{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}</div>
			<span class="kpi-icon"><i data-lucide="shield-check"></i></span>
		</div>
	</x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
	<x-ui.card title="My Classes" subtitle="Jadwal kelas yang sedang diikuti">
		@if ($classCollection->isNotEmpty())
			<ul class="insight-list">
				@foreach ($classCollection as $class)
					<li>
						<span><i data-lucide="music-2"></i>{{ $class->name }}</span>
						<strong>{{ $class->teacher?->name ?? '-' }}</strong>
					</li>
				@endforeach
			</ul>
		@else
			<x-ui.empty-state title="No class assigned" description="Belum ada kelas yang terdaftar ke akun Anda." icon="calendar-x" />
		@endif
	</x-ui.card>

	<x-ui.card title="Recent Activity" subtitle="Pembaruan pembayaran dan progres terbaru">
		<ul class="insight-list">
			<li>
				<span><i data-lucide="receipt"></i>Latest Payment</span>
				<x-ui.badge :type="optional($payments->first())->status === 'paid' ? 'success' : 'warning'">{{ strtoupper(optional($payments->first())->status ?? 'no-payment') }}</x-ui.badge>
			</li>
			<li>
				<span><i data-lucide="target"></i>Latest Progress</span>
				<strong>{{ optional($progress->first())->topic ?? '-' }}</strong>
			</li>
			<li>
				<span><i data-lucide="folder-open"></i>Materials</span>
				<a href="{{ route('student.materials.index') }}" class="ghost-btn">Open</a>
			</li>
		</ul>
	</x-ui.card>
</section>
@endsection