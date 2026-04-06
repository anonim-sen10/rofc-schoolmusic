@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Profile')
@section('page-title','My Profile')
@section('content')
<section class="card"><form class="module-form" method="POST" action="{{ route('student.profile.update') }}">@csrf @method('PUT')
<label>Nama<input type="text" name="name" value="{{ $student->name }}" required></label>
<label>Umur<input type="number" name="age" value="{{ $student->age }}"></label>
<label>Phone<input type="text" name="phone" value="{{ $student->phone }}"></label>
<label>Address<textarea name="address">{{ $student->address }}</textarea></label>
<button type="submit">Update Profile</button></form></section>
@endsection