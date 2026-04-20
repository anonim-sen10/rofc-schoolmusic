@extends('layouts.app')

@section('title', 'ROFC School Music | Registration')

@section('content')
<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Registration</span>
        <h1>Form Pendaftaran Siswa Baru</h1>
        <p>Isi data berikut untuk bergabung dengan ROFC School Music.</p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        <div class="form-card">
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="rofc-form">
                @csrf
                <h3>Data Siswa</h3>
                <label>Nama Lengkap
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                </label>
                <label>Nama Panggilan
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" required>
                </label>
                <label>Jenis Kelamin</label>
                <div class="checkbox-group">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'checked' : '' }} required>
                        Laki-laki
                    </label>
                    <label>
                        <input type="radio" name="jenis_kelamin" value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }} required>
                        Perempuan
                    </label>
                </div>
                <label>Tempat Lahir
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                </label>
                <label>Tanggal Lahir
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                </label>
                <label>Kewarganegaraan
                    <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" required>
                </label>
                <label>Alamat
                    <textarea name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                </label>
                <label>No HP Siswa
                    <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>

                <h3>Data Orang Tua</h3>
                <label>Nama Orang Tua
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required>
                </label>
                <label>Pekerjaan Orang Tua
                    <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}">
                </label>
                <label>No HP Orang Tua
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required>
                </label>
                <label>Email Orang Tua
                    <input type="email" name="email_ortu" value="{{ old('email_ortu') }}">
                </label>

                <h3>Program</h3>
                <label>Instrumen
                    <select name="instrumen" required>
                        <option value="">Pilih Instrumen</option>
                        @foreach (['Drum', 'Piano', 'Guitar', 'Vocal', 'Violin', 'Bass', 'Keyboard', 'Music Theory'] as $instrumen)
                            <option value="{{ $instrumen }}" {{ old('instrumen') === $instrumen ? 'selected' : '' }}>{{ $instrumen }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Program Tambahan (opsional)</label>
                <div class="checkbox-group">
                    @php($oldProgramTambahan = old('program_tambahan', []))
                    @foreach (['Teori Musik', 'Ensemble / Band', 'Skill Teknik (ajang kompetisi)', 'Ujian Sertifikat bertaraf international'] as $programTambahan)
                        <label>
                            <input type="checkbox" name="program_tambahan[]" value="{{ $programTambahan }}" {{ in_array($programTambahan, $oldProgramTambahan, true) ? 'checked' : '' }}>
                            {{ $programTambahan }}
                        </label>
                    @endforeach
                </div>

                <h3>Jadwal</h3>
                <label>Hari Pilihan</label>
                <div class="checkbox-group">
                    @php($oldHariPilihan = old('hari_pilihan', []))
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <label>
                            <input type="checkbox" name="hari_pilihan[]" value="{{ $hari }}" {{ in_array($hari, $oldHariPilihan, true) ? 'checked' : '' }}>
                            {{ $hari }}
                        </label>
                    @endforeach
                </div>

                <h3>Pengalaman</h3>
                <label>Pernah belajar musik sebelumnya?</label>
                <div class="checkbox-group">
                    <label>
                        <input type="radio" name="pengalaman" value="1" {{ old('pengalaman') === '1' ? 'checked' : '' }} required>
                        Ya
                    </label>
                    <label>
                        <input type="radio" name="pengalaman" value="0" {{ old('pengalaman') === '0' ? 'checked' : '' }} required>
                        Tidak
                    </label>
                </div>
                <label>Deskripsi Pengalaman
                    <textarea name="deskripsi_pengalaman" rows="4">{{ old('deskripsi_pengalaman') }}</textarea>
                </label>

                <button type="submit" class="btn btn-gold">Kirim Pendaftaran</button>
            </form>

            @if ($errors->any())
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>
@endsection
