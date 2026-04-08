@extends('portal.layout')

@section('title', $moduleTitle.' | ROFC')
@section('page-title', $moduleTitle)

@section('content')
<section class="card module-head">
    <h2>{{ $moduleTitle }}</h2>
    <p>{{ $moduleDescription }}</p>
</section>

@if (session('success'))
    <section class="card">
        <p>{{ session('success') }}</p>
    </section>
@endif

@if ($errors->any())
    <section class="card">
        <ul class="list">
            @foreach ($errors->all() as $error)
                <li><span>{{ $error }}</span></li>
            @endforeach
        </ul>
    </section>
@endif

@if ($moduleKey === 'users')
    <section class="card">
        <h3>Buat Akun Login Baru</h3>
        <form class="module-form" method="POST" action="{{ route('super-admin.users.store') }}">
            @csrf
            <label>Nama
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>Role
                <select name="role" required>
                    <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="finance" @selected(old('role') === 'finance')>Finance</option>
                    <option value="teacher" @selected(old('role') === 'teacher')>Teacher</option>
                    <option value="student" @selected(old('role') === 'student')>Siswa</option>
                </select>
            </label>
            <label>Instrument (khusus teacher)
                <input type="text" name="instrument" value="{{ old('instrument') }}" placeholder="Drum, Piano, Guitar, dll">
            </label>
            <label>No. Telepon (khusus siswa)
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxx">
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label>Konfirmasi Password
                <input type="password" name="password_confirmation" required>
            </label>
            <button type="submit">Buat Akun</button>
        </form>
    </section>
@endif

@if ($moduleKey === 'roles')
    <section class="card">
        <h3>Buat Role Baru</h3>
        <form class="module-form" method="POST" action="{{ route('super-admin.roles.store') }}">
            @csrf
            <label>Nama Role
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Editor Konten" required>
            </label>
            <label>Slug (opsional)
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="contoh: editor_konten">
            </label>
            <label>Deskripsi (opsional)
                <textarea name="description" rows="3" placeholder="Deskripsi hak akses role">{{ old('description') }}</textarea>
            </label>
            <button type="submit">Simpan Role</button>
        </form>
    </section>
@endif

<section class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        @foreach ($row as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
