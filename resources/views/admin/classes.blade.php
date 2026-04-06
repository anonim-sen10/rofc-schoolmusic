@extends('admin.layout')

@section('title', 'Classes | ROFC Admin')
@section('page-title', 'Classes Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Kelas Musik</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Teacher</th>
                        <th>Jadwal</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr>
                            <td>{{ $class['name'] }}</td>
                            <td>{{ $class['teacher'] }}</td>
                            <td>{{ $class['schedule'] }}</td>
                            <td>{{ $class['price'] }}</td>
                            <td><span class="status {{ $class['status'] }}">{{ ucfirst($class['status']) }}</span></td>
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
        <h2>Tambah Kelas</h2>
        <form class="form-stack">
            <label>Nama Kelas <input type="text" placeholder="Contoh: Drum Beginner"></label>
            <label>Deskripsi <textarea rows="3" placeholder="Deskripsi kelas"></textarea></label>
            <label>Harga <input type="text" placeholder="Rp850.000"></label>
            <label>Jadwal <input type="text" placeholder="Mon & Wed 16:00"></label>
            <label>Teacher <input type="text" placeholder="Nama guru"></label>
            <label>Status
                <select>
                    <option>active</option>
                    <option>inactive</option>
                </select>
            </label>
            <button class="btn-admin" type="button">Simpan Kelas</button>
        </form>
    </article>
</section>
@endsection
