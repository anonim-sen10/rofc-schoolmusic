@extends('admin.layout')

@section('title', 'Testimonials | ROFC Admin')
@section('page-title', 'Testimonials Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Testimoni</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Pesan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testimonials as $testimonial)
                        <tr>
                            <td>{{ $testimonial['name'] }}</td>
                            <td>{{ $testimonial['role'] }}</td>
                            <td>{{ $testimonial['message'] }}</td>
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
        <h2>Tambah Testimoni</h2>
        <form class="form-stack">
            <label>Nama <input type="text" placeholder="Nama siswa/orang tua"></label>
            <label>Role <input type="text" placeholder="Siswa / Orang Tua"></label>
            <label>Pesan <textarea rows="4" placeholder="Testimoni"></textarea></label>
            <button class="btn-admin" type="button">Simpan Testimoni</button>
        </form>
    </article>
</section>
@endsection
