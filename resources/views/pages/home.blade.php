@extends('layouts.app')

@section('title', 'ROFC School Music | Home')

@section('content')
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <span class="eyebrow">ROFC School Music</span>
        <h1>Bangun Bakat Musikmu Bersama ROFC School Music</h1>
        <p>Tempat belajar musik yang nyaman, modern, dan inspiratif untuk anak-anak, remaja, hingga dewasa dengan pendekatan kreatif dan profesional.</p>
        <div class="cta-row">
            <a href="{{ route('register') }}" class="btn btn-gold">Daftar Sekarang</a>
            <a href="{{ route('programs') }}" class="btn btn-outline">Lihat Program</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container two-col">
        <div>
            <span class="eyebrow">Tentang Kami</span>
            <h2>Wadah Belajar Musik yang Kreatif dan Profesional</h2>
            <p>ROFC School Music hadir untuk mendampingi setiap siswa menemukan karakter musikalnya. Kami menggabungkan teknik, kreativitas, dan suasana belajar yang menyenangkan.</p>
        </div>
        <div class="stats-grid">
            <article><strong>12+</strong><span>Tahun Pengalaman</span></article>
            <article><strong>600+</strong><span>Siswa Aktif & Alumni</span></article>
            <article><strong>25+</strong><span>Program & Kelas</span></article>
            <article><strong>40+</strong><span>Event & Recital Tahunan</span></article>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Keunggulan</span>
            <h2>Mengapa Memilih ROFC</h2>
        </div>
        <div class="card-grid three">
            <article class="feature-card"><h3>Mentor Berpengalaman</h3><p>Pengajar profesional dengan pendekatan ramah, terstruktur, dan adaptif.</p></article>
            <article class="feature-card"><h3>Kurikulum Bertahap</h3><p>Silabus disusun dari pemula hingga lanjutan sesuai target perkembangan siswa.</p></article>
            <article class="feature-card"><h3>Studio Nyaman</h3><p>Fasilitas belajar modern untuk meningkatkan fokus, motivasi, dan kreativitas.</p></article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Program Unggulan</span>
            <h2>Kelas Musik Favorit</h2>
        </div>
        <div class="card-grid three">
            <article class="program-card"><h3>Drum Class</h3><p>Teknik ritme, groove, dan performa panggung.</p></article>
            <article class="program-card"><h3>Piano Class</h3><p>Dari basic chord hingga ekspresi musikal tingkat lanjut.</p></article>
            <article class="program-card"><h3>Vocal Class</h3><p>Latihan pernapasan, pitch control, dan karakter vokal.</p></article>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-head split">
            <div>
                <span class="eyebrow">Teachers Preview</span>
                <h2>Belajar dengan Pengajar Berkualitas</h2>
            </div>
            <a href="{{ route('teachers') }}" class="btn btn-outline">Lihat Semua Guru</a>
        </div>
        <div class="card-grid three">
            <article class="teacher-card"><h3>Andra Prakoso</h3><p>Drum Specialist - 10 tahun pengalaman live & studio.</p></article>
            <article class="teacher-card"><h3>Nadia Putri</h3><p>Vocal Coach - fokus teknik vokal modern & stage confidence.</p></article>
            <article class="teacher-card"><h3>Kevin Hartono</h3><p>Piano Mentor - klasik, pop, dan improvisasi kreatif.</p></article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head split">
            <div>
                <span class="eyebrow">Gallery Preview</span>
                <h2>Momen Belajar & Performance</h2>
            </div>
            <a href="{{ route('gallery') }}" class="btn btn-outline">Lihat Gallery</a>
        </div>
        <div class="gallery-grid">
            <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?auto=format&fit=crop&w=800&q=80" alt="Sesi latihan drum">
            <img src="https://images.unsplash.com/photo-1461783436728-0a9217714694?auto=format&fit=crop&w=800&q=80" alt="Siswa belajar gitar">
            <img src="https://images.unsplash.com/photo-1514119412350-e174d90d280e?auto=format&fit=crop&w=800&q=80" alt="Performance siswa">
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Testimonial</span>
            <h2>Apa Kata Siswa & Orang Tua</h2>
        </div>
        <div class="card-grid three">
            <article class="testimonial-card"><p>"Anak saya jadi lebih percaya diri tampil di depan umum setelah ikut kelas vocal di ROFC."</p><strong>- Ibu Rina</strong></article>
            <article class="testimonial-card"><p>"Belajarnya terarah, studio nyaman, dan gurunya sangat suportif."</p><strong>- Arman, Siswa Drum</strong></article>
            <article class="testimonial-card"><p>"Program pianonya cocok untuk pemula dewasa seperti saya."</p><strong>- Devina</strong></article>
        </div>
    </div>
</section>

<section class="section callout">
    <div class="container callout-inner">
        <div>
            <h2>Siap Mulai Perjalanan Musikmu?</h2>
            <p>Bergabunglah bersama ROFC School Music dan kembangkan bakat terbaikmu dari sekarang.</p>
        </div>
        <a href="{{ route('register') }}" class="btn btn-gold">Join Us</a>
    </div>
</section>
@endsection
