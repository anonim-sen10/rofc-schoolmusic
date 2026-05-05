@extends('portal.layout')

@section('title', $moduleTitle.' | ROFC')
@section('page-title', $moduleTitle)

@section('content')

@if ($moduleKey === 'users')
    <section class="card" data-searchable>
        <h3>Buat Akun Login Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.users.store') }}">
            @csrf
            <label>Nama
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>Role
                <select name="role" required>
                    <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="finance" @selected(old('role') === 'finance')>Finance</option>
                    <option value="teacher" @selected(old('role') === 'teacher')>Teacher</option>
                    <option value="student" @selected(old('role') === 'student')>Siswa</option>
                </select>
            </label>
            <label>Instrument (khusus teacher)
                <input type="text" name="instrument" value="{{ old('instrument') }}" placeholder="Drum, Piano, Guitar, dll">
            </label>
            <label>No. Telepon (khusus siswa)
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxx">
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label>Konfirmasi Password
                <input type="password" name="password_confirmation" required>
            </label>
            <div class="form-actions">
                <button type="submit">Buat Akun</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
@endif

@if ($moduleKey === 'roles')
    <section class="card" data-searchable>
        <h3>Data User</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usersForRoles as $userRow)
                        <tr>
                            <td>{{ $userRow->name }}</td>
                            <td>{{ $userRow->email }}</td>
                            <td>{{ $userRow->roles->pluck('slug')->implode(', ') }}</td>
                            <td>{{ optional($userRow->created_at)->format('Y-m-d H:i') }}</td>
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
                                                        <h3>Detail User</h3>
                                                        <p>Informasi lengkap akun pengguna</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($userRow->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama User</p>
                                                            <p class="registration-modal-summary-name">{{ $userRow->name }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge type="neutral">
                                                        {{ strtoupper($userRow->roles->pluck('slug')->first() ?? 'USER') }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article class="registration-modal-item-full">
                                                        <p>Email Address</p>
                                                        <p>{{ $userRow->email }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Role Akses</p>
                                                        <p>{{ $userRow->roles->pluck('name')->join(', ') ?: '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Terdaftar Sejak</p>
                                                        <p>{{ optional($userRow->created_at)->format('d M Y H:i') }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Verifikasi Email</p>
                                                        <p>{{ $userRow->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>ID Akun</p>
                                                        <p>#{{ $userRow->id }}</p>
                                                    </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Tutup</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');"><i data-lucide="user-cog"></i> Edit Akses</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus user ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();"><i data-lucide="trash-2"></i> Hapus</button>
                                            </footer>
                                        </div>
                                    </details>
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.users.update', $userRow) }}">
                                            @csrf
                                            @method('PUT')
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="pencil-line"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Edit User</h3>
                                                        <p>Perbarui informasi akun dan akses</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid">
                                                    <label>Nama
                                                        <input type="text" name="name" value="{{ $userRow->name }}" required>
                                                    </label>
                                                    <label>Email
                                                        <input type="email" name="email" value="{{ $userRow->email }}" required>
                                                    </label>
                                                    <label>Role
                                                        <select name="role" required>
                                                            <option value="super_admin" @selected($userRow->hasRole('super_admin'))>Super Admin</option>
                                                            <option value="admin" @selected($userRow->hasRole('admin'))>Admin</option>
                                                            <option value="finance" @selected($userRow->hasRole('finance'))>Finance</option>
                                                            <option value="teacher" @selected($userRow->hasRole('teacher'))>Teacher</option>
                                                            <option value="student" @selected($userRow->hasRole('student'))>Siswa</option>
                                                        </select>
                                                    </label>
                                                    <label>Password Baru (opsional)
                                                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                                                    </label>
                                                </div>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary"><i data-lucide="check"></i> Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.users.destroy', $userRow) }}" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No user records yet. Create your first account to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'users')
    <section class="card" data-searchable>
        <p>Data user telah dipindahkan ke menu Roles agar pengelolaan role dan akun berada dalam satu halaman.</p>
    </section>
@endif

@if ($moduleKey === 'teachers')
    <section class="card" data-searchable>
        <details class="teacher-create" @if($errors->any()) open @endif>
            <summary>Create Teacher</summary>
            <form class="module-form module-form-grid teacher-create-form" method="POST" enctype="multipart/form-data" action="{{ route('super-admin.teachers.store') }}">
                @csrf
                <label>Nama
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Nomor HP
                    <input type="text" name="phone" value="{{ old('phone') }}" required>
                </label>
                <label>Alamat
                    <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
                </label>
                <label>Jenis Kelamin
                    <select name="gender" required>
                        <option value="laki-laki" @selected(old('gender') === 'laki-laki')>Laki-laki</option>
                        <option value="perempuan" @selected(old('gender') === 'perempuan')>Perempuan</option>
                    </select>
                </label>
                <label>Agama
                    <input type="text" name="religion" value="{{ old('religion') }}" required>
                </label>
                <label>Bidang / Instrumen
                    <input type="text" name="instrument" value="{{ old('instrument') }}" placeholder="Drum, Piano, Vocal, dll">
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Konfirmasi Password
                    <input type="password" name="password_confirmation" required>
                </label>
                <label>Upload Foto Profile
                    <input type="file" name="photo" accept="image/*">
                </label>
                <label>Upload KTP Guru
                    <input type="file" name="ktp" accept="image/*">
                </label>
                <div class="form-actions">
                    <button type="submit">Simpan Teacher</button>
                    <button type="reset" class="btn-secondary">Cancel</button>
                </div>
            </form>
        </details>
    </section>

    <section class="card" data-searchable>
        <h3>Daftar Guru</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Class</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachersForManagement as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->user?->email ?? '-' }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>{{ $teacher->address ?? '-' }}</td>
                            <td>{{ $teacher->gender ?? '-' }}</td>
                            <td>{{ $teacher->religion ?? '-' }}</td>
                            <td>{{ $teacher->classes->pluck('name')->implode(', ') ?: '-' }}</td>
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
                                                        <h3>Detail Guru</h3>
                                                        <p>Informasi lengkap profil pengajar</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        @if($teacher->photo_path)
                                                            <img src="{{ asset('storage/' . $teacher->photo_path) }}" class="registration-modal-avatar" style="object-fit: cover;" onclick="showLightbox(this.src)">
                                                        @else
                                                            <div class="registration-modal-avatar">
                                                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p>Nama Guru</p>
                                                            <p class="registration-modal-summary-name">{{ $teacher->name }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$teacher->is_active ? 'success' : 'warning'">
                                                        {{ $teacher->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article class="registration-modal-item-full">
                                                        <p>Email Address</p>
                                                        <p>{{ $teacher->user?->email ?? '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Nomor HP</p>
                                                        <p>{{ $teacher->phone ?? '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Jenis Kelamin</p>
                                                        <p>{{ ucfirst($teacher->gender ?? '-') }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Agama</p>
                                                        <p>{{ $teacher->religion ?? '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Bidang / Instrumen</p>
                                                        <p>{{ $teacher->instrument ?? '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Alamat</p>
                                                        <p>{{ $teacher->address ?? '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Kelas Diampu</p>
                                                        <p>{{ $teacher->classes->pluck('name')->join(', ') ?: '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                         <p>KTP Guru</p>
                                                         @if($teacher->ktp_path)
                                                            <div style="margin-top: 0.5rem;">
                                                                <img src="{{ asset('storage/' . $teacher->ktp_path) }}" style="width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; border: 1px solid #e2e8f0; cursor: zoom-in;" onclick="showLightbox(this.src)">
                                                            </div>
                                                         @else
                                                            <div style="margin-top: 0.5rem; padding: 1rem; background: #fff1f2; border: 1px dashed #fecdd3; border-radius: 0.5rem; color: #be123c; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                                                <i data-lucide="alert-circle" style="width: 1rem; height: 1rem;"></i>
                                                                KTP belum diupload. Silakan edit data untuk melengkapi.
                                                            </div>
                                                         @endif
                                                     </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Tutup</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');"><i data-lucide="pencil"></i> Edit Data</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus teacher ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();"><i data-lucide="trash-2"></i> Hapus</button>
                                            </footer>
                                        </div>
                                    </details>
                                    <details class="action-popover" id="teacher-edit-{{ $teacher->id }}">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form teacher-edit-modal" method="POST" enctype="multipart/form-data" action="{{ route('super-admin.teachers.update', $teacher) }}" id="teacher-edit-form-{{ $teacher->id }}" novalidate>
                                            @csrf
                                            @method('PUT')
                                            {{-- ─── HEADER ─── --}}
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="user-pen"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Edit Teacher</h3>
                                                        <p>Perbarui informasi data pengajar</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup">
                                                    <i data-lucide="x"></i>
                                                </button>
                                            </header>

                                            {{-- ─── BODY ─── --}}
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid">
                                                    <label>Nama
                                                        <input type="text" name="name" value="{{ $teacher->name }}" placeholder="Masukkan nama lengkap" required>
                                                    </label>
                                                    <label>Jenis Kelamin
                                                        <select name="gender" required>
                                                            <option value="" disabled>Pilih jenis kelamin</option>
                                                            <option value="laki-laki" @selected($teacher->gender === 'laki-laki')>Laki-laki</option>
                                                            <option value="perempuan" @selected($teacher->gender === 'perempuan')>Perempuan</option>
                                                        </select>
                                                    </label>
                                                    <label>Email
                                                        <input type="email" name="email" value="{{ $teacher->user?->email }}" placeholder="contoh@email.com" required>
                                                    </label>
                                                    <label>Agama
                                                        <input type="text" name="religion" value="{{ $teacher->religion }}" placeholder="Masukkan agama" required>
                                                    </label>
                                                    <label>Nomor HP
                                                        <input type="text" name="phone" value="{{ $teacher->phone }}" placeholder="08xxxxxxxxxx" required>
                                                    </label>
                                                    <label>Bidang / Instrumen
                                                        <input type="text" name="instrument" value="{{ $teacher->instrument }}" placeholder="Drum, Piano, Vocal, dll">
                                                    </label>
                                                    <label>Assign Class
                                                        <select name="class_id">
                                                            <option value="">Pilih class (opsional)</option>
                                                            @foreach ($classesForManagement as $classOption)
                                                                <option value="{{ $classOption->id }}" @selected($teacher->classes->contains($classOption->id))>{{ $classOption->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label>Password Baru (opsional)
                                                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                                                    </label>
                                                    <label>Upload Foto Profile
                                                        <input type="file" name="photo" accept="image/*">
                                                    </label>
                                                    <label style="grid-column: span 2;">Upload KTP Guru
                                                        <input type="file" name="ktp" accept="image/*">
                                                        @if($teacher->ktp_path)
                                                            <small style="color: #059669;">KTP sudah ada.</small>
                                                        @endif
                                                    </label>
                                                    <label style="grid-column: span 2;">Alamat
                                                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap" required>{{ $teacher->address }}</textarea>
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- ─── FOOTER ─── --}}
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">
                                                    <i data-lucide="save"></i> Simpan Perubahan
                                                </button>
                                            </footer>
                                        </form>
                                    </details>
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('Hapus teacher ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No teacher profiles yet. Add a teacher to start assigning classes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'classes')
    <section class="card" data-searchable>
        <details class="teacher-create" @if($errors->any()) open @endif>
            <summary>Create Class</summary>
            <form class="module-form module-form-grid teacher-create-form" method="POST" action="{{ route('super-admin.classes.store') }}">
                @csrf
                <label>Nama Kelas
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>Deskripsi
                    <textarea name="description" rows="3">{{ old('description') }}</textarea>
                </label>
                <label>Harga
                    <input type="number" name="price" min="0" step="1000" value="{{ old('price', 0) }}">
                </label>
                <label>Guru
                    <select name="teacher_id">
                        <option value="">Pilih guru (opsional)</option>
                        @foreach ($teachersForClassOptions as $teacherOption)
                            <option value="{{ $teacherOption->id }}" @selected((string) old('teacher_id') === (string) $teacherOption->id)>{{ $teacherOption->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Status
                    <select name="status" required>
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                </label>
                <div class="form-actions">
                    <button type="submit">Simpan Class</button>
                    <button type="reset" class="btn-secondary">Cancel</button>
                </div>
            </form>
        </details>
    </section>

    <section class="card" data-searchable>
        <h3>Daftar Class</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Guru</th>
                        <th>Harga</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classesForManagement as $classItem)
                        <tr>
                            <td>{{ $classItem->name }}</td>
                            <td>{{ $classItem->teacher?->name ?? '-' }}</td>
                            <td>Rp{{ number_format((int) ($classItem->price ?? 0), 0, ',', '.') }}</td>
                            <td>{{ $classItem->schedule ?? '-' }}</td>
                            <td>
                                <x-ui.badge :type="$classItem->status === 'active' ? 'success' : 'warning'">
                                    {{ strtoupper($classItem->status) }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <div class="action-icons class-action-icons">
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
                                                        <h3>Detail Kelas</h3>
                                                        <p>Informasi lengkap program kelas musik</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($classItem->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama Kelas</p>
                                                            <p class="registration-modal-summary-name">{{ $classItem->name }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$classItem->status === 'active' ? 'success' : 'warning'">
                                                        {{ strtoupper($classItem->status) }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article>
                                                        <p>Harga Per Bulan</p>
                                                        <p>Rp{{ number_format((int) ($classItem->price ?? 0), 0, ',', '.') }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Jadwal Standar</p>
                                                        <p>{{ $classItem->schedule ?? '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Guru Pengampu</p>
                                                        <p>{{ $classItem->teacher?->name ?? 'Belum ditentukan' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Deskripsi Kelas</p>
                                                        <p>{{ $classItem->description ?: 'Tidak ada deskripsi' }}</p>
                                                    </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Tutup</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');">Edit Kelas</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus class ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();">Hapus</button>
                                            </footer>
                                        </div>
                                    </details>

                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.classes.update', $classItem) }}">
                                        @csrf
                                        @method('PUT')
                                        <header class="registration-modal-header">
                                            <div class="registration-modal-header-left">
                                                <span class="registration-modal-icon">
                                                    <i data-lucide="pencil-line"></i>
                                                </span>
                                                <div>
                                                    <h3>Edit Class</h3>
                                                    <p>Perbarui informasi kelas musik</p>
                                                </div>
                                            </div>
                                            <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup"><i data-lucide="x"></i></button>
                                        </header>
                                        <div class="registration-modal-body">
                                            <div class="module-form-grid">
                                                <label style="grid-column: span 2;">Nama Kelas
                                                    <input type="text" name="name" value="{{ $classItem->name }}" required>
                                                </label>
                                                <label style="grid-column: span 2;">Deskripsi
                                                    <textarea name="description" rows="3">{{ $classItem->description }}</textarea>
                                                </label>
                                                <label>Harga
                                                    <input type="number" name="price" min="0" step="1000" value="{{ $classItem->price ?? 0 }}">
                                                </label>
                                                <label>Guru
                                                    <select name="teacher_id">
                                                        <option value="">Pilih guru (opsional)</option>
                                                        @foreach ($teachersForClassOptions as $teacherOption)
                                                            <option value="{{ $teacherOption->id }}" @selected((string) $classItem->teacher_id === (string) $teacherOption->id)>{{ $teacherOption->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label style="grid-column: span 2;">Status
                                                    <select name="status" required>
                                                        <option value="active" @selected($classItem->status === 'active')>Active</option>
                                                        <option value="inactive" @selected($classItem->status === 'inactive')>Inactive</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Batal</button>
                                            <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                        </footer>
                                        </form>
                                    </details>
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.classes.destroy', $classItem) }}" onsubmit="return confirm('Hapus class ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No classes yet. Create a class and assign a teacher to begin operations.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'students')
    <section class="card" data-searchable>
        @php
            $openStudentCreate = $errors->hasAny([
                'name',
                'age',
                'email',
                'phone',
                'address',
                'is_active',
                'class_ids',
                'class_ids.*',
            ]);
            $oldClassIds = collect(old('class_ids', []))->map(fn ($id) => (string) $id);
        @endphp

        <details class="teacher-create" @if($openStudentCreate) open @endif>
            <summary>Create Student</summary>
            <form class="module-form module-form-grid teacher-create-form" method="POST" action="{{ route('super-admin.students.store') }}">
                @csrf
                <label>Nama
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>Umur
                    <input type="number" name="age" min="4" max="80" value="{{ old('age') }}">
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}">
                </label>
                <label>Telepon
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </label>
                <label>Alamat
                    <textarea name="address" rows="2">{{ old('address') }}</textarea>
                </label>
                <label>Kelas
                    <select name="class_ids[]" multiple size="6">
                        @foreach($classesForManagement as $classItem)
                            <option value="{{ $classItem->id }}" @selected($oldClassIds->contains((string) $classItem->id))>{{ $classItem->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Status
                    <select name="is_active" required>
                        <option value="1" @selected(old('is_active', '1') === '1')>Active</option>
                        <option value="0" @selected(old('is_active') === '0')>Inactive</option>
                    </select>
                </label>
                <div class="form-actions">
                    <button type="submit">Simpan Student</button>
                    <button type="reset" class="btn-secondary">Cancel</button>
                </div>
            </form>
        </details>
    </section>

    <section class="card" data-searchable>
        <h3>Daftar Seluruh Siswa</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentsForManagement as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email ?: '-' }}</td>
                            <td>{{ $student->phone ?: '-' }}</td>
                            <td>{{ $student->classes->pluck('name')->join(', ') ?: '-' }}</td>
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
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.students.update', $student) }}">
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
                                                    <label style="grid-column: span 2;">Alamat
                                                        <textarea name="address" rows="2">{{ $student->address }}</textarea>
                                                    </label>
                                                    <label style="grid-column: span 2;">Kelas
                                                        <select multiple name="class_ids[]" size="4" style="height: auto; min-height: 100px;">
                                                            @foreach($classesForManagement as $classItem)
                                                                <option value="{{ $classItem->id }}" @selected($student->classes->contains($classItem->id))>
                                                                    {{ $classItem->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small style="color: #64748b; font-size: 0.75rem; margin-top: 0.25rem; display: block;">Tahan Ctrl/Cmd untuk memilih lebih dari satu.</small>
                                                    </label>
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

                                    {{-- Delete Button --}}
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data siswa. Tambahkan siswa baru untuk menampilkannya di sini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'registrations')
    @php
        $instrumenOptions = ['Drum', 'Piano', 'Guitar', 'Vocal', 'Violin', 'Bass', 'Keyboard', 'Music Theory'];
        $programTambahanOptions = ['Teori Musik', 'Ensemble / Band', 'Skill Teknik (ajang kompetisi)', 'Ujian Sertifikat bertaraf international'];
        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    @endphp



    <style>
        /* Highlight search results */
        [data-searchable] mark {
            background: #fef08a;
            color: #111;
            padding: 0 0.1rem;
            border-radius: 0.1rem;
        }
    </style>

    <section class="card" data-searchable>
        @php
            $openRegistrationCreate = $errors->hasAny([
                'nama_lengkap',
                'nama_panggilan',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'kewarganegaraan',
                'alamat',
                'email',
                'no_hp_siswa',
                'nama_ortu',
                'no_hp_ortu',
                'instrumen',
                'hari_pilihan',
                'class_id',
                'pengalaman',
                'deskripsi_pengalaman',
                'status',
            ]);
            $oldProgramTambahan = old('program_tambahan', []);
            $oldHariPilihan = old('hari_pilihan', []);
        @endphp

        <details class="teacher-create" @if($openRegistrationCreate) open @endif>
            <summary>Create Registration</summary>
            <form class="module-form module-form-grid teacher-create-form" method="POST" action="{{ route('super-admin.registrations.store') }}">
                @csrf
                <label>Nama Lengkap
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                </label>
                <label>Nama Panggilan
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" required>
                </label>
                <label>Jenis Kelamin
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="laki-laki" @selected(old('jenis_kelamin') === 'laki-laki')>laki-laki</option>
                        <option value="perempuan" @selected(old('jenis_kelamin') === 'perempuan')>perempuan</option>
                    </select>
                </label>
                <label>Tempat Lahir
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                </label>
                <label>Tanggal Lahir
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                </label>
                <label>Kewarganegaraan
                    <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" required>
                </label>
                <label>Alamat
                    <textarea name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                </label>
                <label>No HP Siswa
                    <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Nama Orang Tua
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required>
                </label>
                <label>Pekerjaan Orang Tua
                    <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}">
                </label>
                <label>No HP Orang Tua
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required>
                </label>
                <label>Email Orang Tua
                    <input type="email" name="email_ortu" value="{{ old('email_ortu') }}">
                </label>
                <label>Instrumen
                    <select name="instrumen" required>
                        <option value="">Pilih instrumen</option>
                        @foreach($instrumenOptions as $instrumenItem)
                            <option value="{{ $instrumenItem }}" @selected(old('instrumen') === $instrumenItem)>{{ $instrumenItem }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Program Tambahan
                    <select name="program_tambahan[]" multiple>
                        @foreach($programTambahanOptions as $programItem)
                            <option value="{{ $programItem }}" @selected(in_array($programItem, $oldProgramTambahan, true))>{{ $programItem }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Hari Pilihan
                    <select name="hari_pilihan[]" multiple required>
                        @foreach($hariOptions as $hariItem)
                            <option value="{{ $hariItem }}" @selected(in_array($hariItem, $oldHariPilihan, true))>{{ $hariItem }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Pernah Belajar Musik
                    <select name="pengalaman" required>
                        <option value="1" @selected(old('pengalaman') === '1')>Ya</option>
                        <option value="0" @selected(old('pengalaman') === '0')>Tidak</option>
                    </select>
                </label>
                <label>Deskripsi Pengalaman
                    <textarea name="deskripsi_pengalaman" rows="2">{{ old('deskripsi_pengalaman') }}</textarea>
                </label>
                <label>Kelas
                    <select name="class_id">
                        <option value="">Pilih kelas</option>
                        @foreach($classesForManagement as $classItem)
                            <option value="{{ $classItem->id }}" @selected((string) old('class_id') === (string) $classItem->id)>{{ $classItem->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Status
                    <select name="status" required>
                        <option value="pending" @selected(old('status', 'pending') === 'pending')>pending</option>
                        <option value="accepted" @selected(old('status') === 'accepted')>accepted</option>
                        <option value="rejected" @selected(old('status') === 'rejected')>rejected</option>
                    </select>
                </label>
                <div class="form-actions">
                    <button type="submit">Simpan Registration</button>
                    <button type="reset" class="btn-secondary">Cancel</button>
                </div>
            </form>
        </details>
    </section>

    <section class="card" data-searchable>
        <h3>Daftar Registration</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Instrumen</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrationsForManagement as $registrationItem)
                        @php
                            $registrationStatus = strtolower((string) $registrationItem->status);
                            $registrationBadge = $registrationStatus === 'accepted' ? 'success' : ($registrationStatus === 'rejected' ? 'danger' : 'warning');
                            $legacyNotesMap = [];
                            foreach (preg_split('/\r\n|\r|\n/', (string) ($registrationItem->notes ?? '')) as $line) {
                                $line = trim((string) $line);
                                if ($line === '' || ! str_contains($line, ':')) {
                                    continue;
                                }

                                [$key, $value] = array_pad(explode(':', $line, 2), 2, '');
                                $key = trim($key);
                                if ($key === '') {
                                    continue;
                                }

                                $legacyNotesMap[$key] = trim($value);
                            }

                            $namaLengkap = $registrationItem->nama_lengkap ?: $registrationItem->full_name;
                            $namaPanggilan = $registrationItem->nama_panggilan ?: ($legacyNotesMap['Nama Panggilan'] ?? '-');
                            $jenisKelamin = $registrationItem->jenis_kelamin ?: ($legacyNotesMap['Jenis Kelamin'] ?? '-');

                            $legacyTempatLahir = '-';
                            $legacyTanggalLahir = '-';
                            $legacyTempatTanggal = trim((string) ($legacyNotesMap['Tempat/Tanggal Lahir'] ?? ''));
                            if ($legacyTempatTanggal !== '') {
                                $legacyTempatTanggalParts = array_values(array_filter(array_map('trim', explode(',', $legacyTempatTanggal)), fn (string $item) => $item !== ''));
                                if (count($legacyTempatTanggalParts) >= 2) {
                                    $legacyTanggalLahir = array_pop($legacyTempatTanggalParts) ?: '-';
                                    $legacyTempatLahir = implode(', ', $legacyTempatTanggalParts) ?: '-';
                                } else {
                                    $legacyTempatLahir = $legacyTempatTanggal;
                                }
                            }

                            $tempatLahir = $registrationItem->tempat_lahir ?: $legacyTempatLahir;

                            $tanggalLahirText = $registrationItem->tanggal_lahir
                                ? optional($registrationItem->tanggal_lahir)->format('d M Y')
                                : $legacyTanggalLahir;

                            $tanggalLahirInput = $registrationItem->tanggal_lahir
                                ? optional($registrationItem->tanggal_lahir)->format('Y-m-d')
                                : null;
                            if (! $tanggalLahirInput && $legacyTanggalLahir !== '-') {
                                try {
                                    $tanggalLahirInput = \Carbon\Carbon::parse($legacyTanggalLahir)->format('Y-m-d');
                                } catch (\Throwable $e) {
                                    $tanggalLahirInput = '';
                                }
                            }

                            $kewarganegaraan = $registrationItem->kewarganegaraan ?: ($legacyNotesMap['Kewarganegaraan'] ?? '-');
                            $alamat = $registrationItem->alamat ?: ($legacyNotesMap['Alamat'] ?? '-');
                            $teleponSiswa = $registrationItem->no_hp_siswa ?: $registrationItem->phone;
                            $namaOrtu = $registrationItem->nama_ortu ?: ($legacyNotesMap['Nama Ortu'] ?? '-');
                            $pekerjaanOrtu = $registrationItem->pekerjaan_ortu ?: ($legacyNotesMap['Pekerjaan Ortu'] ?? '-');
                            $noHpOrtu = $registrationItem->no_hp_ortu ?: ($legacyNotesMap['No HP Ortu'] ?? '-');
                            $emailOrtu = $registrationItem->email_ortu ?: ($legacyNotesMap['Email Ortu'] ?? '-');

                            $instrumenValue = $registrationItem->instrumen ?: ($legacyNotesMap['Instrumen'] ?? '');
                            $instrumenText = $instrumenValue !== '' ? $instrumenValue : ($registrationItem->class?->name ?? '-');

                            $hariPilihanText = $registrationItem->preferred_schedule ?? '-';
                            $hariPilihanArray = is_array($registrationItem->hari_pilihan)
                                ? $registrationItem->hari_pilihan
                                : collect(explode(',', (string) ($registrationItem->preferred_schedule ?? '')))
                                    ->map(fn (string $item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            if (empty($hariPilihanArray) && ! empty($legacyNotesMap['Hari Pilihan'])) {
                                $hariPilihanArray = collect(explode(',', (string) $legacyNotesMap['Hari Pilihan']))
                                    ->map(fn (string $item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                            if (! empty($hariPilihanArray)) {
                                $hariPilihanText = implode(', ', $hariPilihanArray);
                            }

                            $programTambahanArray = is_array($registrationItem->program_tambahan)
                                ? $registrationItem->program_tambahan
                                : [];
                            if (empty($programTambahanArray) && ! empty($legacyNotesMap['Program Tambahan']) && $legacyNotesMap['Program Tambahan'] !== '-') {
                                $programTambahanArray = collect(explode(',', (string) $legacyNotesMap['Program Tambahan']))
                                    ->map(fn (string $item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                            $programTambahanText = ! empty($programTambahanArray) ? implode(', ', $programTambahanArray) : '-';

                            $pengalamanValue = null;
                            if (! is_null($registrationItem->pengalaman)) {
                                $pengalamanValue = (bool) $registrationItem->pengalaman;
                            } elseif (! empty($legacyNotesMap['Pengalaman']) && $legacyNotesMap['Pengalaman'] !== '-') {
                                $pengalamanValue = in_array(strtolower((string) $legacyNotesMap['Pengalaman']), ['ya', 'yes', '1', 'true'], true);
                            }
                            $pengalamanText = is_null($pengalamanValue) ? '-' : ($pengalamanValue ? 'Ya' : 'Tidak');

                            $deskripsiPengalaman = $registrationItem->deskripsi_pengalaman ?: ($legacyNotesMap['Deskripsi Pengalaman'] ?? null);
                            if (blank($deskripsiPengalaman) && ! empty($registrationItem->notes) && empty($legacyNotesMap)) {
                                $deskripsiPengalaman = $registrationItem->notes;
                            }
                            $deskripsiPengalaman = $deskripsiPengalaman ?: '-';

                            $editTriggerId = 'registration-edit-trigger-'.$registrationItem->id;
                            $detailPayload = [
                                'fullName' => $namaLengkap,
                                'nickName' => $namaPanggilan,
                                'gender' => $jenisKelamin,
                                'birthPlace' => $tempatLahir,
                                'birthDate' => $tanggalLahirText,
                                'birthDateInput' => $tanggalLahirInput,
                                'citizenship' => $kewarganegaraan,
                                'studentPhone' => $teleponSiswa,
                                'studentEmail' => $registrationItem->email,
                                'address' => $alamat,
                                'parentName' => $namaOrtu,
                                'parentJob' => $pekerjaanOrtu,
                                'parentPhone' => $noHpOrtu,
                                'parentEmail' => $emailOrtu,
                                'instrument' => $instrumenText,
                                'additionalProgram' => $programTambahanText,
                                'preferredDays' => $hariPilihanText,
                                'preferredDaysRaw' => $hariPilihanArray,
                                'experience' => $pengalamanText,
                                'experienceValue' => $pengalamanValue === true ? '1' : '0',
                                'experienceDescription' => $deskripsiPengalaman,
                                'status' => strtoupper($registrationStatus),
                                'statusValue' => $registrationStatus,
                                'selectedClass' => $registrationItem->class?->name ?? '-',
                                'classId' => (string) ($registrationItem->class_id ?? ''),
                                'instrumentValue' => $instrumenValue,
                                'additionalProgramRaw' => $programTambahanArray,
                                'updateAction' => route('super-admin.registrations.update', $registrationItem),
                                'editTriggerId' => $editTriggerId,
                                'deleteAction' => route('super-admin.registrations.destroy', $registrationItem),
                                'schedules' => $registrationItem->schedules->map(fn($s) => [
                                    'label' => $s->day . ' ' . substr((string)$s->time, 0, 5)
                                ])->all(),
                            ];
                        @endphp
                        <tr>
                            <td>{{ $namaLengkap }}</td>
                            <td>{{ $registrationItem->email }}</td>
                            <td>{{ $teleponSiswa }}</td>
                            <td>{{ $instrumenText }}</td>
                            <td><span class="registration-schedule-count">{{ $registrationItem->schedules->count() }} Slot</span></td>
                            <td><x-ui.badge :type="$registrationBadge">{{ strtoupper($registrationStatus) }}</x-ui.badge></td>
                            <td>
                                <div class="action-icons class-action-icons">
                                    @if ($registrationStatus !== 'accepted')
                                        <form method="POST" action="{{ route('super-admin.registrations.approve', $registrationItem->id) }}" onsubmit="return confirm('Approve registration ini dan buat akun siswa?');">
                                            @csrf
                                            <button type="submit" class="btn-icon" title="Approve" aria-label="Approve"><i data-lucide="badge-check"></i></button>
                                        </form>
                                    @endif

                                    {{-- Detail Popover --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                        <div class="action-popover-form registration-edit-form">
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon"><i data-lucide="clipboard-list"></i></span>
                                                    <div><h3>Detail Pendaftaran</h3><p>Informasi lengkap data siswa</p></div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($namaLengkap, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama Siswa</p>
                                                            <p class="registration-modal-summary-name">{{ $namaLengkap }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$registrationBadge">{{ strtoupper($registrationStatus) }}</x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article><p>Nama Panggilan</p><p>{{ $namaPanggilan }}</p></article>
                                                    <article><p>Jenis Kelamin</p><p>{{ $jenisKelamin }}</p></article>
                                                    <article><p>Tempat Lahir</p><p>{{ $tempatLahir }}</p></article>
                                                    <article><p>Tanggal Lahir</p><p>{{ $tanggalLahirText }}</p></article>
                                                    <article><p>Kewarganegaraan</p><p>{{ $kewarganegaraan }}</p></article>
                                                    <article><p>No HP Siswa</p><p>{{ $teleponSiswa }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Email Siswa</p><p>{{ $registrationItem->email }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Alamat</p><p>{{ $alamat }}</p></article>
                                                    <article><p>Nama Orang Tua</p><p>{{ $namaOrtu }}</p></article>
                                                    <article><p>Pekerjaan Orang Tua</p><p>{{ $pekerjaanOrtu }}</p></article>
                                                    <article><p>No HP Orang Tua</p><p>{{ $noHpOrtu }}</p></article>
                                                    <article><p>Email Orang Tua</p><p>{{ $emailOrtu }}</p></article>
                                                    <article><p>Instrumen</p><p>{{ $instrumenText }}</p></article>
                                                    <article><p>Program Tambahan</p><p>{{ $programTambahanText }}</p></article>
                                                    <article><p>Pengalaman Belajar</p><p>{{ $pengalamanText }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Deskripsi Pengalaman</p><p>{{ $deskripsiPengalaman }}</p></article>
                                                    <article><p>Status</p><p>{{ strtoupper($registrationStatus) }}</p></article>
                                                    <article><p>Kelas Terpilih</p><p>{{ $registrationItem->class?->name ?? '-' }}</p></article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="this.closest('details').removeAttribute('open');">Tutup</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(3)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');">Edit Data</button>
                                            </footer>
                                        </div>
                                    </details>

                                    {{-- Edit Popover --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.registrations.update', $registrationItem) }}">
                                            @csrf
                                            @method('PUT')
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon"><i data-lucide="pencil-line"></i></span>
                                                    <div><h3>Edit Pendaftaran</h3><p>Perbarui informasi pendaftaran siswa</p></div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid">
                                                    <label>Nama Lengkap <input type="text" name="nama_lengkap" value="{{ $namaLengkap }}" required></label>
                                                    <label>Nama Panggilan <input type="text" name="nama_panggilan" value="{{ $namaPanggilan }}" required></label>
                                                    <label>Jenis Kelamin
                                                        <select name="jenis_kelamin" required>
                                                            <option value="laki-laki" @selected($jenisKelamin === 'laki-laki')>Laki-laki</option>
                                                            <option value="perempuan" @selected($jenisKelamin === 'perempuan')>Perempuan</option>
                                                        </select>
                                                    </label>
                                                    <label>Tempat Lahir <input type="text" name="tempat_lahir" value="{{ $tempatLahir }}" required></label>
                                                    <label>Tanggal Lahir <input type="date" name="tanggal_lahir" value="{{ $tanggalLahirInput }}" required></label>
                                                    <label>Kewarganegaraan <input type="text" name="kewarganegaraan" value="{{ $kewarganegaraan }}" required></label>
                                                    <label style="grid-column: span 2;">Alamat <textarea name="alamat" rows="3" required>{{ $alamat }}</textarea></label>
                                                    <label>No HP Siswa <input type="tel" name="no_hp_siswa" value="{{ $teleponSiswa }}" required></label>
                                                    <label>Email Siswa <input type="email" name="email" value="{{ $registrationItem->email }}" required></label>
                                                    <label>Nama Orang Tua <input type="text" name="nama_ortu" value="{{ $namaOrtu }}" required></label>
                                                    <label>Pekerjaan Orang Tua <input type="text" name="pekerjaan_ortu" value="{{ $pekerjaanOrtu }}"></label>
                                                    <label>No HP Orang Tua <input type="tel" name="no_hp_ortu" value="{{ $noHpOrtu }}" required></label>
                                                    <label>Email Orang Tua <input type="email" name="email_ortu" value="{{ $emailOrtu }}"></label>
                                                    <label>Instrumen
                                                        <select name="instrumen" required>
                                                            @foreach($instrumenOptions as $instrumenItem)
                                                                <option value="{{ $instrumenItem }}" @selected($instrumenValue === $instrumenItem)>{{ $instrumenItem }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label>Status
                                                        <select name="status" required>
                                                            <option value="pending" @selected($registrationStatus === 'pending')>pending</option>
                                                            <option value="accepted" @selected($registrationStatus === 'accepted')>accepted</option>
                                                            <option value="rejected" @selected($registrationStatus === 'rejected')>rejected</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="this.closest('details').removeAttribute('open');">Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>

                                    <form method="POST" action="{{ route('super-admin.registrations.destroy', $registrationItem) }}" onsubmit="return confirm('Hapus registration ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No registrations yet. Website leads will appear here automatically.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>





    <form method="POST" data-registration-modal-delete-form style="display:none;">
        @csrf
        @method('DELETE')
    </form>

@endif

@if ($moduleKey === 'blog')
    <section class="card" data-searchable>
        <h3>Create Post</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'blog') }}">
            @csrf
            <label>Title<input type="text" name="title" required></label>
            <label>Slug<input type="text" name="slug" required></label>
            <label>Excerpt<textarea name="excerpt" rows="2"></textarea></label>
            <label>Content<textarea name="content" rows="4"></textarea></label>
            <label>Cover Image URL<input type="text" name="cover_image"></label>
            <label>Status
                <select name="status"><option value="draft">draft</option><option value="published">published</option></select>
            </label>
            <label>Published At<input type="datetime-local" name="published_at"></label>
            <div class="form-actions">
                <button type="submit">Simpan Post</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <h3>Daftar Post</h3>
        @foreach($postsForManagement as $post)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'blog', 'id' => $post->id]) }}">
                @csrf
                @method('PUT')
                <label>Title<input type="text" name="title" value="{{ $post->title }}" required></label>
                <label>Slug<input type="text" name="slug" value="{{ $post->slug }}" required></label>
                <label>Excerpt<textarea name="excerpt" rows="2">{{ $post->excerpt }}</textarea></label>
                <label>Content<textarea name="content" rows="4">{{ $post->content }}</textarea></label>
                <label>Cover Image URL<input type="text" name="cover_image" value="{{ $post->cover_image }}"></label>
                <label>Status
                    <select name="status"><option value="draft" @selected($post->status === 'draft')>draft</option><option value="published" @selected($post->status === 'published')>published</option></select>
                </label>
                <label>Published At<input type="datetime-local" name="published_at" value="{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d\\TH:i') : '' }}"></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'blog', 'id' => $post->id]) }}" onsubmit="return confirm('Hapus post ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'gallery')
    <section class="card" data-searchable>
        <h3>Create Gallery</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'gallery') }}">
            @csrf
            <label>Title<input type="text" name="title" required></label>
            <label>Category<input type="text" name="category"></label>
            <label>Type<select name="type"><option value="photo">photo</option><option value="video">video</option></select></label>
            <label>File Path<input type="text" name="file_path" required></label>
            <div class="form-actions">
                <button type="submit">Simpan Gallery</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <h3>Daftar Gallery</h3>
        @foreach($galleriesForManagement as $gallery)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'gallery', 'id' => $gallery->id]) }}">
                @csrf
                @method('PUT')
                <label>Title<input type="text" name="title" value="{{ $gallery->title }}" required></label>
                <label>Category<input type="text" name="category" value="{{ $gallery->category }}"></label>
                <label>Type<select name="type"><option value="photo" @selected($gallery->type === 'photo')>photo</option><option value="video" @selected($gallery->type === 'video')>video</option></select></label>
                <label>File Path<input type="text" name="file_path" value="{{ $gallery->file_path }}" required></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'gallery', 'id' => $gallery->id]) }}" onsubmit="return confirm('Hapus gallery ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'events')
    <section class="card" data-searchable>
        <h3>Create Event</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'events') }}">
            @csrf
            <label>Title<input type="text" name="title" required></label>
            <label>Description<textarea name="description" rows="3"></textarea></label>
            <label>Date<input type="date" name="event_date"></label>
            <label>Location<input type="text" name="location"></label>
            <label>Status<select name="status"><option value="draft">draft</option><option value="upcoming">upcoming</option><option value="completed">completed</option><option value="cancelled">cancelled</option></select></label>
            <div class="form-actions">
                <button type="submit">Simpan Event</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <h3>Daftar Event</h3>
        @foreach($eventsForManagement as $event)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'events', 'id' => $event->id]) }}">
                @csrf
                @method('PUT')
                <label>Title<input type="text" name="title" value="{{ $event->title }}" required></label>
                <label>Description<textarea name="description" rows="3">{{ $event->description }}</textarea></label>
                <label>Date<input type="date" name="event_date" value="{{ $event->event_date }}"></label>
                <label>Location<input type="text" name="location" value="{{ $event->location }}"></label>
                <label>Status<select name="status"><option value="draft" @selected($event->status === 'draft')>draft</option><option value="upcoming" @selected($event->status === 'upcoming')>upcoming</option><option value="completed" @selected($event->status === 'completed')>completed</option><option value="cancelled" @selected($event->status === 'cancelled')>cancelled</option></select></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'events', 'id' => $event->id]) }}" onsubmit="return confirm('Hapus event ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'testimonials')
    <section class="card" data-searchable>
        <h3>Create Testimonial</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'testimonials') }}">
            @csrf
            <label>Name<input type="text" name="name" required></label>
            <label>Role<input type="text" name="role"></label>
            <label>Message<textarea name="message" rows="3" required></textarea></label>
            <label>Publish<select name="is_published"><option value="1">Ya</option><option value="0">Tidak</option></select></label>
            <div class="form-actions">
                <button type="submit">Simpan Testimonial</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <h3>Daftar Testimonial</h3>
        @foreach($testimonialsForManagement as $testimonial)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'testimonials', 'id' => $testimonial->id]) }}">
                @csrf
                @method('PUT')
                <label>Name<input type="text" name="name" value="{{ $testimonial->name }}" required></label>
                <label>Role<input type="text" name="role" value="{{ $testimonial->role }}"></label>
                <label>Message<textarea name="message" rows="3" required>{{ $testimonial->message }}</textarea></label>
                <label>Publish<select name="is_published"><option value="1" @selected($testimonial->is_published)>Ya</option><option value="0" @selected(! $testimonial->is_published)>Tidak</option></select></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'testimonials', 'id' => $testimonial->id]) }}" onsubmit="return confirm('Hapus testimonial ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'settings')
    <section class="card" data-searchable>
        <h3>Create Setting</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'settings') }}">
            @csrf
            <label>Key<input type="text" name="key" required></label>
            <label>Value<textarea name="value" rows="2"></textarea></label>
            <div class="form-actions">
                <button type="submit">Simpan Setting</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <h3>Daftar Setting</h3>
        @foreach($settingsForManagement as $setting)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'settings', 'id' => $setting->id]) }}">
                @csrf
                @method('PUT')
                <label>Key<input type="text" name="key" value="{{ $setting->key }}" required></label>
                <label>Value<textarea name="value" rows="2">{{ $setting->value }}</textarea></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'settings', 'id' => $setting->id]) }}" onsubmit="return confirm('Hapus setting ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'logs')
    <section class="card" data-searchable>
        <h3>System Logs</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User ID</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logsForManagement as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->user_id ?? '-' }}</td>
                            <td>{{ $log->module ?? '-' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>
                                <form method="POST" action="{{ route('super-admin.logs.destroy', $log->id) }}" onsubmit="return confirm('Hapus log ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No activity logs yet. System events will be listed once users start actions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'reschedule')
    <section class="card" data-searchable>
        <h3>Reschedule Requests</h3>
        <p class="ui-card-subtitle">Daftar permintaan perubahan jadwal siswa yang memerlukan persetujuan.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php $requestObj = $row[5]; @endphp
                        <tr>
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>{{ $row[3] }}</td>
                            <td>
                                @php
                                    $status = strtolower($requestObj->status);
                                    $type = $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning');
                                @endphp
                                <x-ui.badge :type="$type">{{ strtoupper($status) }}</x-ui.badge>
                            </td>
                            <td>
                                @if($status === 'pending')
                                    <div style="display:flex; gap:0.5rem;">
                                        <form action="{{ route('super-admin.reschedule.approve', $requestObj->id) }}" method="POST" onsubmit="return confirm('Approve reschedule ini?')">
                                            @csrf
                                            <button type="submit" class="btn-res-approve" title="Approve">
                                                <i data-lucide="check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('super-admin.reschedule.reject', $requestObj->id) }}" method="POST" onsubmit="return confirm('Reject reschedule ini?')">
                                            @csrf
                                            <button type="submit" class="btn-res-reject" title="Reject">
                                                <i data-lucide="x"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">No Actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No reschedule requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <style>
        .btn-res-approve, .btn-res-reject {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-res-approve { background: rgba(34, 197, 94, 0.15); color: #86efac; }
        .btn-res-approve:hover { background: #166534; color: #fff; }
        .btn-res-reject { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }
        .btn-res-reject:hover { background: #991b1b; color: #fff; }
        .btn-res-approve i, .btn-res-reject i { width: 16px; height: 16px; }
    </style>
@endif

@if ($moduleKey === 'finance')
    <section class="stats-grid" data-searchable>
        <article class="card stat">
            <p>Total Invoice</p>
            <h2>{{ $financeSummary['total_invoice'] }}</h2>
        </article>
        <article class="card stat">
            <p>Pembayaran Berhasil</p>
            <h2>Rp{{ number_format($financeSummary['successful_payments'], 0, ',', '.') }}</h2>
        </article>
    </section>

    <section class="card" data-searchable>
        <h3>Tambah Pembayaran</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.payments.store') }}">
            @csrf
            <label>Student
                <select name="student_id" required>
                    <option value="">Pilih student</option>
                    @foreach($studentsForFinance as $student)
                        <option value="{{ $student->id }}" @selected((string) old('student_id') === (string) $student->id)>{{ $student->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Class
                <select name="class_id">
                    <option value="">Tanpa class</option>
                    @foreach($classesForFinance as $class)
                        <option value="{{ $class->id }}" @selected((string) old('class_id') === (string) $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Amount
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required>
            </label>
            <label>Status
                <select name="status" required>
                    <option value="paid" @selected(old('status', 'paid') === 'paid')>paid</option>
                    <option value="pending" @selected(old('status') === 'pending')>pending</option>
                </select>
            </label>
            <div class="form-actions">
                <button type="submit">Simpan Pembayaran</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>

    <section class="card" data-searchable>
        <h3>Daftar Pembayaran</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentsForFinance as $payment)
                        <tr>
                            <td>{{ $payment->student?->name ?? '-' }}</td>
                            <td>{{ $payment->musicClass?->name ?? '-' }}</td>
                            <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <x-ui.badge :type="$payment->status === 'paid' ? 'success' : 'warning'">
                                    {{ strtoupper($payment->status) }}
                                </x-ui.badge>
                            </td>
                            <td>{{ optional($payment->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'schedule')
    {{-- Tailwind & Alpine.js CDN for immediate result --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        .font-saas { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @php
        $scheduleFeatureReady = (bool) ($scheduleFeatureReady ?? false);
        $availableDayOptions = $dayOptions ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    @endphp

    <div 
        x-data="{ 
            studentModalOpen: false, 
            addModalOpen: false,
            studentData: {},
            showStudent(data) {
                this.studentData = data;
                this.studentModalOpen = true;
                if (window.lucide) {
                    setTimeout(() => window.lucide.createIcons(), 50);
                }
            }
        }"
        class="font-saas py-2"
    >
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-4 px-1">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Schedule Dashboard</h1>
                <p class="text-gray-400 text-[11px] font-medium tracking-wide uppercase">Music School Management System</p>
            </div>
            <div>
                <button @click="addModalOpen = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[12px] font-bold rounded-lg transition-all shadow-md shadow-indigo-100 active:scale-95">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1.5"></i>
                    Add Schedule
                </button>
            </div>
        </div>

        {{-- Main Content --}}
        @php
            $nestedSchedules = [];
            foreach ($schedulesForManagement as $scheduleItem) {
                $className = $scheduleItem->musicClass?->name ?? 'Unassigned Class';
                $teacherName = $scheduleItem->teacher?->name ?? ($scheduleItem->musicClass?->teacher?->name ?? 'Belum ada pengajar');
                $day = $scheduleItem->day;
                if (!isset($nestedSchedules[$className])) $nestedSchedules[$className] = [];
                if (!isset($nestedSchedules[$className][$teacherName])) $nestedSchedules[$className][$teacherName] = [];
                if (!isset($nestedSchedules[$className][$teacherName][$day])) $nestedSchedules[$className][$teacherName][$day] = [];
                $nestedSchedules[$className][$teacherName][$day][] = $scheduleItem;
            }
        @endphp

        <div class="space-y-3">
            @forelse ($nestedSchedules as $className => $teachers)
                <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    {{-- Class Header --}}
                    <div @click="open = !open" class="px-5 py-3 bg-white flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 ring-2 ring-indigo-50/30 transition-all">
                                <i data-lucide="music" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-[15px] font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">Class: {{ $className }}</h2>
                                <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">{{ count($teachers) }} Teachers</p>
                            </div>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transform transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </div>
                    </div>

                    {{-- Class Content (Teachers) --}}
                    <div x-show="open" x-collapse x-cloak class="px-5 pb-3 space-y-3">
                        @foreach ($teachers as $teacherName => $days)
                            <div x-data="{ openTeacher: false }" class="bg-gray-50/50 rounded-xl border border-gray-100 overflow-hidden">
                                <div @click="openTeacher = !openTeacher" class="px-4 py-2.5 flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-white border border-indigo-100 flex items-center justify-center text-indigo-500">
                                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <h3 class="text-[13px] font-bold text-gray-700">Instructor: <span class="text-indigo-600 font-extrabold">{{ $teacherName }}</span></h3>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transform transition-transform duration-200" :class="openTeacher ? 'rotate-180' : ''"></i>
                                </div>

                                {{-- Days Grid --}}
                                <div x-show="openTeacher" x-collapse x-cloak class="px-4 pb-4 pt-0">
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                                        @foreach ($days as $day => $slots)
                                            <div class="bg-white p-4 rounded-xl border border-gray-50 shadow-sm">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="px-2 py-0.5 bg-indigo-600 text-white text-[8px] font-black rounded-md tracking-wider uppercase">{{ $day }}</div>
                                                    <span class="text-[8px] font-bold text-gray-300 uppercase">{{ count($slots) }} Slots</span>
                                                </div>
                                                
                                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                                                    @foreach ($slots as $slot)
                                                        @php
                                                            $isBooked = (bool)$slot->student_id;
                                                            $timeLabel = substr((string)$slot->time, 0, 5);
                                                            $student = $slot->student;
                                                            $studentPayload = $isBooked ? [
                                                                'id' => $student?->id,
                                                                'name' => $student?->user?->name ?? ($student?->name ?? '-'),
                                                                'phone' => $student?->phone ?? '-',
                                                                'address' => $student?->address ?? '-',
                                                                'class_name' => $className,
                                                                'teacher_name' => $teacherName
                                                            ] : null;
                                                        @endphp
                                                        <div x-data="{ showActions: false }" class="relative">
                                                            <button 
                                                                @click="
                                                                    if ({{ $isBooked ? 'true' : 'false' }}) {
                                                                        showStudent(@js($studentPayload))
                                                                    } else {
                                                                        showActions = !showActions
                                                                    }
                                                                "
                                                                class="w-full py-1.5 rounded-lg text-[10px] font-black transition-all border
                                                                    {{ $isBooked 
                                                                        ? 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700 hover:scale-105 shadow-md shadow-indigo-50' 
                                                                        : 'bg-white border-gray-50 text-gray-500 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50/30' }}"
                                                            >
                                                                {{ $timeLabel }}
                                                                @if($isBooked)
                                                                    <div class="text-[6px] opacity-80 font-black">FULL</div>
                                                                @endif
                                                            </button>

                                                            @if(!$isBooked)
                                                            <div x-show="showActions" @click.away="showActions = false" x-transition.opacity class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-28 bg-gray-900 rounded-lg shadow-xl z-50 p-1 border border-white/10">
                                                                <form method="POST" action="{{ route('super-admin.schedule.destroy', $slot) }}" onsubmit="return confirm('Hapus?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="w-full flex items-center justify-center px-2 py-1.5 text-[9px] font-bold text-white hover:bg-red-500 rounded-md transition-all">
                                                                        <i data-lucide="trash-2" class="w-2.5 h-2.5 mr-1.5"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white border-2 border-dashed border-gray-100 rounded-3xl">
                    <i data-lucide="calendar-off" class="w-8 h-8 text-gray-200 mx-auto mb-4"></i>
                    <h3 class="text-base font-bold text-gray-800">No Schedules</h3>
                </div>
            @endforelse
        </div>

        {{-- Teleported Modals to Body Level --}}
        <template x-teleport="body">
            <div>
                {{-- Modal for Create Schedule --}}
                <div x-show="addModalOpen" x-cloak 
                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                    style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(1px);">
                    
                    <div x-show="addModalOpen" 
                        x-transition:enter="ease-out duration-200" 
                        x-transition:enter-start="opacity-0 scale-95" 
                        x-transition:enter-end="opacity-100 scale-100" 
                        @click.away="addModalOpen = false"
                        class="relative bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] w-full max-w-[320px] border border-gray-100/50 overflow-hidden">
                        
                        <form method="POST" action="{{ route('super-admin.schedule.store') }}" class="p-6">
                            @csrf
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-[14px] font-bold text-gray-900 tracking-tight">New Schedule</h3>
                                <button type="button" @click="addModalOpen = false" class="text-gray-300 hover:text-indigo-600 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Class & Teacher</label>
                                    <select name="class_id" class="w-full h-9 px-0 bg-transparent border-0 border-b border-gray-100 focus:border-indigo-500 focus:ring-0 text-[12px] font-bold text-gray-700">
                                        @foreach($classesForSchedule as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->teacher?->name ?? 'No' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2.5">Repeat Days</label>
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($availableDayOptions as $dayOption)
                                            <label class="flex items-center gap-1.5 cursor-pointer group">
                                                <input type="checkbox" name="days[]" value="{{ $dayOption }}" class="w-3 h-3 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-[10px] font-bold text-gray-400 group-hover:text-indigo-600">{{ substr($dayOption, 0, 3) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Start Time</label>
                                        <input type="time" name="start_time" class="w-full h-8 px-0 bg-transparent border-0 border-b border-gray-100 focus:border-indigo-500 focus:ring-0 text-[12px] font-bold text-gray-700">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">End Time</label>
                                        <input type="time" name="end_time" class="w-full h-8 px-0 bg-transparent border-0 border-b border-gray-100 focus:border-indigo-500 focus:ring-0 text-[12px] font-bold text-gray-700">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Interval (Mins)</label>
                                    <input type="number" name="interval" value="60" class="w-full h-8 px-0 bg-transparent border-0 border-b border-gray-100 focus:border-indigo-500 focus:ring-0 text-[12px] font-bold text-gray-700">
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[11px] transition-all shadow-lg shadow-indigo-100 active:scale-95">
                                    Create Schedule
                                </button>
                                <button type="button" @click="addModalOpen = false" class="w-full mt-2 py-2 text-[10px] font-bold text-gray-400 hover:text-gray-600 text-center transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Student Detail Modal --}}
                <div x-show="studentModalOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div @click="studentModalOpen = false" x-show="studentModalOpen" x-transition.opacity class="fixed inset-0 bg-white/10 backdrop-blur-[2px] transition-opacity"></div>
                    
                    <div x-show="studentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] transform transition-all w-full max-w-[320px] border border-gray-100">
                        <div class="px-6 py-8">
                            <button @click="studentModalOpen = false" class="absolute top-5 right-5 text-gray-300 hover:text-indigo-500 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>

                            <div class="flex flex-col items-center mb-6">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xl font-bold mb-4 shadow-lg shadow-indigo-100">
                                    <span x-text="studentData.name ? studentData.name.charAt(0).toUpperCase() : '?'"></span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 text-center" x-text="studentData.name || '-'"></h3>
                                <p class="text-[9px] font-bold text-indigo-500 mt-1 uppercase tracking-widest" x-text="'ID: #' + (studentData.id || '00')"></p>
                            </div>

                            <div class="space-y-4 px-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-medium text-gray-400">Class Unit</span>
                                    <span class="text-[11px] font-bold text-gray-800" x-text="studentData.class_name || '-'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-medium text-gray-400">Instructor</span>
                                    <span class="text-[11px] font-bold text-gray-800" x-text="studentData.teacher_name || '-'"></span>
                                </div>
                                
                                <div class="pt-2 space-y-4">
                                    <div class="flex items-start gap-4">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                        <div>
                                            <p class="text-[11px] font-bold text-gray-800" x-text="studentData.phone || '-'"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                        <div>
                                            <p class="text-[11px] font-bold text-gray-800 leading-relaxed" x-text="studentData.address || '-'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button @click="studentModalOpen = false" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[11px] transition-all active:scale-95">
                                    Close Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        // Re-initialize Lucide icons for the redesign
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
@endif

    {{-- Final Cleanup: Legacy Schedule Code Removed --}}

@if (! in_array($moduleKey, ['users', 'roles', 'teachers', 'schedule', 'classes', 'students', 'registrations', 'reschedule', 'finance', 'blog', 'gallery', 'events', 'testimonials', 'settings', 'logs'], true))
<section class="card" data-searchable>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        @foreach ($row as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}">No records available for this module yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const syncBodyModalState = () => {
        const hasOpenPopover = document.querySelector('details.action-popover[open]') !== null;
        document.body.classList.toggle('modal-open', hasOpenPopover);
    };

    // Close action-popover modal via X button
    document.querySelectorAll('.action-popover-close').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const details = btn.closest('details.action-popover');
            if (details) details.removeAttribute('open');
            syncBodyModalState();
        });
    });

    // Close action-popover when clicking the backdrop (the ::before pseudo-element area)
    document.addEventListener('click', e => {
        // If the click target is a summary inside an open action-popover, do nothing (native toggle)
        if (e.target.closest('details.action-popover')) return;
        // Otherwise close all open action-popovers
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
        details.addEventListener('toggle', syncBodyModalState);
    });
    syncBodyModalState();

    // Teacher edit modal: inline validation + loading state on submit
    document.querySelectorAll('.teacher-edit-modal').forEach(form => {
        const fields = form.querySelectorAll('input, select, textarea');
        const submitBtn = form.querySelector('.te-btn--primary');

        const getMessage = (field) => {
            if (!field.validity) return 'Input tidak valid.';
            if (field.validity.valueMissing) return 'Field ini wajib diisi.';
            if (field.validity.typeMismatch && field.type === 'email') return 'Masukkan format email yang valid.';
            if (field.validity.tooShort) return 'Input terlalu pendek.';
            if (field.validity.tooLong) return 'Input terlalu panjang.';
            if (field.validity.patternMismatch) return 'Format input tidak sesuai.';
            return 'Periksa kembali input ini.';
        };

        const setFieldError = (field, message) => {
            const wrapper = field.closest('.te-field');
            if (!wrapper) return;
            wrapper.classList.add('has-error');
            field.setAttribute('aria-invalid', 'true');
            const error = wrapper.querySelector('.te-error-text');
            if (error) error.textContent = message;
        };

        const clearFieldError = (field) => {
            const wrapper = field.closest('.te-field');
            if (!wrapper) return;
            wrapper.classList.remove('has-error');
            field.removeAttribute('aria-invalid');
            const error = wrapper.querySelector('.te-error-text');
            if (error) error.textContent = '';
        };

        const validateField = (field) => {
            clearFieldError(field);
            if (field.checkValidity()) return true;
            setFieldError(field, getMessage(field));
            return false;
        };

        fields.forEach(field => {
            field.addEventListener('input', () => validateField(field));
            field.addEventListener('change', () => validateField(field));
            field.addEventListener('blur', () => validateField(field));
        });

        form.addEventListener('submit', (event) => {
            let firstInvalid = null;
            let allValid = true;

            fields.forEach(field => {
                const isValid = validateField(field);
                if (!isValid) {
                    allValid = false;
                    if (!firstInvalid) firstInvalid = field;
                }
            });

            if (!allValid) {
                event.preventDefault();
                if (submitBtn) submitBtn.classList.remove('is-loading');
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            if (submitBtn) {
                submitBtn.classList.add('is-loading');
                submitBtn.setAttribute('aria-busy', 'true');
                submitBtn.disabled = true;
            }
        });
    });

    // Re-initialize Lucide icons when popovers open
    document.querySelectorAll('details.action-popover').forEach(details => {
        details.addEventListener('toggle', () => {
            if (details.open && window.lucide) {
                window.lucide.createIcons();
            }
        });
    });
});
</script>
@endpush



