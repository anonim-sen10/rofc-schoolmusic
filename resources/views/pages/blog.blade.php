@extends('layouts.app')

@section('title', 'ROFC Private Music | Blog')

@section('content')
<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Blog & News</span>
        <h1>Artikel dan Update Terbaru</h1>
        <p>Tips, insight, dan berita kegiatan dari ROFC Private Music.</p>
    </div>
</section>

<section class="section">
    <div class="container card-grid two">
        <article class="blog-card">
            <h3>5 Tips Konsisten Latihan Alat Musik di Rumah</h3>
            <p>Bangun kebiasaan latihan yang efektif dengan target kecil yang terukur.</p>
            <a href="#">Baca Selengkapnya</a>
        </article>
        <article class="blog-card">
            <h3>Mengapa Sekolah Musik Penting untuk Anak?</h3>
            <p>Musik membantu perkembangan fokus, disiplin, kreativitas, dan kepercayaan diri.</p>
            <a href="#">Baca Selengkapnya</a>
        </article>
        <article class="blog-card">
            <h3>Teknik Dasar Vocal untuk Pemula</h3>
            <p>Pelajari pondasi pernapasan dan kontrol suara agar bernyanyi lebih stabil.</p>
            <a href="#">Baca Selengkapnya</a>
        </article>
        <article class="blog-card">
            <h3>Highlight Student Performance Bulan Ini</h3>
            <p>Dokumentasi momen terbaik siswa saat tampil di mini concert ROFC.</p>
            <a href="#">Baca Selengkapnya</a>
        </article>
    </div>
</section>
@endsection
