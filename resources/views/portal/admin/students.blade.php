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
@section('title', 'Students Management')
@section('page-title', 'Students Management')
@section('content')
<div class="split-grid">
    <section class="card">
        <h3>Tambah Siswa</h3>
        <form class="module-form" method="POST" action="{{ route('admin.students.store') }}">
            @csrf
            <label>Nama <input type="text" name="name" required></label>
            <label>Umur <input type="number" name="age"></label>
            <label>Phone <input type="text" name="phone"></label>
            <label>Email <input type="email" name="email"></label>
            <label>Address <textarea name="address" rows="2"></textarea></label>
            <label>Kelas
                <select multiple name="class_ids[]">@foreach($classList as $classItem)<option value="{{ $classItem->id }}">{{ $classItem->name }}</option>@endforeach</select>
            </label>
            <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
            <button type="submit">Simpan</button>
        </form>
    </section>
    <section class="card">
        <h3>Daftar Siswa</h3>
        <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Email</th><th>Kelas</th><th>Action</th></tr></thead><tbody>
        @foreach($students as $student)
            <tr><td>{{ $student->name }}</td><td>{{ $student->email }}</td><td>{{ $student->classes->pluck('name')->join(', ') }}</td><td><form method="POST" action="{{ route('admin.students.destroy', $student) }}">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
        @endforeach
        </tbody></table></div>
    </section>
</div>
@endsection
