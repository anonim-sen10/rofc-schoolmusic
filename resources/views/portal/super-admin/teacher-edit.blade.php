@extends('portal.layout')

@section('title', 'Edit Teacher | ROFC')
@section('page-title', 'Edit Teacher')

@section('content')
<section class="card module-head">
    <h2>Edit Teacher</h2>
    <p>Perbarui data akun dan profil teacher.</p>
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
    <form class="module-form" method="POST" enctype="multipart/form-data" action="{{ route('super-admin.teachers.update', $teacher) }}">
        @csrf
        @method('PUT')
        <label>Nama
            <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required>
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email', $teacher->user?->email) }}" required>
        </label>
        <label>Nomor HP
            <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" required>
        </label>
        <label>Alamat
            <textarea name="address" rows="3" required>{{ old('address', $teacher->address) }}</textarea>
        </label>
        <label>Jenis Kelamin
            <select name="gender" required>
                <option value="laki-laki" @selected(old('gender', $teacher->gender) === 'laki-laki')>Laki-laki</option>
                <option value="perempuan" @selected(old('gender', $teacher->gender) === 'perempuan')>Perempuan</option>
            </select>
        </label>
        <label>Agama
            <input type="text" name="religion" value="{{ old('religion', $teacher->religion) }}" required>
        </label>
        <label>Bidang / Instrumen
            <input type="text" name="instrument" value="{{ old('instrument', $teacher->instrument) }}">
        </label>
        <label>Class
            <select name="class_id">
                <option value="">Pilih class (opsional)</option>
                @foreach ($classesForTeachers as $class)
                    <option value="{{ $class->id }}" @selected((string) old('class_id') === (string) $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
        </label>
        <label>Password Baru (opsional)
            <input type="password" name="password" placeholder="Kosongkan jika tidak ganti password">
        </label>
        <label>Konfirmasi Password Baru
            <input type="password" name="password_confirmation">
        </label>
        <label>Upload Foto Profile
            <input type="file" name="photo" accept="image/*">
        </label>
        <button type="submit">Simpan Perubahan</button>
    </form>

    <div style="margin-top: 1rem;">
        <a href="{{ route('super-admin.module', ['module' => 'teachers']) }}" class="logout-btn">Kembali</a>
    </div>
</section>
@endsection
