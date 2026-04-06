@extends('layouts.app')

@section('title', 'ROFC School Music | Programs')

@section('content')
<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Programs</span>
        <h1>Program Kelas Musik</h1>
        <p>Pilih kelas sesuai minat, usia, dan level kemampuanmu.</p>
    </div>
</section>

<section class="section">
    <div class="container card-grid three">
        <article class="program-card">
            <h3>Drum</h3>
            <p>Untuk: anak 8+, remaja, dewasa</p>
            <p>Level: Pemula - Menengah - Lanjutan</p>
            <p>Manfaat: koordinasi, timing, groove, dan performa.</p>
        </article>
        <article class="program-card">
            <h3>Piano</h3>
            <p>Untuk: anak 6+, remaja, dewasa</p>
            <p>Level: Pemula - Menengah - Lanjutan</p>
            <p>Manfaat: pemahaman harmoni, reading, dan ekspresi musikal.</p>
        </article>
        <article class="program-card">
            <h3>Guitar</h3>
            <p>Untuk: anak 8+, remaja, dewasa</p>
            <p>Level: Pemula - Menengah - Lanjutan</p>
            <p>Manfaat: teknik chord, rhythm, solo, dan kreativitas lagu.</p>
        </article>
        <article class="program-card">
            <h3>Vocal</h3>
            <p>Untuk: anak 9+, remaja, dewasa</p>
            <p>Level: Pemula - Menengah - Lanjutan</p>
            <p>Manfaat: teknik pernapasan, intonasi, dan kepercayaan diri.</p>
        </article>
        <article class="program-card">
            <h3>Violin</h3>
            <p>Untuk: anak 7+, remaja, dewasa</p>
            <p>Level: Pemula - Menengah</p>
            <p>Manfaat: ketelitian teknik, tone control, dan musikalitas.</p>
        </article>
        <article class="program-card">
            <h3>Music Theory</h3>
            <p>Untuk: semua siswa yang ingin memperkuat fondasi musik.</p>
            <p>Level: Basic - Intermediate</p>
            <p>Manfaat: membaca notasi, harmoni, rhythm, dan analisis lagu.</p>
        </article>
    </div>
</section>

<section class="section section-soft">
    <div class="container two-col">
        <div>
            <h2>Sistem Belajar</h2>
            <ul class="check-list">
                <li>Pertemuan privat atau semi privat</li>
                <li>Durasi kelas 60 menit per sesi</li>
                <li>Jadwal fleksibel sesuai slot tersedia</li>
                <li>Progress report berkala</li>
            </ul>
        </div>
        <div class="info-panel">
            <h3>Siap Pilih Kelas?</h3>
            <p>Tim kami akan membantu menentukan program paling cocok berdasarkan tujuan belajar Anda.</p>
            <a href="{{ route('register') }}" class="btn btn-gold">Daftar Program</a>
        </div>
    </div>
</section>
@endsection
