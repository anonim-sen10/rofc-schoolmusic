@php
$menuItems = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Classes', 'url' => route('admin.classes.index')],
    ['label' => 'Teachers', 'url' => route('admin.teachers.index')],
    ['label' => 'Students', 'url' => route('admin.students.index')],
    ['label' => 'Registrations', 'url' => route('admin.registrations.index')],
    ['label' => 'Schedule', 'url' => route('admin.schedule.index')],
];
$panelTitle = 'Admin Dashboard';
$homeRoute = route('admin.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'Registrations')
@section('page-title', 'Registrations Approval')
@section('content')
<section class="card">
    <h3>Daftar Pendaftaran</h3>
    <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Email</th><th>Jadwal</th><th>Status</th><th>Action</th></tr></thead><tbody>
    @foreach($registrations as $registration)
        <tr>
            <td>{{ $registration->full_name }}</td>
            <td>{{ $registration->email }}</td>
            <td>{{ $registration->preferred_schedule }}</td>
            <td>{{ $registration->status }}</td>
            <td>
                <form method="POST" action="{{ route('admin.registrations.status', $registration) }}">@csrf @method('PATCH')
                    <select name="status"><option value="pending">pending</option><option value="accepted">accepted</option><option value="rejected">rejected</option></select>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody></table></div>
</section>
@endsection
