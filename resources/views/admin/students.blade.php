@extends('admin.layout')

@section('title', 'Students | ROFC Admin')
@section('page-title', 'Students Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Siswa</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Class</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student['name'] }}</td>
                            <td>{{ $student['age'] }}</td>
                            <td>{{ $student['phone'] }}</td>
                            <td>{{ $student['email'] }}</td>
                            <td>{{ $student['class'] }}</td>
                            <td>
                                <div class="action-row">
                                    <button class="btn-admin ghost" type="button">Edit</button>
                                    <button class="btn-admin ghost" type="button">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <article class="admin-card">
        <h2>Tambah Siswa</h2>
        <form class="form-stack">
            <label>Nama <input type="text" placeholder="Nama lengkap"></label>
            <label>Umur <input type="number" placeholder="Umur"></label>
            <label>Phone <input type="text" placeholder="Nomor WhatsApp"></label>
            <label>Email <input type="email" placeholder="Email"></label>
            <label>Class <input type="text" placeholder="Kelas siswa"></label>
            <button class="btn-admin" type="button">Simpan Siswa</button>
        </form>
    </article>
</section>
@endsection
