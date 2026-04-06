@php
$menuItems = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Classes', 'url' => route('admin.classes.index')],
    ['label' => 'Teachers', 'url' => route('admin.teachers.index')],
    ['label' => 'Students', 'url' => route('admin.students.index')],
    ['label' => 'Registrations', 'url' => route('admin.registrations.index')],
];
$panelTitle = 'Admin Dashboard';
$homeRoute = route('admin.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'Teachers Management')
@section('page-title', 'Teachers Management')
@section('content')
<div class="split-grid">
    <section class="card">
        <h3>Tambah Guru</h3>
        <form class="module-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.teachers.store') }}">
            @csrf
            <label>Nama <input type="text" name="name" required></label>
            <label>Instrument <input type="text" name="instrument" required></label>
            <label>Bio <textarea name="bio" rows="3"></textarea></label>
            <label>Experience <input type="text" name="experience"></label>
            <label>Photo <input type="file" name="photo"></label>
            <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
            <button type="submit">Simpan</button>
        </form>
    </section>
    <section class="card">
        <h3>Daftar Guru</h3>
        <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Instrument</th><th>Status</th><th>Action</th></tr></thead><tbody>
        @foreach($teachers as $teacher)
            <tr><td>{{ $teacher->name }}</td><td>{{ $teacher->instrument }}</td><td>{{ $teacher->is_active ? 'active' : 'inactive' }}</td><td><form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
        @endforeach
        </tbody></table></div>
    </section>
</div>
@endsection
