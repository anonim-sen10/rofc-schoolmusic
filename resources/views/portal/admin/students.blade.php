@php
$menuItems = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Classes', 'url' => route('admin.classes.index')],
    ['label' => 'Teachers', 'url' => route('admin.teachers.index')],
    ['label' => 'Students', 'url' => route('admin.students.index')],
    ['label' => 'Registrations', 'url' => route('admin.registrations.index')],
    ['label' => 'Schedule', 'url' => route('admin.schedule.index')],
    ['label' => 'Attendance Monitoring', 'url' => route('admin.attendance.index'), 'icon' => 'check-circle'],
    ['label' => 'Reschedule Requests', 'url' => route('admin.module', ['module' => 'reschedule']), 'icon' => 'refresh-cw'],
    ['label' => 'Gallery', 'url' => route('admin.module', ['module' => 'gallery'])],
    ['label' => 'Blog', 'url' => route('admin.module', ['module' => 'blog'])],
    ['label' => 'Events', 'url' => route('admin.module', ['module' => 'events'])],
    ['label' => 'Testimonials', 'url' => route('admin.module', ['module' => 'testimonials'])],
];
$panelTitle = 'Admin Dashboard';
$homeRoute = route('admin.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'Students Management')
@section('page-title', 'Students Management')
@section('content')
<style>
    /* Registration Modal / Popover Styles */
    .action-popover {
        position: relative;
        display: inline-block;
    }
    .action-popover summary {
        list-style: none;
        cursor: pointer;
    }
    .action-popover summary::-webkit-details-marker {
        display: none;
    }
    .action-popover[open] .action-popover-form {
        display: block;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translate(-50%, -50%) scale(1);
    }
    .action-popover-form {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.95);
        z-index: 1000;
        width: min(650px, 95vw);
        max-height: 90vh;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid #e2e8f0;
        display: none;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: all 0.2s ease;
        overflow: hidden;
        color: #1e293b;
        text-align: left;
    }
    /* Overlay effect using a fixed background when open */
    .action-popover[open]::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 999;
    }

    .registration-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .registration-modal-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .registration-modal-icon {
        width: 2.5rem;
        height: 2.5rem;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .registration-modal-header h3 { margin: 0; font-size: 1.1rem; font-weight: 700; color: #0f172a; }
    .registration-modal-header p { margin: 0.1rem 0 0; font-size: 0.85rem; color: #64748b; }
    
    .registration-modal-close-btn {
        background: none; border: none; color: #64748b; cursor: pointer; padding: 0.5rem; border-radius: 0.5rem;
    }
    .registration-modal-close-btn:hover { background: #f1f5f9; color: #0f172a; }

    .registration-modal-body {
        padding: 1.25rem;
        overflow-y: auto;
        max-height: calc(90vh - 130px);
    }
    .registration-modal-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f1f5f9;
        border-radius: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .registration-modal-summary-left { display: flex; align-items: center; gap: 0.75rem; }
    .registration-modal-avatar {
        width: 3rem; height: 3rem; background: #2563eb; color: #fff;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.25rem;
    }
    .registration-modal-summary-name { font-weight: 700; color: #0f172a; margin: 0; }
    .registration-modal-summary p { margin: 0; font-size: 0.85rem; color: #64748b; }

    .registration-modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .registration-modal-grid article {
        padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem;
    }
    .registration-modal-grid article p:first-child { margin: 0; font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em; }
    .registration-modal-grid article p:last-child { margin: 0.25rem 0 0; font-weight: 600; color: #0f172a; }
    .registration-modal-item-full { grid-column: 1 / -1; }

    .registration-edit-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;
    }
    .registration-edit-field { display: flex; flex-direction: column; gap: 0.4rem; }
    .registration-edit-field-full { grid-column: 1 / -1; }
    .registration-edit-label { font-size: 0.85rem; font-weight: 600; color: #334155; }
    .registration-edit-field input, 
    .registration-edit-field select, 
    .registration-edit-field textarea {
        padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.9rem;
    }

    .registration-modal-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        background: #f8fafc;
    }
    .registration-modal-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 0.65rem 1.25rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.875rem;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .registration-modal-btn i {
        width: 1.15rem;
        height: 1.15rem;
    }
    .registration-modal-btn-secondary { background: #fff; border: 1px solid #e2e8f0; color: #475569; }
    .registration-modal-btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .registration-modal-btn-primary { 
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); 
        border: 1px solid #1d4ed8; 
        color: #fff; 
    }
    .registration-modal-btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }
    .registration-modal-btn-danger {
        background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        border: 1px solid #be123c;
        color: #fff;
    }
    .registration-modal-btn-danger:hover {
        background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
        box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.3);
    }

    .action-icons { display: flex; gap: 0.5rem; align-items: center; }
</style>
<div class="split-grid-sa" data-searchable>
    <x-ui.card title="Tambah Siswa" subtitle="Registrasi manual siswa baru">
        <form class="module-form module-form-grid" method="POST" action="{{ route('admin.students.store') }}">
            @csrf
            <label>Nama <input type="text" name="name" required></label>
            <label>Umur <input type="number" name="age"></label>
            <label>Phone <input type="text" name="phone"></label>
            <label>Email <input type="email" name="email"></label>
            <label>Address <textarea name="address" rows="2"></textarea></label>
            <label>Kelas
                <select multiple name="class_ids[]">@foreach($classList as $classItem)<option value="{{ $classItem->id }}">{{ $classItem->name }}</option>@endforeach</select>
            </label>
            <label>Start Date <input type="date" name="start_date" value="{{ date('Y-m-d') }}"></label>
            <label>Duration (Months)
                <select name="duration_months">
                    <option value="1">1 Bulan</option>
                    <option value="2">2 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="4">4 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">1 Tahun</option>
                </select>
            </label>
            <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
            <div class="form-actions">
                <button type="submit">Simpan</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Daftar Siswa" subtitle="Data siswa dan assignment kelas">
        @if ($students->isNotEmpty())
            <x-ui.table :headers="['Nama', 'Kelas', 'Mulai', 'Selesai', 'Status', 'Action']">
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->classes->pluck('name')->join(', ') ?: '-' }}</td>
                        <td>{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('d M Y') : '-' }}</td>
                        <td>{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('d M Y') : '-' }}</td>
                        <td>
                            <x-ui.badge :type="$student->is_active ? 'success' : 'warning'">
                                {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                            </x-ui.badge>
                        </td>
                        <td>
                            <div class="action-icons">
                                {{-- Detail Button --}}
                                <details class="action-popover registration-style-popover">
                                    <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                    <div class="action-popover-form registration-edit-form">
                                        <header class="registration-modal-header">
                                            <div class="registration-modal-header-left">
                                                <span class="registration-modal-icon">
                                                    <i data-lucide="clipboard-list"></i>
                                                </span>
                                                <div>
                                                    <h3>Detail Siswa</h3>
                                                    <p>Informasi lengkap data siswa</p>
                                                </div>
                                            </div>
                                            <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                        </header>
                                        <div class="registration-modal-body">
                                            <div class="registration-modal-summary">
                                                <div class="registration-modal-summary-left">
                                                    <div class="registration-modal-avatar">
                                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p>Nama Siswa</p>
                                                        <p class="registration-modal-summary-name">{{ $student->name }}</p>
                                                    </div>
                                                </div>
                                                <span class="registration-status-badge {{ $student->is_active ? 'is-success' : 'is-warning' }}">
                                                    {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                                </span>
                                            </div>
                                            <section class="registration-modal-grid">
                                                <article>
                                                    <p>Email Siswa</p>
                                                    <p>{{ $student->email ?: '-' }}</p>
                                                </article>
                                                <article>
                                                    <p>Telepon</p>
                                                    <p>{{ $student->phone ?: '-' }}</p>
                                                </article>
                                                <article>
                                                    <p>Umur</p>
                                                    <p>{{ $student->age ? $student->age . ' Tahun' : '-' }}</p>
                                                </article>
                                                <article>
                                                    <p>Mulai Kursus</p>
                                                    <p>{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('d M Y') : '-' }}</p>
                                                </article>
                                                <article class="registration-modal-item-full">
                                                    <p>Alamat</p>
                                                    <p>{{ $student->address ?: '-' }}</p>
                                                </article>
                                                <article class="registration-modal-item-full">
                                                    <p>Kelas Terdaftar</p>
                                                    <p>{{ $student->classes->pluck('name')->join(', ') ?: '-' }}</p>
                                                </article>
                                                <article>
                                                    <p>Berakhir Pada</p>
                                                    <p>{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('d M Y') : '-' }}</p>
                                                </article>
                                                <article>
                                                    <p>Status Akun</p>
                                                    <p>{{ $student->is_active ? 'Aktif' : 'Non-aktif' }}</p>
                                                </article>
                                            </section>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Tutup</button>
                                            <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');"><i data-lucide="pencil"></i> Edit Data</button>
                                            <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus siswa ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();"><i data-lucide="trash-2"></i> Hapus</button>
                                        </footer>
                                    </div>
                                </details>

                                {{-- Edit Button --}}
                                <details class="action-popover registration-style-popover">
                                    <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                    <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('admin.students.update', $student) }}">
                                        @csrf
                                        @method('PUT')
                                        <header class="registration-modal-header">
                                            <div class="registration-modal-header-left">
                                                <span class="registration-modal-icon">
                                                    <i data-lucide="pencil-line"></i>
                                                </span>
                                                <div>
                                                    <h3>Edit Data Siswa</h3>
                                                    <p>Perbarui informasi profil siswa</p>
                                                </div>
                                            </div>
                                            <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                        </header>
                                        <div class="registration-modal-body">
                                            <section class="registration-edit-grid">
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Nama Lengkap</label>
                                                    <input type="text" name="name" value="{{ $student->name }}" required>
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Umur</label>
                                                    <input type="number" name="age" value="{{ $student->age }}">
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Email</label>
                                                    <input type="email" name="email" value="{{ $student->email }}">
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Telepon</label>
                                                    <input type="text" name="phone" value="{{ $student->phone }}">
                                                </div>
                                                <div class="registration-edit-field registration-edit-field-full">
                                                    <label class="registration-edit-label">Alamat</label>
                                                    <textarea name="address" rows="2">{{ $student->address }}</textarea>
                                                </div>
                                                <div class="registration-edit-field registration-edit-field-full">
                                                    <label class="registration-edit-label">Kelas</label>
                                                    <select multiple name="class_ids[]" size="4">
                                                        @foreach($classList as $classItem)
                                                            <option value="{{ $classItem->id }}" @selected($student->classes->contains($classItem->id))>
                                                                {{ $classItem->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Mulai Kursus</label>
                                                    <input type="date" name="start_date" value="{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('Y-m-d') : '' }}">
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Durasi (Bulan)</label>
                                                    <select name="duration_months">
                                                        @foreach([1, 2, 3, 4, 6, 12] as $m)
                                                            <option value="{{ $m }}" @selected((int)($student->duration_months ?? 0) === $m)>{{ $m }} Bulan</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Status Akun</label>
                                                    <select name="is_active">
                                                        <option value="1" @selected($student->is_active)>Aktif</option>
                                                        <option value="0" @selected(!$student->is_active)>Non-aktif</option>
                                                    </select>
                                                </div>
                                            </section>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Batal</button>
                                            <button type="submit" class="registration-modal-btn registration-modal-btn-primary"><i data-lucide="check"></i> Simpan Perubahan</button>
                                        </footer>
                                    </form>
                                </details>

                                {{-- Delete Button --}}
                                <form class="delete-form" method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state title="No student data yet" description="Tambahkan siswa baru atau terima pendaftaran dari menu Registrations." icon="graduation-cap" />
        @endif
    </x-ui.card>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const syncBodyModalState = () => {
        const hasOpenPopover = document.querySelector('details.action-popover[open]') !== null;
        document.body.classList.toggle('modal-open', hasOpenPopover);
    };

    // Close action-popover modal via close button
    document.querySelectorAll('.action-popover-close').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const details = btn.closest('details.action-popover');
            if (details) details.removeAttribute('open');
            syncBodyModalState();
        });
    });

    // Close action-popover when clicking the backdrop
    document.addEventListener('click', e => {
        if (e.target.closest('details.action-popover')) return;
        document.querySelectorAll('details.action-popover[open]').forEach(d => {
            d.removeAttribute('open');
        });
        syncBodyModalState();
    });

    // Prevent clicks inside the modal form from bubbling and closing the modal
    document.querySelectorAll('.action-popover-form').forEach(form => {
        form.addEventListener('click', e => e.stopPropagation());
    });

    document.querySelectorAll('details.action-popover').forEach(details => {
        details.addEventListener('toggle', () => {
            if (details.open && window.lucide) {
                window.lucide.createIcons();
            }
            syncBodyModalState();
        });
    });
    syncBodyModalState();
});
</script>
@endpush
@endsection
