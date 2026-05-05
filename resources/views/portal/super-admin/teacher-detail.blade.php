@extends('portal.layout')

@section('title', 'Detail Teacher | ROFC')
@section('page-title', 'Detail Teacher')

@section('content')
<section class="card module-head">
    <h2>{{ $teacher->name }}</h2>
    <p>Detail data akun dan profil teacher.</p>
</section>

<section class="card">
    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0;">
        @if($teacher->photo_path)
            <img src="{{ asset('storage/' . $teacher->photo_path) }}" style="width: 100px; height: 100px; border-radius: 1rem; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);" onclick="showLightbox(this.src)">
        @else
            <div style="width: 100px; height: 100px; border-radius: 1rem; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: #94a3b8; border: 3px solid #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h3 style="margin: 0; font-size: 1.25rem;">{{ $teacher->name }}</h3>
            <p style="margin: 0.25rem 0 0; color: #64748b;">{{ $teacher->instrument ?? 'General Teacher' }}</p>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <tbody>
                <tr><th>Nama</th><td>{{ $teacher->name }}</td></tr>
                <tr><th>Email</th><td>{{ $teacher->user?->email ?? '-' }}</td></tr>
                <tr><th>Nomor HP</th><td>{{ $teacher->phone ?? '-' }}</td></tr>
                <tr><th>Alamat</th><td>{{ $teacher->address ?? '-' }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>{{ $teacher->gender ?? '-' }}</td></tr>
                <tr><th>Agama</th><td>{{ $teacher->religion ?? '-' }}</td></tr>
                <tr><th>Bidang / Instrumen</th><td>{{ $teacher->instrument }}</td></tr>
                <tr><th>Class</th><td>{{ $teacher->classes->pluck('name')->implode(', ') ?: '-' }}</td></tr>
                <tr>
                    <th>KTP Guru</th>
                    <td>
                        @if($teacher->ktp_path)
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ asset('storage/' . $teacher->ktp_path) }}" style="width: 100%; max-width: 300px; border-radius: 0.5rem; cursor: zoom-in; border: 1px solid #e2e8f0;" onclick="showLightbox(this.src)">
                            </div>
                        @else
                            <span style="color: #ef4444; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-lucide="alert-circle" style="width: 1rem; height: 1rem;"></i> Belum diupload
                            </span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem; display: flex; gap: 0.6rem;">
        <a href="{{ route('super-admin.teachers.edit', $teacher) }}" class="logout-btn">Edit</a>
        <a href="{{ route('super-admin.module', ['module' => 'teachers']) }}" class="logout-btn">Kembali</a>
    </div>
</section>
@endsection
