@extends('admin.layout')

@section('title', 'Users | ROFC Admin')
@section('page-title', 'Users Management')

@section('content')
<section class="page-grid">
    <article class="admin-card">
        <h2>Daftar User Admin</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ ucfirst($user['role']) }}</td>
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
        <h2>Tambah User</h2>
        <form class="form-stack">
            <label>Nama <input type="text" placeholder="Nama user"></label>
            <label>Email <input type="email" placeholder="Email"></label>
            <label>Password <input type="password" placeholder="Password"></label>
            <label>Role
                <select>
                    <option>admin</option>
                    <option>staff</option>
                </select>
            </label>
            <button class="btn-admin" type="button">Simpan User</button>
        </form>
    </article>
</section>
@endsection
