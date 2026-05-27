@extends('layouts.app')

@section('title', 'ROFC Private Music | Home')

@section('content')
<section class="cp-hero" style="position: relative; overflow: hidden; background: linear-gradient(to right, #eef3fa 0%, #ffffff 100%); padding-bottom: 2rem; min-height: 85vh; display: flex; align-items: center;">
    <!-- Fade mask image background -->
    <div style="position: absolute; right: 0; top: 0; width: 55%; height: 100%; z-index: 0;" class="hidden lg:block">
        <img src="{{ asset('images/hero-drummer.jpg') }}" alt="Drummer" style="width: 100%; height: 100%; object-fit: cover; object-position: right 10%; -webkit-mask-image: linear-gradient(to right, transparent 0%, black 25%); mask-image: linear-gradient(to right, transparent 0%, black 25%);">
    </div>

    <div class="container cp-hero-grid" style="position: relative; z-index: 10; display: block; width: 100%;">
        <div class="cp-hero-copy" style="max-width: 600px; background: transparent; border-radius: 0; padding: 2.6rem 0 1.7rem;">
            <span class="cp-kicker">ROFC Private Music</span>
            <h1 style="font-size: clamp(2.5rem, 4vw, 3.8rem);">Music for<br>a Better <span>Education</span></h1>
            <p style="font-size: 1.1rem; max-width: 500px;">ROFC Private Music hadir untuk mendukung sekolah dalam membangun program musik yang berkualitas, inspiratif, dan berkelanjutan.</p>
            <div class="cp-hero-actions">
                <a href="{{ route('about') }}" class="btn btn-primary" style="border-radius: 999px;">Tentang Kami</a>
                <button onclick="document.getElementById('videoModal').style.display = 'flex'" class="btn btn-outline cp-video-btn" style="border-radius: 999px; cursor: pointer;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #1a56db;"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    Lihat Video
                </button>
            </div>
            <div class="cp-pill-list" style="margin-top: 2rem;">
                <article style="border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 1rem;">
                    <span class="cp-pill-icon" style="background: #0f2554;">M</span>
                    <div>
                        <strong>Program Musik</strong>
                        <small>Berkualitas</small>
                    </div>
                </article>
                <article style="border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 1rem;">
                    <span class="cp-pill-icon" style="background: #0f2554;">S</span>
                    <div>
                        <strong>Pengembangan</strong>
                        <small>Bakat Siswa</small>
                    </div>
                </article>
                <article style="border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 1rem;">
                    <span class="cp-pill-icon" style="background: #0f2554;">E</span>
                    <div>
                        <strong>Edukasi Musik</strong>
                        <small>Berkelanjutan</small>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <!-- Video Modal Popup -->
    <div id="videoModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.85); align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <!-- Background click to close -->
        <div style="position: absolute; inset: 0;" onclick="document.getElementById('videoModal').style.display = 'none'; document.getElementById('youtubeVideo').contentWindow.postMessage('{\&quot;event\&quot;:\&quot;command\&quot;,\&quot;func\&quot;:\&quot;pauseVideo\&quot;,\&quot;args\&quot;:\"\"}', '*');"></div>
        
        <!-- Video Container -->
        <div style="position: relative; width: 90%; max-width: 900px; aspect-ratio: 16/9; background: #000; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); z-index: 10001; overflow: hidden;">
            <button onclick="document.getElementById('videoModal').style.display = 'none'; document.getElementById('youtubeVideo').contentWindow.postMessage('{\&quot;event\&quot;:\&quot;command\&quot;,\&quot;func\&quot;:\&quot;pauseVideo\&quot;,\&quot;args\&quot;:\"\"}', '*');" style="position: absolute; top: -40px; right: 0; background: transparent; color: white; border: none; font-size: 24px; cursor: pointer; opacity: 0.8;">✕</button>
            
            <!-- Ganti URL src di bawah ini dengan URL embed video YouTube/Google Drive Anda -->
            <!-- Tambahkan ?enablejsapi=1 agar video berhenti saat modal ditutup -->
            <iframe id="youtubeVideo" src="https://www.youtube.com/embed/NpEaa2P7qZI?enablejsapi=1" title="Video ROFC" style="width: 100%; height: 100%; border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
