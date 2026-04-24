@extends('layouts.app')

@section('title', 'ROFC Private Music | Home')

@section('content')
<section class="cp-hero">
    <div class="container cp-hero-grid">
        <div class="cp-hero-copy">
            <span class="cp-kicker">ROFC Private Music</span>
            <h1>Music for<br>a Better <span>Education</span></h1>
            <p>ROFC Private Music hadir untuk mendukung sekolah dalam membangun program musik yang berkualitas, inspiratif, dan berkelanjutan.</p>
            <div class="cp-hero-actions">
                <a href="{{ route('about') }}" class="btn btn-primary">Tentang Kami</a>
                <a href="{{ route('programs') }}" class="btn btn-outline cp-video-btn">Lihat Video</a>
            </div>
            <div class="cp-pill-list">
                <article>
                    <span class="cp-pill-icon">M</span>
                    <div>
                        <strong>Program Musik</strong>
                        <small>Berkualitas</small>
                    </div>
                </article>
                <article>
                    <span class="cp-pill-icon">S</span>
                    <div>
                        <strong>Pengembangan</strong>
                        <small>Bakat Siswa</small>
                    </div>
                </article>
                <article>
                    <span class="cp-pill-icon">E</span>
                    <div>
                        <strong>Edukasi Musik</strong>
                        <small>Berkelanjutan</small>
                    </div>
                </article>
            </div>
        </div>
        <div class="cp-hero-media">
            <img src="https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=1400&q=80" alt="Siswa sekolah sedang belajar musik bersama">
        </div>
    </div>
</section>

<section class="cp-about">
    <div class="container cp-about-grid">
        <div class="cp-about-left">
            <span class="cp-kicker">TENTANG KAMI</span>
            <h2>Mitra Sekolah dalam<br>Mewujudkan Pendidikan Musik</h2>
            <p>ROFC Private Music adalah penyedia solusi pendidikan musik untuk sekolah. Kami percaya bahwa musik dapat membentuk karakter, meningkatkan kreativitas, dan membangun kepercayaan diri siswa.</p>
            <a href="{{ route('about') }}" class="btn btn-primary">Selengkapnya</a>
        </div>
        <div class="cp-about-right">
            <div class="cp-vision-list">
                <article>
                    <span>V</span>
                    <div>
                        <h3>Visi</h3>
                        <p>Menjadi mitra terpercaya sekolah dalam mewujudkan pendidikan musik berkualitas.</p>
                    </div>
                </article>
                <article>
                    <span>M</span>
                    <div>
                        <h3>Misi</h3>
                        <p>Memberikan layanan, produk, dan program musik terbaik yang mendukung perkembangan siswa.</p>
                    </div>
                </article>
                <article>
                    <span>N</span>
                    <div>
                        <h3>Nilai</h3>
                        <p>Kualitas, Integritas, Kolaborasi, dan Inspirasi.</p>
                    </div>
                </article>
            </div>
            <img src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80" alt="Gedung sekolah untuk program musik">
        </div>
    </div>
</section>

<section class="cp-stats">
    <div class="container cp-stats-wrap">
        <article><strong>150+</strong><span>Sekolah Bermitra</span></article>
        <article><strong>20.000+</strong><span>Siswa Terlibat</span></article>
        <article><strong>100+</strong><span>Program Musik</span></article>
        <article><strong>50+</strong><span>Prestasi Siswa</span></article>
    </div>
</section>

<section class="cp-services">
    <div class="container">
        <div class="cp-head">
            <h2>Layanan Kami</h2>
            <p>Solusi lengkap untuk mendukung program musik di sekolah Anda</p>
        </div>
        <div class="cp-service-grid">
            <article>
                <h3>Program Musik Sekolah</h3>
                <p>Kurikulum dan program musik yang dirancang sesuai kebutuhan sekolah.</p>
            </article>
            <article>
                <h3>Pengadaan Alat Musik</h3>
                <p>Penyediaan alat musik berkualitas dari berbagai merek terpercaya.</p>
            </article>
            <article>
                <h3>Pelatihan & Workshop</h3>
                <p>Pelatihan guru dan workshop siswa oleh instruktur berpengalaman.</p>
            </article>
            <article>
                <h3>Event & Kompetisi</h3>
                <p>Mendukung sekolah dalam kegiatan musik dan kompetisi siswa.</p>
            </article>
        </div>
    </div>
</section>
@endsection
