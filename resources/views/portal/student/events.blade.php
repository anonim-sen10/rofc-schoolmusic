@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Events')
@section('page-title','School Events')
@section('content')
<section class="card"><ul class="list"><li><span>Creative Drum Workshop</span><small>20 Mei 2026</small></li><li><span>Student Recital Night</span><small>12 Juni 2026</small></li><li><span>ROFC Internal Concert</span><small>20 Agustus 2026</small></li></ul></section>
@endsection