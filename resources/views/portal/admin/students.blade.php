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
    /* Admin specific search highlighting */
    [data-searchable] mark {
        background: #fef08a;
        color: #111;
        padding: 0 0.1rem;
        border-radius: 0.1rem;
    }
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
            <label>Lagu Favorite <input type="text" name="favorite_song" placeholder="Contoh: Heal The World"></label>
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
                        <td data-label="Nama">{{ $student->name }}</td>
                        <td data-label="Kelas">{{ $student->classes->pluck('name')->join(', ') ?: '-' }}</td>
                        <td data-label="Mulai">{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('d M Y') : '-' }}</td>
                        <td data-label="Selesai">{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('d M Y') : '-' }}</td>
                        <td data-label="Status">
                            <x-ui.badge :type="$student->is_active ? 'success' : 'warning'">
                                {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                            </x-ui.badge>
                        </td>
                        <td data-label="Action">
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
                                                <article class="registration-modal-item-full">
                                                    <p>Lagu Favorite</p>
                                                    <p>{{ $student->favorite_song ?: '-' }}</p>
                                                </article>
                                            </section>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Tutup</button>
                                            @if($student->user)
                                                <a href="{{ route('admin.users.impersonate', $student->user->id) }}" class="registration-modal-btn" style="background: #0f172a !important; color: #fff !important; border: none !important;">
                                                    <i data-lucide="user-plus" class="w-4 h-4"></i> Login As
                                                </a>
                                            @endif
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
                                            <div class="module-form-grid">
                                                <label>Nama Lengkap
                                                    <input type="text" name="name" value="{{ $student->name }}" required>
                                                </label>
                                                <label>Umur
                                                    <input type="number" name="age" value="{{ $student->age }}">
                                                </label>
                                                <label>Email
                                                    <input type="email" name="email" value="{{ $student->email }}">
                                                </label>
                                                <label>Telepon
                                                    <input type="text" name="phone" value="{{ $student->phone }}">
                                                </label>
                                                <label style="grid-column: span 2;">Lagu Favorite
                                                    <input type="text" name="favorite_song" value="{{ $student->favorite_song }}">
                                                </label>
                                                <label style="grid-column: span 2;">Alamat
                                                    <textarea name="address" rows="2">{{ $student->address }}</textarea>
                                                </label>
                                                <label style="grid-column: span 2;">Kelas
                                                    <select multiple name="class_ids[]" size="4" style="height: auto; min-height: 100px;">
                                                        @foreach($classList as $classItem)
                                                            <option value="{{ $classItem->id }}" @selected($student->classes->contains($classItem->id))>
                                                                {{ $classItem->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small>Tahan Ctrl/Cmd untuk memilih lebih dari satu.</small>
                                                </label>
                                                @php
                                                    $studentScheduleIds = $student->scheduleSessions
                                                        ->pluck('schedule_id')
                                                        ->filter()
                                                        ->unique()
                                                        ->values();

                                                    if ($studentScheduleIds->isEmpty() && $student->schedule_id) {
                                                        $studentScheduleIds = collect([$student->schedule_id]);
                                                    }

                                                    $studentScheduleOptions = $schedulesForManagement
                                                        ->filter(fn($scheduleOption) => strtolower((string) $scheduleOption->status) === 'available'
                                                            || (int) $scheduleOption->student_id === (int) $student->id
                                                            || $studentScheduleIds->contains($scheduleOption->id))
                                                        ->sortBy(function ($scheduleOption) {
                                                            $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                                            $dayIndex = array_search($scheduleOption->day, $dayOrder, true);

                                                            return sprintf('%02d-%s', $dayIndex === false ? 99 : $dayIndex, $scheduleOption->time);
                                                        });
                                                @endphp
                                                <div style="grid-column: span 2;">
                                                    <label class="premium-label" style="font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 0.75rem; display: block;">Jadwal Siswa (Double Time: pilih lebih dari satu)</label>
                                                    <input type="hidden" name="schedule_sync" value="1">
                                                    <div class="registration-schedule-container">
                                                        @php
                                                            $studentGroupedSchedules = $studentScheduleOptions->groupBy('day');
                                                        @endphp

                                                        @forelse($studentGroupedSchedules as $day => $slots)
                                                            <div class="reg-day-group">
                                                                <div class="reg-day-header">{{ $day }}</div>
                                                                <div class="reg-day-slots">
                                                                    @foreach($slots as $scheduleOption)
                                                                        @php
                                                                            $isSelected = $studentScheduleIds->contains($scheduleOption->id);
                                                                            $isFull = strtolower((string) $scheduleOption->status) === 'booked'
                                                                                && (int) $scheduleOption->student_id !== (int) $student->id
                                                                                && ! $isSelected;
                                                                        @endphp
                                                                        <label class="reg-slot-card {{ $isSelected ? 'is-selected' : '' }} {{ $isFull ? 'is-disabled' : '' }}" data-teacher-id="{{ $scheduleOption->teacher_id }}" data-class-id="{{ $scheduleOption->class_id }}">
                                                                            <input type="checkbox" name="schedule_ids[]" value="{{ $scheduleOption->id }}" @checked($isSelected) @disabled($isFull) style="display: none;" onchange="this.parentElement.classList.toggle('is-selected', this.checked)">
                                                                            <span class="reg-slot-time">{{ substr((string) $scheduleOption->time, 0, 5) }}</span>
                                                                            <span class="reg-slot-teacher" style="font-size: 8px; color: #64748b; display: block; line-height: 1; margin-top: 2px; font-weight: 600;">{{ $scheduleOption->teacher->name ?? '-' }}</span>
                                                                            @if($isFull)
                                                                                <span class="reg-slot-badge">FULL</span>
                                                                            @endif
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="reg-empty-state">
                                                                <i data-lucide="calendar-x2"></i>
                                                                <span>Tidak ada jadwal tersedia.</span>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                    <small style="color: #64748b; display: block; margin-top: 0.5rem;">Pilih jadwal slot. Slot yang sudah dipakai siswa lain akan dikunci.</small>
                                                </div>
                                                <label>Mulai Kursus
                                                    <input type="date" name="start_date" value="{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('Y-m-d') : '' }}">
                                                </label>
                                                <label>Durasi (Bulan)
                                                    <select name="duration_months">
                                                        @foreach([1, 2, 3, 4, 6, 12] as $m)
                                                            <option value="{{ $m }}" @selected((int)($student->duration_months ?? 0) === $m)>{{ $m }} Bulan</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label style="grid-column: span 2;">Status Akun
                                                    <select name="is_active">
                                                        <option value="1" @selected($student->is_active)>Aktif</option>
                                                        <option value="0" @selected(!$student->is_active)>Non-aktif</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Batal</button>
                                            <button type="submit" class="registration-modal-btn registration-modal-btn-primary"><i data-lucide="check"></i> Simpan Perubahan</button>
                                        </footer>
                                    </form>
                                </details>

                                {{-- Extend Button --}}
                                <form class="extend-form" method="POST" action="{{ route('admin.students.extend', $student) }}" onsubmit="return confirm('Perpanjang jadwal siswa ini selama 1 bulan menggunakan jadwal yang sama?');">
                                    @csrf
                                    <button type="submit" class="btn-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.1);" title="Perpanjang 1 Bulan" aria-label="Perpanjang 1 Bulan"><i data-lucide="calendar-plus"></i></button>
                                </form>

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
