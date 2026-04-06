@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Student Dashboard')
@section('page-title','Student Dashboard')
@section('content')
<section class="stats-grid"><article class="card stat"><p>My Classes</p><h2>{{ $classCount }}</h2></article><article class="card stat"><p>Recent Payments</p><h2>{{ $payments->count() }}</h2></article><article class="card stat"><p>Progress Notes</p><h2>{{ $progress->count() }}</h2></article><article class="card stat"><p>Status</p><h2>{{ $student->is_active ? 'Active' : 'Inactive' }}</h2></article></section>
@endsection