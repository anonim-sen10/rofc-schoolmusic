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
        <h3>Data User</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usersForRoles as $userRow)
                        <tr>
                            <td>{{ $userRow->name }}</td>
                            <td>{{ $userRow->email }}</td>
                            <td>{{ $userRow->roles->pluck('slug')->implode(', ') }}</td>
                            <td>{{ optional($userRow->created_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('super-admin.users.show', $userRow) }}" title="Detail" aria-label="Detail">&#128065;</a>
                                    <a href="{{ route('super-admin.users.edit', $userRow) }}" title="Edit" aria-label="Edit">&#9998;</a>
                                    <form method="POST" action="{{ route('super-admin.users.destroy', $userRow) }}" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" aria-label="Hapus">&#128465;</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'users')
    <section class="card">
        <p>Data user telah dipindahkan ke menu Roles agar pengelolaan role dan akun berada dalam satu halaman.</p>
    </section>
@endif

@if ($moduleKey === 'teachers')
    <section class="card">
        <h3>Create Teacher Account</h3>
        <form class="module-form" method="POST" enctype="multipart/form-data" action="{{ route('super-admin.teachers.store') }}">
            @csrf
            <label>Nama
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label>Konfirmasi Password
                <input type="password" name="password_confirmation" required>
            </label>
            <label>Nomor HP
                <input type="text" name="phone" value="{{ old('phone') }}" required>
            </label>
            <label>Alamat
                <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
            </label>
            <label>Jenis Kelamin
                <select name="gender" required>
                    <option value="laki-laki" @selected(old('gender') === 'laki-laki')>Laki-laki</option>
                    <option value="perempuan" @selected(old('gender') === 'perempuan')>Perempuan</option>
                </select>
            </label>
            <label>Agama
                <input type="text" name="religion" value="{{ old('religion') }}" required>
            </label>
            <label>Instrument
                <input type="text" name="instrument" value="{{ old('instrument') }}" placeholder="Drum, Piano, Guitar, dll">
            </label>
            <label>Bio
                <textarea name="bio" rows="3">{{ old('bio') }}</textarea>
            </label>
            <label>Experience
                <input type="text" name="experience" value="{{ old('experience') }}">
            </label>
            <label>Class
                <select name="class_id">
                    <option value="">Pilih class (opsional)</option>
                    @foreach ($classesForTeachers as $class)
                        <option value="{{ $class->id }}" @selected((string) old('class_id') === (string) $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Upload Foto Profile
                <input type="file" name="photo" accept="image/*">
            </label>
            <button type="submit">Simpan Teacher</button>
        </form>
    </section>

    <section class="card">
        <h3>Daftar Guru</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Instrument</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachersForManagement as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->user?->email ?? '-' }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>{{ $teacher->address ?? '-' }}</td>
                            <td>{{ $teacher->gender ?? '-' }}</td>
                            <td>{{ $teacher->religion ?? '-' }}</td>
                            <td>{{ $teacher->instrument }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if (! in_array($moduleKey, ['users', 'roles'], true))
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
@endif
@endsection
