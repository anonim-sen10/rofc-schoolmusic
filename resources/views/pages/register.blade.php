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
                <label>Nama Lengkap
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                </label>
                <label>Umur
                    <input type="number" name="age" min="4" max="80" value="{{ old('age') }}" required>
                </label>
                <label>Nomor WhatsApp
                    <input type="text" name="phone" value="{{ old('phone') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Pilih Kelas
                    <select name="program" required>
                        <option value="">Pilih Kelas</option>
                        @foreach (['Drum', 'Piano', 'Guitar', 'Vocal', 'Violin', 'Music Theory'] as $program)
                            <option value="{{ $program }}" {{ old('program') === $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Jadwal yang Diminati
                    <select name="preferred_schedule" required>
                        <option value="">Pilih Jadwal</option>
                        @foreach (['Weekday Pagi', 'Weekday Sore', 'Weekday Malam', 'Weekend Pagi', 'Weekend Sore'] as $schedule)
                            <option value="{{ $schedule }}" {{ old('preferred_schedule') === $schedule ? 'selected' : '' }}>{{ $schedule }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Pesan Tambahan
                    <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
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
