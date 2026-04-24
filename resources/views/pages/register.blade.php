@extends('layouts.app')

@section('title', 'ROFC Private Music | Registration')

@section('content')
<style>
    .register-page {
        background: #f8fafc;
        color: #0f172a;
        padding: 1.2rem 0 4rem;
    }

    .register-animate-in {
        animation: register-fade-in 0.65s ease both;
    }

    @keyframes register-fade-in {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .register-hero {
        position: relative;
        overflow: hidden;
        min-height: 168px;
        max-height: 200px;
        border-radius: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.32);
        background:
            radial-gradient(circle at 12% 12%, rgba(37, 99, 235, 0.3), transparent 42%),
            radial-gradient(circle at 86% 80%, rgba(147, 197, 253, 0.24), transparent 35%),
            linear-gradient(130deg, #0b1e3c 0%, #13356a 56%, #0f2b58 100%);
        box-shadow: 0 24px 60px rgba(11, 30, 60, 0.24);
        display: flex;
        align-items: center;
    }

    .register-hero-note {
        position: absolute;
        color: rgba(191, 219, 254, 0.58);
        font-size: 1.8rem;
        pointer-events: none;
        animation: note-float 5.8s ease-in-out infinite;
    }

    .register-hero-note.alt {
        animation-delay: 1.2s;
        animation-duration: 6.8s;
    }

    .register-hero-note.alt-2 {
        animation-delay: 2.1s;
        animation-duration: 5s;
    }

    .register-wave {
        position: absolute;
        border: 1px solid rgba(191, 219, 254, 0.2);
        border-radius: 999px;
        width: 280px;
        height: 280px;
        opacity: 0.8;
        filter: blur(0.5px);
    }

    .register-wave::before,
    .register-wave::after {
        content: "";
        position: absolute;
        border: 1px solid rgba(191, 219, 254, 0.16);
        border-radius: 999px;
        inset: 14px;
    }

    .register-wave::after {
        inset: 28px;
    }

    @keyframes note-float {
        0%, 100% {
            transform: translateY(0);
            opacity: 0.6;
        }

        50% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    .register-step-wrap {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.75rem;
        margin: 1.3rem 0 1.4rem;
    }

    .register-step {
        display: flex;
        align-items: center;
        gap: 0.62rem;
        border: 1px solid #dbe5f3;
        border-radius: 0.95rem;
        background: #ffffff;
        padding: 0.72rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        transition: all 0.2s ease;
    }

    .register-step-dot {
        width: 1.7rem;
        height: 1.7rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.74rem;
        font-weight: 700;
        color: #fff;
        background: #2563eb;
        flex: 0 0 auto;
    }

    .register-step-muted .register-step-dot {
        background: #94a3b8;
    }

    .register-step.is-active {
        border-color: rgba(37, 99, 235, 0.35);
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.14);
    }

    .register-step.is-complete .register-step-dot {
        background: #16a34a;
    }

    .register-step p,
    .register-step small {
        margin: 0;
        line-height: 1.35;
    }

    .register-step p {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0f172a;
    }

    .register-step small {
        font-size: 0.73rem;
        color: #64748b;
    }

    .register-glass-card {
        border: 1px solid rgba(148, 163, 184, 0.28);
        border-radius: 1.2rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.86));
        backdrop-filter: blur(6px);
        box-shadow: 0 30px 70px rgba(15, 23, 42, 0.12);
        padding: 1rem;
    }

    .register-step-panel[hidden] {
        display: none !important;
    }

    .register-group {
        margin-bottom: 1rem;
        border: 1px solid #e4ebf5;
        border-radius: 1rem;
        background: #fff;
        padding: 1rem;
    }

    .register-group:last-of-type {
        margin-bottom: 0.5rem;
    }

    .register-group-head {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        margin-bottom: 0.8rem;
    }

    .register-group-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.7rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        background: #e9f0fe;
        flex: 0 0 auto;
    }

    .register-group h3 {
        margin: 0;
        color: #0b1e3c;
        font-size: 1rem;
        font-weight: 700;
    }

    .register-group p {
        margin: 0.08rem 0 0;
        color: #64748b;
        font-size: 0.79rem;
    }

    .register-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.8rem 0.95rem;
    }

    .register-grid .full {
        grid-column: 1 / -1;
    }

    .register-field {
        display: grid;
        gap: 0.35rem;
    }

    .register-field > span {
        color: #334155;
        font-size: 0.81rem;
        font-weight: 600;
    }

    .register-input {
        width: 100%;
        border: 1px solid #d5e0ef;
        border-radius: 0.78rem;
        background: #f8fbff;
        color: #0f172a;
        padding: 0.68rem 0.8rem;
        font: inherit;
        transition: all 0.2s ease;
    }

    .register-input::placeholder {
        color: #94a3b8;
    }

    .register-input:focus {
        outline: none;
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        transform: translateY(-1px);
    }

    .register-choice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.48rem 0.8rem;
        margin: 0;
    }

    .register-choice-grid label {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: #334155;
        font-size: 0.86rem;
    }

    .register-choice-grid input[type="checkbox"],
    .register-choice-grid input[type="radio"] {
        width: 1rem;
        height: 1rem;
        margin: 0;
        accent-color: #2563eb;
    }

    .register-submit {
        width: 100%;
        border: 0;
        border-radius: 0.82rem;
        padding: 0.78rem 1rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 16px 30px rgba(37, 99, 235, 0.28);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .register-submit:hover {
        transform: scale(1.01) translateY(-1px);
        box-shadow: 0 20px 36px rgba(37, 99, 235, 0.35);
    }

    .register-submit:focus-visible {
        outline: 0;
        box-shadow:
            0 0 0 4px rgba(37, 99, 235, 0.16),
            0 16px 30px rgba(37, 99, 235, 0.28);
    }

    .register-action-row {
        margin-top: 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.7rem;
        flex-wrap: wrap;
    }

    .register-btn-secondary {
        border: 1px solid #cbd5e1;
        border-radius: 0.82rem;
        padding: 0.72rem 1rem;
        font-weight: 700;
        color: #0f172a;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .register-btn-secondary:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }

    .register-confirm-wrap {
        border: 1px solid #dbe5f3;
        border-radius: 1rem;
        background: #fff;
        padding: 1rem;
    }

    .register-confirm-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.7rem;
    }

    .register-confirm-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background: #f8fbff;
        padding: 0.68rem 0.75rem;
    }

    .register-confirm-item span {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.18rem;
    }

    .register-confirm-item p {
        margin: 0;
        font-size: 0.87rem;
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
    }

    .register-confirm-item.full {
        grid-column: 1 / -1;
    }

    .register-success {
        border: 1px solid #bbebc7;
        border-radius: 0.75rem;
        background: #ebfaef;
        color: #1f6f36;
        padding: 0.72rem 0.85rem;
        margin-bottom: 0.9rem;
    }

    .register-errors {
        margin: 0.8rem 0 0;
        padding-left: 1rem;
        color: #b4233a;
        font-size: 0.87rem;
    }

    @media (max-width: 900px) {
        .register-step-wrap {
            grid-template-columns: 1fr;
        }

        .register-grid {
            grid-template-columns: 1fr;
        }

        .register-choice-grid {
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        }

        .register-confirm-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="register-page">
    <div class="container register-animate-in">
        <div class="register-hero">
            <span class="register-wave -left-36 -top-20"></span>
            <span class="register-wave -right-32 -bottom-36"></span>
            <span class="register-hero-note" style="left: 6%; top: 25%;">&#9834;</span>
            <span class="register-hero-note alt" style="right: 14%; top: 26%;">&#9835;</span>
            <span class="register-hero-note alt-2" style="right: 11%; bottom: 16%;">&#9836;</span>

            <div style="position: relative; padding: 1.35rem 1.4rem; width: 100%;">
                <span style="display: inline-block; font-size: 0.74rem; font-weight: 700; color: #bfdbfe; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.38rem;">
                    Registration
                </span>
                <h1 style="margin: 0; color: #f8fbff; font-size: clamp(1.4rem, 2.7vw, 2rem); line-height: 1.2; font-weight: 700;">
                    Form Pendaftaran Siswa Baru
                </h1>
                <p style="margin: 0.45rem 0 0; color: #dbeafe; max-width: 640px; font-size: 0.95rem;">
                    Isi data berikut untuk bergabung dengan ROFC Private Music.
                </p>
            </div>
        </div>

        <div class="register-step-wrap register-animate-in" style="animation-delay: 0.1s;">
            <article class="register-step is-active" data-step-indicator="1">
                <span class="register-step-dot">1</span>
                <div>
                    <p>Data Siswa</p>
                    <small>Informasi personal siswa</small>
                </div>
            </article>
            <article class="register-step register-step-muted" data-step-indicator="2">
                <span class="register-step-dot">2</span>
                <div>
                    <p>Pilihan Program</p>
                    <small>Kelas dan jadwal belajar</small>
                </div>
            </article>
            <article class="register-step register-step-muted" data-step-indicator="3">
                <span class="register-step-dot">3</span>
                <div>
                    <p>Konfirmasi</p>
                    <small>Kirim pendaftaran siswa</small>
                </div>
            </article>
        </div>

        <div class="container narrow register-animate-in" style="padding: 0; animation-delay: 0.2s;">
            <div class="register-glass-card">
                @if (session('success'))
                    <div class="register-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST" id="register-form" class="rofc-form" style="gap: 0;">
                    @csrf

                    <div class="register-step-panel" data-step-panel="1">
                        <section class="register-group">
                            <div class="register-group-head">
                                <span class="register-group-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="8" r="3.5" />
                                        <path d="M4.5 19.5c1.6-3 4.3-4.5 7.5-4.5s5.9 1.5 7.5 4.5" />
                                    </svg>
                                </span>
                                <div>
                                    <h3>Data Siswa</h3>
                                    <p>Lengkapi identitas dasar siswa.</p>
                                </div>
                            </div>

                            <div class="register-grid">
                                <label class="register-field">
                                    <span>Nama Lengkap</span>
                                    <input class="register-input" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap siswa" required>
                                </label>

                                <label class="register-field">
                                    <span>Nama Panggilan</span>
                                    <input class="register-input" type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" placeholder="Masukkan nama panggilan siswa" required>
                                </label>

                                <div class="register-field full">
                                    <span>Jenis Kelamin</span>
                                    <div class="register-choice-grid">
                                        <label>
                                            <input type="radio" name="jenis_kelamin" value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'checked' : '' }} required>
                                            Laki-laki
                                        </label>
                                        <label>
                                            <input type="radio" name="jenis_kelamin" value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }} required>
                                            Perempuan
                                        </label>
                                    </div>
                                </div>

                                <label class="register-field">
                                    <span>Tempat Lahir</span>
                                    <input class="register-input" type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Masukkan tempat lahir" required>
                                </label>

                                <label class="register-field">
                                    <span>Tanggal Lahir</span>
                                    <input class="register-input" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                </label>

                                <label class="register-field">
                                    <span>Kewarganegaraan</span>
                                    <input class="register-input" type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" required>
                                </label>

                                <label class="register-field">
                                    <span>No HP Siswa</span>
                                    <input class="register-input" type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}" placeholder="08xxxxxxxxxx" required>
                                </label>

                                <label class="register-field full">
                                    <span>Alamat</span>
                                    <textarea class="register-input" name="alamat" rows="3" placeholder="Masukkan alamat lengkap siswa" required>{{ old('alamat') }}</textarea>
                                </label>

                                <label class="register-field full">
                                    <span>Email</span>
                                    <input class="register-input" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                </label>
                            </div>
                        </section>

                        <section class="register-group">
                            <div class="register-group-head">
                                <span class="register-group-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="M3 8.5h18M6.5 4h11A1.5 1.5 0 0 1 19 5.5v13A1.5 1.5 0 0 1 17.5 20h-11A1.5 1.5 0 0 1 5 18.5v-13A1.5 1.5 0 0 1 6.5 4Z" />
                                        <path d="M9 13h6M9 16h3" />
                                    </svg>
                                </span>
                                <div>
                                    <h3>Data Orang Tua</h3>
                                    <p>Informasi pendamping siswa.</p>
                                </div>
                            </div>

                            <div class="register-grid">
                                <label class="register-field">
                                    <span>Nama Orang Tua</span>
                                    <input class="register-input" type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required>
                                </label>

                                <label class="register-field">
                                    <span>Pekerjaan Orang Tua</span>
                                    <input class="register-input" type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}">
                                </label>

                                <label class="register-field">
                                    <span>No HP Orang Tua</span>
                                    <input class="register-input" type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required>
                                </label>

                                <label class="register-field">
                                    <span>Email Orang Tua</span>
                                    <input class="register-input" type="email" name="email_ortu" value="{{ old('email_ortu') }}">
                                </label>
                            </div>
                        </section>

                        <div class="register-action-row">
                            <span style="font-size: 0.82rem; color: #64748b;">Step 1 of 3</span>
                            <button type="button" class="register-submit" style="width: auto; min-width: 156px;" data-step-next>Lanjut ke Pilihan Program</button>
                        </div>
                    </div>

                    <div class="register-step-panel" data-step-panel="2" hidden>
                        <section class="register-group">
                            <div class="register-group-head">
                                <span class="register-group-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="M9 18V5l10-2v13" />
                                        <circle cx="6" cy="18" r="3" />
                                        <circle cx="16" cy="16" r="3" />
                                    </svg>
                                </span>
                                <div>
                                    <h3>Program dan Jadwal</h3>
                                    <p>Pilih instrumen dan jadwal yang tersedia.</p>
                                </div>
                            </div>

                            <div class="register-grid">
                                <label class="register-field">
                                    <span>Instrumen</span>
                                    <select class="register-input" name="class_id" required>
                                        <option value="">Pilih Instrumen</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ (string) old('class_id') === (string) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <label class="register-field">
                                    <span>Hari</span>
                                    <select class="register-input" name="day" id="day-select" required>
                                        <option value="">Pilih Hari</option>
                                        @foreach ($dayOptions as $hari)
                                            <option value="{{ $hari }}" {{ old('day') === $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <label class="register-field full">
                                    <span>Jam (Slot Tersedia)</span>
                                    <select class="register-input" name="schedule_id" id="schedule-select" required data-old-value="{{ old('schedule_id') }}">
                                        <option value="">Pilih class dan hari terlebih dahulu</option>
                                    </select>
                                </label>

                                <div class="register-field full">
                                    <span>Program Tambahan (opsional)</span>
                                    <div class="register-choice-grid">
                                        @php($oldProgramTambahan = old('program_tambahan', []))
                                        @foreach (['Teori Musik', 'Ensemble / Band', 'Skill Teknik (ajang kompetisi)', 'Ujian Sertifikat bertaraf international'] as $programTambahan)
                                            <label>
                                                <input type="checkbox" name="program_tambahan[]" value="{{ $programTambahan }}" {{ in_array($programTambahan, $oldProgramTambahan, true) ? 'checked' : '' }}>
                                                {{ $programTambahan }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="register-group">
                            <div class="register-group-head">
                                <span class="register-group-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="M14 4a7 7 0 1 0 7 7" />
                                        <path d="M14 4v7h7" />
                                    </svg>
                                </span>
                                <div>
                                    <h3>Pengalaman Musik</h3>
                                    <p>Informasi tambahan untuk penyesuaian kelas.</p>
                                </div>
                            </div>

                            <div class="register-grid">
                                <div class="register-field full">
                                    <span>Pernah belajar musik sebelumnya?</span>
                                    <div class="register-choice-grid">
                                        <label>
                                            <input type="radio" name="pengalaman" value="1" {{ old('pengalaman') === '1' ? 'checked' : '' }} required>
                                            Ya
                                        </label>
                                        <label>
                                            <input type="radio" name="pengalaman" value="0" {{ old('pengalaman') === '0' ? 'checked' : '' }} required>
                                            Tidak
                                        </label>
                                    </div>
                                </div>

                                <label class="register-field full">
                                    <span>Deskripsi Pengalaman</span>
                                    <textarea class="register-input" name="deskripsi_pengalaman" rows="4" placeholder="Ceritakan pengalaman belajar musik sebelumnya (opsional)">{{ old('deskripsi_pengalaman') }}</textarea>
                                </label>
                            </div>
                        </section>

                        <div class="register-action-row">
                            <button type="button" class="register-btn-secondary" data-step-prev>Kembali ke Data Siswa</button>
                            <button type="button" class="register-submit" style="width: auto; min-width: 156px;" data-step-next>Lanjut ke Konfirmasi</button>
                        </div>
                    </div>

                    <div class="register-step-panel" data-step-panel="3" hidden>
                        <section class="register-confirm-wrap">
                            <div class="register-group-head" style="margin-bottom: 0.75rem;">
                                <span class="register-group-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="m5 13 4 4L19 7" />
                                    </svg>
                                </span>
                                <div>
                                    <h3>Konfirmasi Data Pendaftaran</h3>
                                    <p>Periksa kembali data sebelum dikirim.</p>
                                </div>
                            </div>
                            <div class="register-confirm-grid">
                                <article class="register-confirm-item"><span>Nama Lengkap</span><p data-confirm="nama_lengkap">-</p></article>
                                <article class="register-confirm-item"><span>Nama Panggilan</span><p data-confirm="nama_panggilan">-</p></article>
                                <article class="register-confirm-item"><span>Jenis Kelamin</span><p data-confirm="jenis_kelamin">-</p></article>
                                <article class="register-confirm-item"><span>Tanggal Lahir</span><p data-confirm="tanggal_lahir">-</p></article>
                                <article class="register-confirm-item"><span>Kontak Siswa</span><p data-confirm="no_hp_siswa">-</p></article>
                                <article class="register-confirm-item"><span>Email Siswa</span><p data-confirm="email">-</p></article>
                                <article class="register-confirm-item"><span>Nama Orang Tua</span><p data-confirm="nama_ortu">-</p></article>
                                <article class="register-confirm-item"><span>Kontak Orang Tua</span><p data-confirm="no_hp_ortu">-</p></article>
                                <article class="register-confirm-item"><span>Instrumen</span><p data-confirm="class_id">-</p></article>
                                <article class="register-confirm-item"><span>Hari</span><p data-confirm="day">-</p></article>
                                <article class="register-confirm-item"><span>Jam</span><p data-confirm="schedule_id">-</p></article>
                                <article class="register-confirm-item"><span>Pengalaman Musik</span><p data-confirm="pengalaman">-</p></article>
                                <article class="register-confirm-item full"><span>Alamat</span><p data-confirm="alamat">-</p></article>
                                <article class="register-confirm-item full"><span>Program Tambahan</span><p data-confirm="program_tambahan">-</p></article>
                                <article class="register-confirm-item full"><span>Deskripsi Pengalaman</span><p data-confirm="deskripsi_pengalaman">-</p></article>
                            </div>
                        </section>
                        <div class="register-action-row">
                            <button type="button" class="register-btn-secondary" data-step-prev>Kembali ke Pilihan Program</button>
                            <button type="submit" class="register-submit" style="width: auto; min-width: 156px;" id="register-submit-final">Kirim Pendaftaran</button>
                        </div>
                    </div>
                </form>

                @if ($errors->any())
                    <ul class="register-errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const registerForm = document.getElementById('register-form');
        const stepPanels = Array.from(document.querySelectorAll('[data-step-panel]'));
        const stepIndicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
        const nextButtons = Array.from(document.querySelectorAll('[data-step-next]'));
        const prevButtons = Array.from(document.querySelectorAll('[data-step-prev]'));
        const submitFinalButton = document.getElementById('register-submit-final');
        let currentStep = 1;

        const getStepPanel = (stepNumber) => document.querySelector(`[data-step-panel="${stepNumber}"]`);

        const getPanelInputs = (panel) => {
            if (!panel) return [];
            return Array.from(panel.querySelectorAll('input, select, textarea')).filter((input) => {
                return input.type !== 'button' && input.type !== 'submit';
            });
        };

        const validateStep = (stepNumber) => {
            const panel = getStepPanel(stepNumber);
            const fields = getPanelInputs(panel).filter((field) => {
                if (field.disabled || field.type === 'hidden') return false;
                return field.hasAttribute('required');
            });

            for (const field of fields) {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return false;
                }
            }

            return true;
        };

        const getFieldDisplayValue = (fieldName) => {
            if (!registerForm) return '-';
            const field = registerForm.elements[fieldName];
            if (!field) return '-';

            if (field instanceof RadioNodeList) {
                if (fieldName === 'program_tambahan') {
                    const values = Array.from(field)
                        .filter((item) => item.checked)
                        .map((item) => item.value);
                    return values.length ? values.join(', ') : '-';
                }

                const checked = Array.from(field).find((item) => item.checked);
                if (!checked) return '-';
                if (fieldName === 'pengalaman') {
                    return checked.value === '1' ? 'Ya' : 'Tidak';
                }

                return checked.value || '-';
            }

            if (field instanceof HTMLSelectElement) {
                if (field.selectedIndex < 0) return '-';
                return field.options[field.selectedIndex]?.text?.trim() || '-';
            }

            const value = String(field.value || '').trim();
            return value === '' ? '-' : value;
        };

        const refreshConfirmation = () => {
            const confirmTargets = Array.from(document.querySelectorAll('[data-confirm]'));
            confirmTargets.forEach((target) => {
                const key = target.getAttribute('data-confirm');
                if (!key) return;
                target.textContent = getFieldDisplayValue(key);
            });
        };

        const setStep = (stepNumber) => {
            currentStep = stepNumber;

            stepPanels.forEach((panel) => {
                const panelStep = Number(panel.getAttribute('data-step-panel'));
                const isCurrent = panelStep === currentStep;
                panel.hidden = !isCurrent;

                const panelInputs = getPanelInputs(panel);
                panelInputs.forEach((input) => {
                    input.disabled = !isCurrent;
                });
            });

            stepIndicators.forEach((indicator) => {
                const indicatorStep = Number(indicator.getAttribute('data-step-indicator'));
                indicator.classList.toggle('is-active', indicatorStep === currentStep);
                indicator.classList.toggle('register-step-muted', indicatorStep !== currentStep);
                indicator.classList.toggle('is-complete', indicatorStep < currentStep);
            });

            if (currentStep === 3) {
                refreshConfirmation();
            }
        };

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (!validateStep(currentStep)) {
                    return;
                }

                if (currentStep < 3) {
                    setStep(currentStep + 1);
                }
            });
        });

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (currentStep > 1) {
                    setStep(currentStep - 1);
                }
            });
        });

        if (submitFinalButton && registerForm) {
            submitFinalButton.addEventListener('click', (event) => {
                event.preventDefault();

                const step1Valid = validateStep(1);
                const step2Valid = validateStep(2);
                if (!step1Valid || !step2Valid) {
                    setStep(!step1Valid ? 1 : 2);
                    return;
                }

                stepPanels.forEach((panel) => {
                    getPanelInputs(panel).forEach((input) => {
                        input.disabled = false;
                    });
                });

                registerForm.submit();
            });
        }

        setStep(1);

        const classSelect = document.querySelector('select[name="class_id"]');
        const daySelect = document.getElementById('day-select');
        const scheduleSelect = document.getElementById('schedule-select');
        const oldScheduleId = scheduleSelect?.dataset.oldValue || '';

        const resetScheduleOptions = (message) => {
            if (!scheduleSelect) return;

            scheduleSelect.innerHTML = '';

            const option = document.createElement('option');
            option.value = '';
            option.textContent = message;

            scheduleSelect.appendChild(option);
            scheduleSelect.value = '';
        };

        const loadSchedules = async () => {
            if (!classSelect || !daySelect || !scheduleSelect) {
                return;
            }

            const classId = classSelect.value;
            const day = daySelect.value;

            if (!classId || !day) {
                resetScheduleOptions('Pilih class dan hari terlebih dahulu');
                return;
            }

            resetScheduleOptions('Memuat jadwal...');

            try {
                const endpoint = `/get-available-schedules/${encodeURIComponent(classId)}/${encodeURIComponent(day)}`;
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data jadwal');
                }

                const data = await response.json();
                const schedules = Array.isArray(data.schedules) ? data.schedules : [];

                scheduleSelect.innerHTML = '';

                if (schedules.length === 0) {
                    resetScheduleOptions('Tidak ada slot tersedia untuk pilihan ini');
                    return;
                }

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.textContent = 'Pilih Jam';
                scheduleSelect.appendChild(placeholderOption);

                schedules.forEach((schedule) => {
                    const option = document.createElement('option');
                    option.value = String(schedule.id);
                    option.textContent = schedule.label ?? `${schedule.day} - ${schedule.time}`;
                    scheduleSelect.appendChild(option);
                });

                if (oldScheduleId !== '') {
                    const exists = schedules.some((schedule) => String(schedule.id) === String(oldScheduleId));
                    if (exists) {
                        scheduleSelect.value = String(oldScheduleId);
                    }
                }
            } catch (error) {
                resetScheduleOptions('Terjadi kesalahan saat memuat jadwal');
            }
        };

        classSelect?.addEventListener('change', loadSchedules);
        daySelect?.addEventListener('change', loadSchedules);

        if (classSelect && daySelect && classSelect.value && daySelect.value) {
            loadSchedules();
        }
    });
</script>
@endsection
