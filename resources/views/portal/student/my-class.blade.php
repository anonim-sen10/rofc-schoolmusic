@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','My Class')
@section('page-title','My Class')
@section('content')
<section class="card"><div class="table-wrap"><table><thead><tr><th>Class</th><th>Teacher</th><th>Schedule</th></tr></thead><tbody>@foreach($classes as $class)<tr><td>{{ $class->name }}</td><td>{{ $class->teacher?->name ?? '-' }}</td><td>{{ $class->schedule }}</td></tr>@endforeach</tbody></table></div></section>
@endsection