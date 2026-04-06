@extends('admin.layout')

@section('title', 'Events | ROFC Admin')
@section('page-title', 'Events Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar Event</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event['title'] }}</td>
                            <td>{{ $event['date'] }}</td>
                            <td><span class="status {{ $event['status'] }}">{{ ucfirst($event['status']) }}</span></td>
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
        <h2>Tambah Event</h2>
        <form class="form-stack">
            <label>Judul Event <input type="text" placeholder="Nama event"></label>
            <label>Tanggal Event <input type="date"></label>
            <label>Deskripsi <textarea rows="4" placeholder="Deskripsi event"></textarea></label>
            <label>Status
                <select>
                    <option>upcoming</option>
                    <option>planning</option>
                </select>
            </label>
            <button class="btn-admin" type="button">Simpan Event</button>
        </form>
    </article>
</section>
@endsection
