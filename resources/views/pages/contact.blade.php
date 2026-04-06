@extends('layouts.app')

@section('title', 'ROFC School Music | Contact')

@section('content')
<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Contact</span>
        <h1>Hubungi Kami</h1>
        <p>Tim ROFC siap membantu informasi program, jadwal, dan pendaftaran.</p>
    </div>
</section>

<section class="section">
    <div class="container two-col">
        <div>
            <h2>Informasi Kontak</h2>
            <ul class="check-list">
                <li>Alamat: Jl. Harmoni Musik No. 25, Jakarta Selatan</li>
                <li>WhatsApp: +62 812-3456-7890</li>
                <li>Email: hello@rofcschoolmusic.com</li>
                <li>Jam Operasional: Senin - Sabtu, 09.00 - 20.00 WIB</li>
            </ul>
            <iframe class="map-frame" title="Peta Lokasi ROFC" src="https://maps.google.com/maps?q=jakarta%20selatan&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
        </div>

        <div class="form-card">
            <h2>Form Kontak</h2>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="rofc-form">
                @csrf
                <label>Nama Lengkap
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>Nomor WhatsApp
                    <input type="text" name="phone" value="{{ old('phone') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Pesan
                    <textarea name="message" rows="4" required>{{ old('message') }}</textarea>
                </label>
                <button type="submit" class="btn btn-gold">Kirim Pesan</button>
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
