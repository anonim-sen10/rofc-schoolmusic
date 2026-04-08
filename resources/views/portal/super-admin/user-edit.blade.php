@extends('portal.layout')

@section('title', 'Edit User | ROFC')
@section('page-title', 'Edit User')

@section('content')
<section class="card module-head">
    <h2>Edit User</h2>
    <p>Perbarui data akun dan role user.</p>
</section>

@if ($errors->any())
    <section class="card">
        <ul class="list">
            @foreach ($errors->all() as $error)
                <li><span>{{ $error }}</span></li>
            @endforeach
        </ul>
    </section>
@endif

<section class="card">
    <form class="module-form" method="POST" action="{{ route('super-admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <label>Nama
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </label>
        <label>Role
            <select name="role" required>
                @foreach ($roleOptions as $slug => $config)
                    <option value="{{ $slug }}" @selected(old('role', $user->primaryRole()) === $slug)>{{ $config['name'] }}</option>
                @endforeach
            </select>
        </label>
        <label>Instrument (opsional, khusus teacher)
            <input type="text" name="instrument" value="{{ old('instrument') }}" placeholder="Drum, Piano, Guitar, dll">
        </label>
        <label>No. Telepon (opsional, khusus siswa)
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxx">
        </label>
        <label>Password Baru (opsional)
            <input type="password" name="password" placeholder="Kosongkan jika tidak ganti password">
        </label>
        <label>Konfirmasi Password Baru
            <input type="password" name="password_confirmation">
        </label>
        <button type="submit">Simpan Perubahan</button>
    </form>

    <div style="margin-top: 1rem;">
        <a href="{{ route('super-admin.module', ['module' => 'roles']) }}" class="logout-btn">Kembali</a>
    </div>
</section>
@endsection
