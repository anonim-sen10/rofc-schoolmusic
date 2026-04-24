@extends('layouts.app')

@section('title', 'ROFC Private Music | Events')

@section('content')
<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Events</span>
        <h1>Event & Performance</h1>
        <p>Kegiatan rutin dan spesial untuk membangun pengalaman panggung siswa.</p>
    </div>
</section>

<section class="section">
    <div class="container card-grid two">
        <article class="event-card">
            <span class="tag">Workshop</span>
            <h3>Creative Drum Workshop</h3>
            <p>20 Mei 2026 - Studio Hall ROFC</p>
            <p>Eksplorasi ritme modern, rudiment, dan stage setup untuk remaja & dewasa.</p>
        </article>
        <article class="event-card">
            <span class="tag">Recital</span>
            <h3>Student Recital Night</h3>
            <p>12 Juni 2026 - Auditorium Mini ROFC</p>
            <p>Ajang tampil siswa lintas kelas untuk melatih mental panggung.</p>
        </article>
        <article class="event-card">
            <span class="tag">Performance</span>
            <h3>Young Talent Showcase</h3>
            <p>5 Juli 2026 - Creative Mall Stage</p>
            <p>Performance siswa pilihan dengan format band ensemble.</p>
        </article>
        <article class="event-card">
            <span class="tag">Special</span>
            <h3>ROFC Internal Concert</h3>
            <p>20 Agustus 2026 - Grand Hall</p>
            <p>Konser internal tahunan sebagai apresiasi progres siswa.</p>
        </article>
    </div>
</section>
@endsection
