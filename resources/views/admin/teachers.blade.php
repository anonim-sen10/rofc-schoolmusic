@extends('admin.layout')

@section('title', 'Teachers | ROFC Admin')
@section('page-title', 'Teachers Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Guru</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Instrument</th>
                        <th>Experience</th>
                        <th>Bio</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher['name'] }}</td>
                            <td>{{ $teacher['instrument'] }}</td>
                            <td>{{ $teacher['experience'] }}</td>
                            <td>{{ $teacher['bio'] }}</td>
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
        <h2>Tambah Guru</h2>
        <form class="form-stack">
            <label>Nama <input type="text" placeholder="Nama guru"></label>
            <label>Instrument <input type="text" placeholder="Drum / Piano / Vocal"></label>
            <label>Bio <textarea rows="3" placeholder="Deskripsi singkat"></textarea></label>
            <label>Experience <input type="text" placeholder="Contoh: 8 Tahun"></label>
            <label>Photo <input type="file"></label>
            <button class="btn-admin" type="button">Simpan Guru</button>
        </form>
    </article>
</section>
@endsection
