@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Progress')
@section('page-title','Learning Progress')
@section('content')
<section class="card"><div class="table-wrap"><table><thead><tr><th>Date</th><th>Topic</th><th>Score</th><th>Note</th></tr></thead><tbody>@foreach($records as $row)<tr><td>{{ optional($row->recorded_at)->format('Y-m-d') }}</td><td>{{ $row->topic }}</td><td>{{ $row->score }}</td><td>{{ $row->note }}</td></tr>@endforeach</tbody></table></div></section>
@endsection