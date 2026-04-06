@extends('admin.layout')

@section('title', 'Gallery | ROFC Admin')
@section('page-title', 'Gallery Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Gallery</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ $item['category'] }}</td>
                            <td>{{ $item['type'] }}</td>
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
        <h2>Upload Gallery</h2>
        <form class="form-stack">
            <label>Judul <input type="text" placeholder="Judul media"></label>
            <label>Kategori <input type="text" placeholder="Class Activity / Event"></label>
            <label>Tipe
                <select>
                    <option>Photo</option>
                    <option>Video</option>
                </select>
            </label>
            <label>File <input type="file"></label>
            <button class="btn-admin" type="button">Upload</button>
        </form>
    </article>
</section>
@endsection
