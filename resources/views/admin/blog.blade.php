@extends('admin.layout')

@section('title', 'Blog | ROFC Admin')
@section('page-title', 'Blog / News Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Artikel</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post['title'] }}</td>
                            <td>{{ $post['author'] }}</td>
                            <td><span class="status {{ $post['status'] }}">{{ ucfirst($post['status']) }}</span></td>
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
        <h2>Tambah Artikel</h2>
        <form class="form-stack">
            <label>Judul Artikel <input type="text" placeholder="Judul"></label>
            <label>Konten <textarea rows="6" placeholder="Isi artikel"></textarea></label>
            <label>Upload Gambar <input type="file"></label>
            <label>Status
                <select>
                    <option>published</option>
                    <option>draft</option>
                </select>
            </label>
            <button class="btn-admin" type="button">Simpan Artikel</button>
        </form>
    </article>
</section>
@endsection
