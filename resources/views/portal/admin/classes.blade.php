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
@section('title', 'Classes Management')
@section('page-title', 'Classes Management')
@section('content')
<div class="split-grid">
    <section class="card">
        <h3>Tambah Kelas</h3>
        <form class="module-form" method="POST" action="{{ route('admin.classes.store') }}">
            @csrf
            <label>Nama Kelas <input type="text" name="name" required></label>
            <label>Deskripsi <textarea name="description" rows="3"></textarea></label>
            <label>Harga <input type="number" step="0.01" name="price" required></label>
            <label>Jadwal <input type="text" name="schedule"></label>
            <label>Teacher
                <select name="teacher_id"><option value="">-</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach</select>
            </label>
            <label>Status
                <select name="status"><option value="active">active</option><option value="inactive">inactive</option></select>
            </label>
            <button type="submit">Simpan</button>
        </form>
    </section>
    <section class="card">
        <h3>Daftar Kelas</h3>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Teacher</th><th>Harga</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                @foreach($classList as $classItem)
                    <tr>
                        <td>{{ $classItem->name }}</td>
                        <td>{{ $classItem->teacher?->name ?? '-' }}</td>
                        <td>{{ number_format($classItem->price,0,',','.') }}</td>
                        <td>{{ $classItem->status }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.classes.destroy', $classItem) }}">@csrf @method('DELETE')<button type="submit">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
