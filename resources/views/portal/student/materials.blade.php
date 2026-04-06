@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Materials')
@section('page-title','Download Materials')
@section('content')
<section class="card"><div class="table-wrap"><table><thead><tr><th>Title</th><th>Description</th><th>Download</th></tr></thead><tbody>@foreach($materials as $row)<tr><td>{{ $row->title }}</td><td>{{ $row->description }}</td><td>@if($row->file_path)<a href="{{ asset('storage/'.$row->file_path) }}" target="_blank">Download</a>@endif</td></tr>@endforeach</tbody></table></div></section>
@endsection