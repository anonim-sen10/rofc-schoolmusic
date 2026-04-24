@extends('layouts.app')

@section('title', 'ROFC Private Music | About Us')

@section('content')
<section class="page-banner">
    <div class="container">
        <span class="eyebrow">About Us</span>
        <h1>Profil ROFC Private Music</h1>
        <p>Sekolah musik modern dengan lingkungan belajar yang hangat, kreatif, dan profesional.</p>
    </div>
</section>

<section class="section">
    <div class="container two-col">
        <div>
            <h2>Cerita Kami</h2>
            <p>ROFC Private Music berdiri dari semangat menghadirkan pendidikan musik yang berkualitas dan menyenangkan. Kami percaya musik bukan hanya keterampilan, tetapi juga sarana membangun karakter, disiplin, dan kepercayaan diri.</p>
            <p>Sejak awal, ROFC berkomitmen menjadi ruang tumbuh bagi setiap siswa dari berbagai usia dan latar belakang.</p>
        </div>
        <div class="info-panel">
            <h3>Suasana Belajar</h3>
            <p>Lingkungan kelas dirancang agar siswa merasa nyaman, fokus, dan berani berekspresi.</p>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container card-grid two">
        <article class="feature-card">
            <h3>Visi</h3>
            <p>Menjadi sekolah musik pilihan yang melahirkan musisi kreatif, percaya diri, dan berkarakter.</p>
        </article>
        <article class="feature-card">
            <h3>Misi</h3>
            <p>Menyediakan pembelajaran musik terstruktur, menghadirkan pengajar kompeten, serta membangun budaya musikal yang positif.</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Nilai Utama</span>
            <h2>Nilai yang Kami Pegang</h2>
        </div>
        <div class="card-grid three">
            <article class="feature-card"><h3>Kreativitas</h3><p>Mendorong siswa berani bereksplorasi dan menemukan gaya bermusik sendiri.</p></article>
            <article class="feature-card"><h3>Profesionalitas</h3><p>Standar pengajaran tinggi dengan sistem evaluasi yang jelas dan terarah.</p></article>
            <article class="feature-card"><h3>Empati</h3><p>Pendekatan personal yang menghargai proses unik setiap siswa.</p></article>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container two-col">
        <div>
            <h2>Fasilitas</h2>
            <ul class="check-list">
                <li>Ruang kelas ber-AC dan kedap suara</li>
                <li>Alat musik standar studio</li>
                <li>Area tunggu orang tua yang nyaman</li>
                <li>Program evaluasi progres berkala</li>
            </ul>
        </div>
        <div>
            <h2>Komitmen Kami</h2>
            <p>Kami berkomitmen mengembangkan bakat siswa secara menyeluruh: teknik, musikalitas, mental tampil, dan kolaborasi.</p>
            <a href="{{ route('register') }}" class="btn btn-gold">Mulai Belajar</a>
        </div>
    </div>
</section>
@endsection
