@extends('portal.layout')

@section('title', 'Detail Teacher | ROFC')
@section('page-title', 'Detail Teacher')

@section('content')
<section class="card module-head">
    <h2>{{ $teacher->name }}</h2>
    <p>Detail data akun dan profil teacher.</p>
</section>

<section class="card">
    <div class="table-wrap">
        <table>
            <tbody>
                <tr><th>Nama</th><td>{{ $teacher->name }}</td></tr>
                <tr><th>Email</th><td>{{ $teacher->user?->email ?? '-' }}</td></tr>
                <tr><th>Nomor HP</th><td>{{ $teacher->phone ?? '-' }}</td></tr>
                <tr><th>Alamat</th><td>{{ $teacher->address ?? '-' }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>{{ $teacher->gender ?? '-' }}</td></tr>
                <tr><th>Agama</th><td>{{ $teacher->religion ?? '-' }}</td></tr>
                <tr><th>Instrument</th><td>{{ $teacher->instrument }}</td></tr>
                <tr><th>Class</th><td>{{ $teacher->classes->pluck('name')->implode(', ') ?: '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem; display: flex; gap: 0.6rem;">
        <a href="{{ route('super-admin.teachers.edit', $teacher) }}" class="logout-btn">Edit</a>
        <a href="{{ route('super-admin.module', ['module' => 'teachers']) }}" class="logout-btn">Kembali</a>
    </div>
</section>
@endsection
