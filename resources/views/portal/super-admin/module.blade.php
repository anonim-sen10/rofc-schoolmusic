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
                                    <a href="{{ route('super-admin.users.show', $userRow) }}" class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></a>
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
                                                <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <section class="registration-edit-grid">
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Nama</label>
                                                        <input type="text" name="name" value="{{ $userRow->name }}" required>
                                                    </div>
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Email</label>
                                                        <input type="email" name="email" value="{{ $userRow->email }}" required>
                                                    </div>
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Role</label>
                                                        <select name="role" required>
                                                            <option value="super_admin" @selected($userRow->hasRole('super_admin'))>Super Admin</option>
                                                            <option value="admin" @selected($userRow->hasRole('admin'))>Admin</option>
                                                            <option value="finance" @selected($userRow->hasRole('finance'))>Finance</option>
                                                            <option value="teacher" @selected($userRow->hasRole('teacher'))>Teacher</option>
                                                            <option value="student" @selected($userRow->hasRole('student'))>Siswa</option>
                                                        </select>
                                                    </div>
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Password Baru (opsional)</label>
                                                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                                                    </div>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>
                                    <form method="POST" action="{{ route('super-admin.users.destroy', $userRow) }}" onsubmit="return confirm('Hapus user ini?');">
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
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Konfirmasi Password
                    <input type="password" name="password_confirmation" required>
                </label>
                <label>Upload Foto Profile
                    <input type="file" name="photo" accept="image/*">
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
                                    <a href="{{ route('super-admin.teachers.show', $teacher) }}" class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></a>
                                    <details class="action-popover" id="teacher-edit-{{ $teacher->id }}">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form teacher-edit-modal" method="POST" enctype="multipart/form-data" action="{{ route('super-admin.teachers.update', $teacher) }}" id="teacher-edit-form-{{ $teacher->id }}" novalidate>
                                            @csrf
                                            @method('PUT')
                                            {{-- ─── HEADER ─── --}}
                                            <header class="te-modal-header">
                                                <div class="te-modal-header-left">
                                                    <span class="te-modal-icon">
                                                        <i data-lucide="user-pen"></i>
                                                    </span>
                                                    <div>
                                                        <h3 class="te-modal-title">Edit Teacher</h3>
                                                        <p class="te-modal-subtitle">Perbarui informasi data pengajar</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="te-modal-close action-popover-close" aria-label="Tutup">
                                                    <i data-lucide="x"></i>
                                                </button>
                                            </header>

                                            {{-- ─── BODY ─── --}}
                                            <div class="te-modal-body">
                                                <div class="te-modal-grid">
                                                    {{-- Row 1 --}}
                                                    <div class="te-field" id="field-name-{{ $teacher->id }}">
                                                        <label class="te-label" for="te-name-{{ $teacher->id }}">Nama</label>
                                                        <input class="te-input" type="text" id="te-name-{{ $teacher->id }}" name="name" value="{{ $teacher->name }}" placeholder="Masukkan nama lengkap" required>
                                                        <span class="te-error-text" aria-live="polite"></span>
                                                    </div>
                                                    <div class="te-field" id="field-gender-{{ $teacher->id }}">
                                                        <label class="te-label" for="te-gender-{{ $teacher->id }}">Jenis Kelamin</label>
                                                        <div class="te-select-wrap">
                                                            <select class="te-input te-select" id="te-gender-{{ $teacher->id }}" name="gender" required>
                                                                <option value="" disabled>Pilih jenis kelamin</option>
                                                                <option value="laki-laki" @selected($teacher->gender === 'laki-laki')>Laki-laki</option>
                                                                <option value="perempuan" @selected($teacher->gender === 'perempuan')>Perempuan</option>
                                                            </select>
                                                            <i data-lucide="chevron-down" class="te-select-icon"></i>
                                                        </div>
                                                        <span class="te-error-text" aria-live="polite"></span>
                                                    </div>

                                                    {{-- Row 2 --}}
                                                    <div class="te-field" id="field-email-{{ $teacher->id }}">
                                                        <label class="te-label" for="te-email-{{ $teacher->id }}">Email</label>
                                                        <input class="te-input" type="email" id="te-email-{{ $teacher->id }}" name="email" value="{{ $teacher->user?->email }}" placeholder="contoh@email.com" required>
                                                        <span class="te-error-text" aria-live="polite"></span>
                                                    </div>
                                                    <div class="te-field" id="field-religion-{{ $teacher->id }}">
                                                        <label class="te-label" for="te-religion-{{ $teacher->id }}">Agama</label>
                                                        <input class="te-input" type="text" id="te-religion-{{ $teacher->id }}" name="religion" value="{{ $teacher->religion }}" placeholder="Masukkan agama" required>
                                                        <span class="te-error-text" aria-live="polite"></span>
                                                    </div>

                                                    {{-- Row 3 --}}
                                                    <div class="te-field" id="field-phone-{{ $teacher->id }}">
                                                        <label class="te-label" for="te-phone-{{ $teacher->id }}">Nomor HP</label>
                                                        <input class="te-input" type="text" id="te-phone-{{ $teacher->id }}" name="phone" value="{{ $teacher->phone }}" placeholder="08xxxxxxxxxx" required>
                                                        <span class="te-error-text" aria-live="polite"></span>
                                                    </div>
                                                    <div class="te-field te-field--placeholder"></div>

                                                    {{-- Full-width: Alamat --}}
                                                    <div class="te-field te-field--full" id="field-address-{{ $teacher->id }}">
                                                        <label class="te-label" for="te-address-{{ $teacher->id }}">Alamat</label>
                                                        <textarea class="te-input te-textarea" id="te-address-{{ $teacher->id }}" name="address" rows="3" placeholder="Masukkan alamat lengkap" required>{{ $teacher->address }}</textarea>
                                                        <span class="te-error-text" aria-live="polite"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ─── FOOTER ─── --}}
                                            <footer class="te-modal-footer">
                                                <button type="button" class="te-btn te-btn--secondary action-popover-close">Cancel</button>
                                                <button type="submit" class="te-btn te-btn--primary">
                                                    <span class="te-btn-label">Save Changes</span>
                                                    <span class="te-btn-spinner" aria-hidden="true">
                                                        <svg class="te-spin" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="31.42 31.42" stroke-dashoffset="10"/></svg>
                                                    </span>
                                                </button>
                                            </footer>
                                        </form>
                                    </details>
                                    <form method="POST" action="{{ route('super-admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('Hapus teacher ini?');">
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
                                            <section class="registration-edit-grid">
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Nama Kelas</label>
                                                    <input type="text" name="name" value="{{ $classItem->name }}" required>
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Harga</label>
                                                    <input type="number" name="price" min="0" step="1000" value="{{ (int) ($classItem->price ?? 0) }}">
                                                </div>
                                                <div class="registration-edit-field registration-edit-field-full">
                                                    <label class="registration-edit-label">Deskripsi</label>
                                                    <textarea name="description" rows="2">{{ $classItem->description }}</textarea>
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Guru</label>
                                                    <select name="teacher_id">
                                                        <option value="">Tanpa guru</option>
                                                        @foreach ($teachersForClassOptions as $teacherOption)
                                                            <option value="{{ $teacherOption->id }}" @selected((int) $classItem->teacher_id === (int) $teacherOption->id)>{{ $teacherOption->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="registration-edit-field">
                                                    <label class="registration-edit-label">Status</label>
                                                    <select name="status" required>
                                                        <option value="active" @selected($classItem->status === 'active')>Active</option>
                                                        <option value="inactive" @selected($classItem->status === 'inactive')>Inactive</option>
                                                    </select>
                                                </div>
                                            </section>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Batal</button>
                                            <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                        </footer>
                                        </form>
                                    </details>
                                    <form method="POST" action="{{ route('super-admin.classes.destroy', $classItem) }}" onsubmit="return confirm('Hapus class ini?');">
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
                                                        <i data-lucide="eye"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Detail Siswa</h3>
                                                        <p>Informasi lengkap profil siswa</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="registration-modal-summary-name">{{ $student->name }}</p>
                                                            <p>{{ $student->email ?: 'No email' }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$student->is_active ? 'success' : 'warning'">
                                                        {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article>
                                                        <p>Umur</p>
                                                        <p>{{ $student->age ? $student->age . ' Tahun' : '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Telepon</p>
                                                        <p>{{ $student->phone ?: '-' }}</p>
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
                                                        <p>Mulai Kursus</p>
                                                        <p>{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('d M Y') : '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Berakhir Pada</p>
                                                        <p>{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('d M Y') : '-' }}</p>
                                                    </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Tutup</button>
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
                                                <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <section class="registration-edit-grid">
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Nama</label>
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
                                                            @foreach($classesForManagement as $classItem)
                                                                <option value="{{ $classItem->id }}" @selected($student->classes->contains($classItem->id))>
                                                                    {{ $classItem->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Start Date</label>
                                                        <input type="date" name="start_date" value="{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('Y-m-d') : '' }}">
                                                    </div>
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Duration (Months)</label>
                                                        <select name="duration_months">
                                                            @foreach([1, 2, 3, 4, 6, 12] as $m)
                                                                <option value="{{ $m }}" @selected((int)($student->duration_months ?? 0) === $m)>{{ $m }} Bulan</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="registration-edit-field">
                                                        <label class="registration-edit-label">Status</label>
                                                        <select name="is_active">
                                                            <option value="1" @selected($student->is_active)>Active</option>
                                                            <option value="0" @selected(!$student->is_active)>Inactive</option>
                                                        </select>
                                                    </div>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>

                                    {{-- Delete Button --}}
                                    <form method="POST" action="{{ route('super-admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?');">
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
        .registration-modal {
            position: fixed;
            inset: 0;
            z-index: 80;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .2s ease, visibility .2s ease;
        }

        [data-registration-edit-modal] {
            z-index: 90;
        }

        .registration-modal.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .registration-modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(2, 6, 23, .58);
            backdrop-filter: blur(4px);
        }

        .registration-modal-panel {
            position: relative;
            width: min(700px, 100%);
            max-height: calc(100vh - 2rem);
            border-radius: 1rem;
            border: 1px solid #d5deeb;
            background: #fff;
            box-shadow: 0 28px 56px rgba(2, 6, 23, .35);
            overflow: hidden;
            transform: scale(.96);
            opacity: 0;
            transition: transform .2s ease, opacity .2s ease;
        }

        .registration-modal.is-open .registration-modal-panel {
            transform: scale(1);
            opacity: 1;
        }

        .registration-modal-header {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            align-items: flex-start;
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .registration-modal-header-left {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .registration-modal-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: .75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: #eff6ff;
        }

        .registration-modal-header h3 {
            margin: 0;
            color: #0f172a;
            font-size: 1.02rem;
        }

        .registration-modal-header p {
            margin: .18rem 0 0;
            color: #64748b;
            font-size: .84rem;
        }

        .registration-modal-close-btn {
            width: 2.1rem;
            height: 2.1rem;
            border-radius: .6rem;
            border: 1px solid #dbe3ef;
            background: #fff;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .registration-modal-close-btn:hover {
            background: #f8fafc;
            color: #334155;
        }

        .registration-modal-body {
            max-height: 68vh;
            overflow-y: auto;
            padding: 1.1rem 1.25rem;
        }

        .registration-modal-summary {
            margin-bottom: .9rem;
            border-radius: .9rem;
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            padding: .8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
        }

        .registration-modal-summary-left {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .registration-modal-avatar {
            width: 2.7rem;
            height: 2.7rem;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e40af;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .registration-modal-summary p {
            margin: 0;
            color: #64748b;
            font-size: .8rem;
        }

        .registration-modal-summary-name {
            margin-top: .2rem !important;
            color: #0f172a !important;
            font-size: .95rem !important;
            font-weight: 700;
        }

        .registration-modal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem;
        }

        .registration-modal-grid > article {
            border: 1px solid #dbe3ef;
            border-radius: .75rem;
            padding: .7rem;
            background: #fff;
        }

        .registration-modal-grid > article > p:first-child {
            margin: 0;
            font-size: .76rem;
            color: #64748b;
        }

        .registration-modal-grid > article > p:last-child {
            margin: .24rem 0 0;
            color: #0f172a;
            font-weight: 600;
            line-height: 1.45;
        }

        .registration-modal-item-full {
            grid-column: 1 / -1;
        }

        .registration-status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: .28rem .7rem;
            font-size: .72rem;
            font-weight: 700;
            background: #e2e8f0;
            color: #334155;
        }

        .registration-status-badge.is-success {
            background: #dcfce7;
            color: #166534;
        }

        .registration-status-badge.is-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .registration-status-badge.is-danger {
            background: #ffe4e6;
            color: #be123c;
        }

        .registration-status-badge.is-neutral {
            background: #e2e8f0;
            color: #334155;
        }

        .registration-modal-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .55rem;
            padding: .9rem 1.25rem 1.1rem;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }

        .registration-modal-btn {
            border-radius: .65rem;
            border: 1px solid transparent;
            padding: .52rem .9rem;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
        }

        .registration-modal-btn-secondary {
            border-color: #cbd5e1;
            background: #fff;
            color: #334155;
        }

        .registration-modal-btn-secondary:hover {
            background: #f8fafc;
        }

        .registration-modal-btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .registration-modal-btn-primary:hover {
            background: #1d4ed8;
        }

        .registration-modal-btn-danger {
            background: #e11d48;
            color: #fff;
        }

        .registration-modal-btn-danger:hover {
            background: #be123c;
        }

        .registration-edit-form .registration-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem;
        }

        .registration-edit-form .registration-edit-grid > label {
            display: grid;
            gap: .35rem;
            color: #334155;
            font-size: .82rem;
        }

        .registration-edit-form .registration-edit-field-full {
            grid-column: 1 / -1;
        }

        .registration-edit-form input,
        .registration-edit-form select,
        .registration-edit-form textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: .75rem;
            padding: .52rem .7rem;
            font-size: .84rem;
            color: #0f172a;
            background: #fff;
        }

        .registration-edit-form select[multiple] {
            min-height: 5.9rem;
        }

        .registration-edit-form input:focus,
        .registration-edit-form select:focus,
        .registration-edit-form textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, .15);
        }

        .registration-edit-footer {
            margin-top: 1rem;
            padding: .9rem 0 0;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 900px) {
            .registration-modal {
                padding: .75rem;
            }

            .registration-modal-header,
            .registration-modal-body,
            .registration-modal-footer {
                padding-left: .9rem;
                padding-right: .9rem;
            }

            .registration-modal-grid,
            .registration-edit-form .registration-edit-grid {
                grid-template-columns: 1fr;
            }
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

                                    <button
                                        type="button"
                                        class="btn-icon"
                                        title="Detail"
                                        aria-label="Detail"
                                        data-registration-detail-trigger
                                        data-registration='@json($detailPayload, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT)'
                                    ><i data-lucide="eye"></i></button>

                                    <button
                                        type="button"
                                        class="btn-icon"
                                        id="{{ $editTriggerId }}"
                                        title="Edit"
                                        aria-label="Edit"
                                        data-registration-edit-trigger
                                        data-registration='@json($detailPayload, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT)'
                                    ><i data-lucide="pencil-line"></i></button>
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

    <div
        class="registration-modal"
        data-registration-modal
        aria-hidden="true"
    >
        <div class="registration-modal-overlay" data-registration-modal-overlay></div>

        <div
            class="registration-modal-panel"
            data-registration-modal-panel
            role="dialog"
            aria-modal="true"
            aria-labelledby="registration-modal-title"
        >
            <header class="registration-modal-header">
                <div class="registration-modal-header-left">
                    <span class="registration-modal-icon">
                        <i data-lucide="clipboard-list"></i>
                    </span>
                    <div>
                        <h3 id="registration-modal-title">Detail Pendaftaran</h3>
                        <p>Informasi lengkap data siswa</p>
                    </div>
                </div>

                <button
                    type="button"
                    class="registration-modal-close-btn"
                    data-registration-modal-close
                    aria-label="Close"
                >
                    <i data-lucide="x"></i>
                </button>
            </header>

            <div class="registration-modal-body">
                <section class="registration-modal-summary">
                    <div class="registration-modal-summary-left">
                        <span class="registration-modal-avatar" id="registration-modal-avatar">-</span>
                        <div>
                            <p>Nama Siswa</p>
                            <p class="registration-modal-summary-name" data-registration-field="fullName">-</p>
                        </div>
                    </div>
                    <span class="registration-status-badge" data-registration-status-badge>-</span>
                </section>

                <section class="registration-modal-grid">
                    <article><p>Nama Panggilan</p><p data-registration-field="nickName">-</p></article>
                    <article><p>Jenis Kelamin</p><p data-registration-field="gender">-</p></article>
                    <article><p>Tempat Lahir</p><p data-registration-field="birthPlace">-</p></article>
                    <article><p>Tanggal Lahir</p><p data-registration-field="birthDate">-</p></article>
                    <article><p>Kewarganegaraan</p><p data-registration-field="citizenship">-</p></article>
                    <article><p>No HP Siswa</p><p data-registration-field="studentPhone">-</p></article>
                    <article class="registration-modal-item-full"><p>Email Siswa</p><p data-registration-field="studentEmail">-</p></article>
                    <article class="registration-modal-item-full"><p>Alamat</p><p data-registration-field="address">-</p></article>

                    <article><p>Nama Orang Tua</p><p data-registration-field="parentName">-</p></article>
                    <article><p>Pekerjaan Orang Tua</p><p data-registration-field="parentJob">-</p></article>
                    <article><p>No HP Orang Tua</p><p data-registration-field="parentPhone">-</p></article>
                    <article><p>Email Orang Tua</p><p data-registration-field="parentEmail">-</p></article>

                    <article><p>Instrumen</p><p data-registration-field="instrument">-</p></article>
                    <article><p>Program Tambahan</p><p data-registration-field="additionalProgram">-</p></article>
                    <article><p>Pengalaman Belajar</p><p data-registration-field="experience">-</p></article>
                    <article class="registration-modal-item-full"><p>Deskripsi Pengalaman</p><p data-registration-field="experienceDescription">-</p></article>
                    <article><p>Status</p><p data-registration-field="status">-</p></article>
                    <article><p>Kelas Terpilih</p><p data-registration-field="selectedClass">-</p></article>
                </section>

                <section class="registration-modal-schedules-section">
                    <div class="registration-modal-schedules-header">
                        <h4>Jadwal Dipilih</h4>
                        <span class="registration-modal-schedules-badge" id="registration-modal-schedules-badge">0 Jadwal</span>
                    </div>
                    <div id="registration-modal-schedules-list" class="registration-modal-schedules-list">
                        <!-- JS populated -->
                    </div>
                </section>
            </div>

            <footer class="registration-modal-footer">
                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" data-registration-modal-close>Tutup</button>
                <button type="button" class="registration-modal-btn registration-modal-btn-primary" data-registration-modal-edit>Edit Data</button>
                <button type="button" class="registration-modal-btn registration-modal-btn-danger" data-registration-modal-delete>Hapus</button>
            </footer>
        </div>
    </div>

    <div
        class="registration-modal"
        data-registration-edit-modal
        aria-hidden="true"
    >
        <div class="registration-modal-overlay" data-registration-edit-modal-overlay></div>

        <div
            class="registration-modal-panel"
            data-registration-edit-modal-panel
            role="dialog"
            aria-modal="true"
            aria-labelledby="registration-edit-modal-title"
        >
            <header class="registration-modal-header">
                <div class="registration-modal-header-left">
                    <span class="registration-modal-icon">
                        <i data-lucide="pencil-line"></i>
                    </span>
                    <div>
                        <h3 id="registration-edit-modal-title">Edit Data Pendaftaran</h3>
                        <p>Perbarui informasi pendaftaran siswa</p>
                    </div>
                </div>

                <button
                    type="button"
                    class="registration-modal-close-btn"
                    data-registration-edit-modal-close
                    aria-label="Close"
                >
                    <i data-lucide="x"></i>
                </button>
            </header>

            <form method="POST" class="registration-modal-body registration-edit-form" data-registration-edit-form>
                @csrf
                @method('PUT')

                <section class="registration-edit-grid">
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" required>
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" required>
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" required>
                    </div>

                    <div class="registration-edit-field registration-edit-field-full">
                        <label class="registration-edit-label">Alamat</label>
                        <textarea name="alamat" rows="3" required></textarea>
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">No HP Siswa</label>
                        <input type="tel" name="no_hp_siswa" required>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Email Siswa</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Nama Orang Tua</label>
                        <input type="text" name="nama_ortu" required>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Pekerjaan Orang Tua</label>
                        <input type="text" name="pekerjaan_ortu">
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">No HP Orang Tua</label>
                        <input type="tel" name="no_hp_ortu" required>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Email Orang Tua</label>
                        <input type="email" name="email_ortu">
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Instrumen</label>
                        <select name="instrumen" required>
                            <option value="">Pilih instrumen</option>
                            @foreach($instrumenOptions as $instrumenItem)
                                <option value="{{ $instrumenItem }}">{{ $instrumenItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Program Tambahan</label>
                        <select name="program_tambahan[]" multiple>
                            @foreach($programTambahanOptions as $programItem)
                                <option value="{{ $programItem }}">{{ $programItem }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Hari Pilihan</label>
                        <select name="hari_pilihan[]" multiple required>
                            @foreach($hariOptions as $hariItem)
                                <option value="{{ $hariItem }}">{{ $hariItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Pernah Belajar Musik</label>
                        <select name="pengalaman" required>
                            <option value="">Pilih</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>

                    <div class="registration-edit-field registration-edit-field-full">
                        <label class="registration-edit-label">Deskripsi Pengalaman</label>
                        <textarea name="deskripsi_pengalaman" rows="3"></textarea>
                    </div>

                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Kelas</label>
                        <select name="class_id">
                            <option value="">Pilih kelas</option>
                            @foreach($classesForManagement as $classItem)
                                <option value="{{ $classItem->id }}">{{ $classItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="registration-edit-field">
                        <label class="registration-edit-label">Status</label>
                        <select name="status" required>
                            <option value="">Pilih status</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </section>

                <footer class="registration-modal-footer registration-edit-footer">
                    <button type="button" class="registration-modal-btn registration-modal-btn-secondary" data-registration-edit-modal-close>Batal</button>
                    <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                </footer>
            </form>
        </div>
    </div>

    <form method="POST" data-registration-modal-delete-form style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const detailModal = document.querySelector("[data-registration-modal]");
            const editModal = document.querySelector("[data-registration-edit-modal]");

            if (!detailModal || !editModal) {
                return;
            }

            const detailPanel = detailModal.querySelector("[data-registration-modal-panel]");
            const detailOverlay = detailModal.querySelector("[data-registration-modal-overlay]");
            const detailCloseButtons = detailModal.querySelectorAll("[data-registration-modal-close]");
            const detailTriggerButtons = document.querySelectorAll("[data-registration-detail-trigger]");
            const detailEditButton = detailModal.querySelector("[data-registration-modal-edit]");
            const detailDeleteButton = detailModal.querySelector("[data-registration-modal-delete]");

            const editPanel = editModal.querySelector("[data-registration-edit-modal-panel]");
            const editOverlay = editModal.querySelector("[data-registration-edit-modal-overlay]");
            const editCloseButtons = editModal.querySelectorAll("[data-registration-edit-modal-close]");
            const editTriggerButtons = document.querySelectorAll("[data-registration-edit-trigger]");
            const editForm = editModal.querySelector("[data-registration-edit-form]");

            const deleteForm = document.querySelector("[data-registration-modal-delete-form]");
            const avatar = document.getElementById("registration-modal-avatar");
            const statusBadge = detailModal.querySelector("[data-registration-status-badge]");
            const fieldNodes = new Map(
                Array.from(detailModal.querySelectorAll("[data-registration-field]"))
                    .map((node) => [node.getAttribute("data-registration-field"), node]),
            );

            let activePayload = null;
            let activeDeleteAction = "";

            const parsePayload = (button) => {
                let payload = {};
                try {
                    payload = JSON.parse(button.getAttribute("data-registration") || "{}");
                } catch {
                    payload = {};
                }

                return payload;
            };

            const syncBodyScroll = () => {
                const detailOpen = detailModal.getAttribute("aria-hidden") === "false";
                const editOpen = editModal.getAttribute("aria-hidden") === "false";
                document.body.style.overflow = detailOpen || editOpen ? "hidden" : "";
            };

            const setModalState = (modal, panel, isOpen) => {
                if (isOpen) {
                    modal.style.display = "flex";
                    requestAnimationFrame(() => {
                        modal.classList.add("is-open");
                    });
                } else {
                    modal.classList.remove("is-open");
                    modal.style.display = "none";
                }

                modal.setAttribute("aria-hidden", String(!isOpen));
                syncBodyScroll();
            };

            const setField = (key, value) => {
                const target = fieldNodes.get(key);
                if (!target) {
                    return;
                }

                const safeValue = String(value ?? "").trim();
                target.textContent = safeValue !== "" ? safeValue : "-";
            };

            const applyStatusBadge = (statusValue) => {
                const safeStatus = String(statusValue || "-").toUpperCase();
                statusBadge.textContent = safeStatus;
                statusBadge.className = "registration-status-badge";

                if (safeStatus === "ACCEPTED" || safeStatus === "DITERIMA") {
                    statusBadge.classList.add("is-success");
                    return;
                }

                if (safeStatus === "PENDING") {
                    statusBadge.classList.add("is-warning");
                    return;
                }

                if (safeStatus === "REJECTED" || safeStatus === "DITOLAK") {
                    statusBadge.classList.add("is-danger");
                    return;
                }

                statusBadge.classList.add("is-neutral");
            };

            const normalizeArray = (value) => {
                if (Array.isArray(value)) {
                    return value.map((item) => String(item).trim()).filter(Boolean);
                }

                const safeValue = String(value ?? "").trim();
                if (safeValue === "" || safeValue === "-") {
                    return [];
                }

                return safeValue.split(",").map((item) => item.trim()).filter(Boolean);
            };

            const setInputValue = (name, value) => {
                const element = editForm.elements.namedItem(name);
                if (!element) {
                    return;
                }

                const safeValue = String(value ?? "").trim();
                element.value = safeValue === "-" ? "" : safeValue;
            };

            const setMultiSelectValues = (name, values) => {
                const element = editForm.elements.namedItem(name);
                if (!(element instanceof HTMLSelectElement)) {
                    return;
                }

                const selected = new Set(values);
                Array.from(element.options).forEach((option) => {
                    option.selected = selected.has(option.value);
                });
            };

            const openEditModal = (payload) => {
                activePayload = payload || activePayload || {};
                if (!activePayload || !editForm) {
                    return;
                }

                if (activePayload.updateAction) {
                    editForm.setAttribute("action", String(activePayload.updateAction));
                }

                setInputValue("nama_lengkap", activePayload.fullName);
                setInputValue("nama_panggilan", activePayload.nickName);
                setInputValue("jenis_kelamin", activePayload.gender);
                setInputValue("tempat_lahir", activePayload.birthPlace);
                setInputValue("tanggal_lahir", activePayload.birthDateInput);
                setInputValue("kewarganegaraan", activePayload.citizenship === "-" ? "Indonesia" : activePayload.citizenship);
                setInputValue("alamat", activePayload.address);
                setInputValue("no_hp_siswa", activePayload.studentPhone);
                setInputValue("email", activePayload.studentEmail);
                setInputValue("nama_ortu", activePayload.parentName);
                setInputValue("pekerjaan_ortu", activePayload.parentJob);
                setInputValue("no_hp_ortu", activePayload.parentPhone);
                setInputValue("email_ortu", activePayload.parentEmail);
                setInputValue("instrumen", activePayload.instrumentValue || activePayload.instrument);
                setInputValue("pengalaman", activePayload.experienceValue || "0");
                setInputValue("deskripsi_pengalaman", activePayload.experienceDescription);
                setInputValue("class_id", activePayload.classId || "");
                setInputValue("status", activePayload.statusValue || "pending");

                setMultiSelectValues("program_tambahan[]", normalizeArray(activePayload.additionalProgramRaw || activePayload.additionalProgram));
                setMultiSelectValues("hari_pilihan[]", normalizeArray(activePayload.preferredDaysRaw || activePayload.preferredDays));

                setModalState(editModal, editPanel, true);
            };

            const closeDetailModal = () => {
                setModalState(detailModal, detailPanel, false);
                activeDeleteAction = "";
            };

            const closeEditModal = () => {
                setModalState(editModal, editPanel, false);
            };

            const openModalWithData = (payload) => {
                const fields = [
                    "fullName",
                    "nickName",
                    "gender",
                    "birthPlace",
                    "birthDate",
                    "citizenship",
                    "studentPhone",
                    "studentEmail",
                    "address",
                    "parentName",
                    "parentJob",
                    "parentPhone",
                    "parentEmail",
                    "instrument",
                    "additionalProgram",
                    "preferredDays",
                    "experience",
                    "experienceDescription",
                    "status",
                    "selectedClass",
                ];

                fields.forEach((key) => setField(key, payload[key]));
                applyStatusBadge(payload.status);

                // Handle schedules list
                const schedulesList = document.getElementById("registration-modal-schedules-list");
                const schedulesBadge = document.getElementById("registration-modal-schedules-badge");
                if (schedulesList) {
                    const schedules = payload.schedules || [];
                    if (schedulesBadge) {
                        schedulesBadge.textContent = `${schedules.length} Jadwal Dipilih`;
                        schedulesBadge.style.display = schedules.length > 0 ? 'inline-block' : 'none';
                    }
                    if (schedules.length > 0) {
                        schedulesList.innerHTML = `<ul class="registration-modal-schedules-ul">${schedules.map(s => `<li><i data-lucide="check-circle-2"></i> ${s.label.replace(' ', ' - ')}</li>`).join('')}</ul>`;
                        if (window.lucide) window.lucide.createIcons();
                    } else {
                        schedulesList.innerHTML = '<p class="no-schedule">No schedule selected</p>';
                    }
                }

                const fullName = String(payload.fullName || "-").trim();
                avatar.textContent = fullName === "-"
                    ? "-"
                    : fullName
                        .split(/\s+/)
                        .filter(Boolean)
                        .slice(0, 2)
                        .map((chunk) => chunk.charAt(0).toUpperCase())
                        .join("");

                activePayload = payload;
                activeDeleteAction = String(payload.deleteAction || "");

                setModalState(detailModal, detailPanel, true);
            };

            detailTriggerButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    openModalWithData(parsePayload(button));
                });
            });

            editTriggerButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    openEditModal(parsePayload(button));
                });
            });

            detailCloseButtons.forEach((button) => {
                button.addEventListener("click", closeDetailModal);
            });

            editCloseButtons.forEach((button) => {
                button.addEventListener("click", closeEditModal);
            });

            detailOverlay.addEventListener("click", closeDetailModal);
            editOverlay.addEventListener("click", closeEditModal);

            document.addEventListener("keydown", (event) => {
                if (event.key !== "Escape") {
                    return;
                }

                if (editModal.getAttribute("aria-hidden") === "false") {
                    closeEditModal();
                    return;
                }

                if (detailModal.getAttribute("aria-hidden") === "false") {
                    closeDetailModal();
                }
            });

            detailEditButton.addEventListener("click", () => {
                closeDetailModal();
                if (!activePayload) {
                    return;
                }

                openEditModal(activePayload);
            });

            detailDeleteButton.addEventListener("click", () => {
                if (!activeDeleteAction || !deleteForm) {
                    return;
                }

                const confirmed = window.confirm("Hapus registration ini?");
                if (!confirmed) {
                    return;
                }

                deleteForm.setAttribute("action", activeDeleteAction);
                deleteForm.submit();
            });
        });
    </script>
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
    @php
        $scheduleFeatureReady = (bool) ($scheduleFeatureReady ?? false);
        $availableDayOptions = $dayOptions ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $openScheduleCreate = $errors->hasAny(['class_id', 'day', 'start_time', 'end_time', 'interval']);
    @endphp

    @if (! $scheduleFeatureReady)
        <section class="card" data-searchable>
            <x-ui.badge type="danger">ERROR</x-ui.badge>
            <p style="margin-top: 0.5rem;">Fitur schedule belum aktif karena tabel <strong>schedules</strong> belum ada. Jalankan migrasi di server: <strong>php artisan migrate --force</strong>.</p>
        </section>
    @else
        <section class="card" data-searchable>
            <details class="teacher-create" @if($openScheduleCreate) open @endif>
                <summary>Tambah Jadwal Class</summary>
                <form class="module-form module-form-grid teacher-create-form" method="POST" action="{{ route('super-admin.schedule.store') }}">
                    @csrf
                    <label>Class
                        <select name="class_id" data-schedule-class-select required>
                            <option value="">Pilih class</option>
                            @foreach($classesForSchedule as $class)
                                <option
                                    value="{{ $class->id }}"
                                    data-teacher-name="{{ $class->teacher?->name ?? 'Belum ada pengajar' }}"
                                    @selected((string) old('class_id') === (string) $class->id)
                                >{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Hari
                        <select name="day" required>
                            <option value="">Pilih hari</option>
                            @foreach($availableDayOptions as $dayOption)
                                <option value="{{ $dayOption }}" @selected(old('day') === $dayOption)>{{ $dayOption }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Start Time
                        <input type="time" name="start_time" value="{{ old('start_time') }}" required>
                    </label>
                    <label>End Time
                        <input type="time" name="end_time" value="{{ old('end_time') }}" required>
                    </label>
                    <label>Interval (menit)
                        <input type="number" name="interval" value="{{ old('interval', 60) }}" min="1" required>
                    </label>
                    <label>Teacher (otomatis dari class)
                        <input type="text" value="-" data-schedule-teacher-preview readonly>
                    </label>
                    <div class="form-actions">
                        <button type="submit">Simpan Jadwal</button>
                        <button type="reset" class="btn-secondary">Cancel</button>
                    </div>
                </form>
            </details>
        </section>

        <section class="card" data-searchable>
            <h3>Daftar Jadwal Class</h3>
            @php
                $nestedSchedules = [];
                foreach ($schedulesForManagement as $scheduleItem) {
                    $className = $scheduleItem->musicClass?->name ?? 'Unknown Class';
                    $teacherName = $scheduleItem->teacher?->name ?? ($scheduleItem->musicClass?->teacher?->name ?? 'Unknown Teacher');
                    $day = $scheduleItem->day;
                    
                    if (!isset($nestedSchedules[$className])) {
                        $nestedSchedules[$className] = [];
                    }
                    if (!isset($nestedSchedules[$className][$teacherName])) {
                        $nestedSchedules[$className][$teacherName] = [];
                    }
                    if (!isset($nestedSchedules[$className][$teacherName][$day])) {
                        $nestedSchedules[$className][$teacherName][$day] = [];
                    }
                    $nestedSchedules[$className][$teacherName][$day][] = $scheduleItem;
                }
            @endphp

            <style>
                .schedule-nested-container {
                    margin-top: 1.5rem;
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }
                
                /* Class Accordion */
                .class-details {
                    border: 1px solid #e2e8f0;
                    border-radius: 0.75rem;
                    background: #fff;
                    overflow: hidden;
                }
                .class-summary {
                    padding: 1rem 1.25rem;
                    background: #f8fafc;
                    font-weight: 600;
                    font-size: 1.05rem;
                    color: #0f172a;
                    cursor: pointer;
                    list-style: none;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    user-select: none;
                }
                .class-summary::-webkit-details-marker {
                    display: none;
                }
                .class-summary i {
                    color: #64748b;
                    transition: transform 0.2s ease;
                }
                .class-details[open] > .class-summary i {
                    transform: rotate(90deg);
                }
                .class-body {
                    padding: 1.25rem;
                    display: flex;
                    flex-direction: column;
                    gap: 0.85rem;
                }

                /* Teacher Accordion */
                .teacher-details {
                    border: 1px solid #e2e8f0;
                    border-radius: 0.5rem;
                    background: #fff;
                    overflow: hidden;
                }
                .teacher-summary {
                    padding: 0.85rem 1rem;
                    font-weight: 600;
                    font-size: 0.95rem;
                    color: #1e293b;
                    cursor: pointer;
                    list-style: none;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    user-select: none;
                }
                .teacher-summary::-webkit-details-marker {
                    display: none;
                }
                .teacher-summary i {
                    color: #64748b;
                    transition: transform 0.2s ease;
                    width: 18px;
                    height: 18px;
                }
                .teacher-details[open] > .teacher-summary i {
                    transform: rotate(90deg);
                }
                .teacher-details[open] > .teacher-summary {
                    border-bottom: 1px solid #e2e8f0;
                    background: #f8fafc;
                }
                .teacher-body {
                    padding: 0;
                }

                /* Day Group */
                .day-group {
                    padding: 1rem;
                }
                .day-group:not(:last-child) {
                    border-bottom: 1px dashed #e2e8f0;
                }
                .day-title {
                    font-size: 0.95rem;
                    font-weight: 600;
                    color: #334155;
                    margin-bottom: 0.75rem;
                }
                
                /* Time Slots */
                .schedule-slots-grid {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.75rem;
                }
                .schedule-slot-btn {
                    background: #f1f5f9;
                    color: #475569;
                    border: 1px solid #cbd5e1;
                    border-radius: 0.5rem;
                    padding: 0.5rem 1rem;
                    font-size: 0.875rem;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                .schedule-slot-btn:hover {
                    background: #e2e8f0;
                    border-color: #94a3b8;
                }
                .schedule-slot-btn.is-booked {
                    background: #dcfce7;
                    color: #166534;
                    border-color: #bbf7d0;
                }
                .schedule-slot-btn.is-booked:hover {
                    background: #bbf7d0;
                    border-color: #86efac;
                }
            </style>

            <div class="schedule-nested-container">
                @forelse ($nestedSchedules as $className => $teachers)
                    <details class="class-details">
                        <summary class="class-summary">
                            <i data-lucide="chevron-right"></i>
                            Class: {{ $className }}
                        </summary>
                        <div class="class-body">
                            @foreach ($teachers as $teacherName => $days)
                                <details class="teacher-details">
                                    <summary class="teacher-summary">
                                        <i data-lucide="chevron-right"></i>
                                        Teacher: {{ $teacherName }}
                                    </summary>
                                    <div class="teacher-body">
                                        @foreach ($days as $day => $slots)
                                            <div class="day-group">
                                                <h4 class="day-title">{{ $day }}</h4>
                                                <div class="schedule-slots-grid">
                                                    @foreach ($slots as $slot)
                                                        @php
                                                            $timeValue = substr((string) $slot->time, 0, 5);
                                                            $isBooked = strtolower((string) $slot->status) === 'booked';
                                                            $student = $slot->student;
                                                            $studentName = $student?->user?->name ?? ($student?->name ?? '-');
                                                            $studentPhone = $student?->phone ?? '-';
                                                            $studentAddress = $student?->address ?? '-';
                                                            
                                                            $slotPayload = [
                                                                'time' => $timeValue,
                                                                'status' => strtolower((string) $slot->status),
                                                                'isBooked' => $isBooked,
                                                                'studentName' => $studentName,
                                                                'teacherName' => $teacherName,
                                                                'className' => $className,
                                                                'day' => $day,
                                                                'address' => $studentAddress,
                                                                'phone' => $studentPhone,
                                                            ];
                                                        @endphp
                                                        <button 
                                                            type="button" 
                                                            class="schedule-slot-btn {{ $isBooked ? 'is-booked' : '' }}"
                                                            data-schedule-slot='@json($slotPayload, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT)'
                                                            onclick="openScheduleModal(this)"
                                                        >
                                                            {{ $timeValue }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p style="color: #64748b;">No schedule available.</p>
                @endforelse
            </div>

            <!-- Modal for Schedule Details -->
            <div class="registration-modal" id="schedule-detail-modal" aria-hidden="true">
                <div class="registration-modal-overlay" onclick="closeScheduleModal()"></div>
                <div class="registration-modal-panel" role="dialog" aria-modal="true" style="max-width: 500px;">
                    <header class="registration-modal-header">
                        <div class="registration-modal-header-left">
                            <span class="registration-modal-icon">
                                <i data-lucide="calendar"></i>
                            </span>
                            <div>
                                <h3 id="schedule-modal-title">Detail Jadwal</h3>
                                <p>Informasi lengkap slot waktu kelas</p>
                            </div>
                        </div>
                        <button type="button" class="registration-modal-close-btn" onclick="closeScheduleModal()" aria-label="Close">
                            <i data-lucide="x"></i>
                        </button>
                    </header>

                    <div class="registration-modal-body">
                        <!-- Used for available slot -->
                        <div id="schedule-available-message" style="display: none; padding: 2rem; text-align: center; color: #64748b;">
                            <i data-lucide="info" style="width: 3rem; height: 3rem; margin: 0 auto 1rem; color: #94a3b8;"></i>
                            <p style="font-size: 1.1rem; font-weight: 500; color: #0f172a;">This slot is available</p>
                        </div>

                        <!-- Used for booked slot -->
                        <div id="schedule-booked-details" style="display: none;">
                            <section class="registration-modal-summary">
                                <div class="registration-modal-summary-left">
                                    <span class="registration-modal-avatar" id="schedule-student-avatar">-</span>
                                    <div>
                                        <p>Nama Siswa</p>
                                        <p class="registration-modal-summary-name" id="schedule-student-name">-</p>
                                    </div>
                                </div>
                                <span class="registration-status-badge is-success">BOOKED</span>
                            </section>

                            <section class="registration-modal-grid">
                                <article><p>Class Name</p><p id="schedule-class-name">-</p></article>
                                <article><p>Teacher Name</p><p id="schedule-teacher-name">-</p></article>
                                <article><p>Day</p><p id="schedule-day">-</p></article>
                                <article><p>Time</p><p id="schedule-time">-</p></article>
                                <article><p>Phone Number</p><p id="schedule-phone">-</p></article>
                                <article><p>Address</p><p id="schedule-address">-</p></article>
                            </section>
                        </div>
                    </div>
                    
                    <footer class="registration-modal-footer">
                        <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="closeScheduleModal()">Tutup</button>
                    </footer>
                </div>
            </div>

            <script>
                function openScheduleModal(button) {
                    const payload = JSON.parse(button.getAttribute('data-schedule-slot') || '{}');
                    const modal = document.getElementById('schedule-detail-modal');
                    
                    const availableMsg = document.getElementById('schedule-available-message');
                    const bookedDetails = document.getElementById('schedule-booked-details');
                    
                    if (payload.isBooked) {
                        availableMsg.style.display = 'none';
                        bookedDetails.style.display = 'block';
                        
                        document.getElementById('schedule-student-name').textContent = payload.studentName;
                        document.getElementById('schedule-student-avatar').textContent = payload.studentName.charAt(0).toUpperCase();
                        document.getElementById('schedule-class-name').textContent = payload.className;
                        document.getElementById('schedule-teacher-name').textContent = payload.teacherName;
                        document.getElementById('schedule-day').textContent = payload.day;
                        document.getElementById('schedule-time').textContent = payload.time;
                        document.getElementById('schedule-phone').textContent = payload.phone;
                        document.getElementById('schedule-address').textContent = payload.address;
                    } else {
                        availableMsg.style.display = 'block';
                        bookedDetails.style.display = 'none';
                    }
                    
                    modal.style.display = "flex";
                    requestAnimationFrame(() => {
                        modal.classList.add("is-open");
                    });
                    modal.setAttribute("aria-hidden", "false");
                    document.body.style.overflow = "hidden";
                    
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }
                
                function closeScheduleModal() {
                    const modal = document.getElementById('schedule-detail-modal');
                    modal.classList.remove("is-open");
                    modal.setAttribute("aria-hidden", "true");
                    setTimeout(() => {
                        modal.style.display = "none";
                        document.body.style.overflow = "";
                    }, 200);
                }
            </script>
        </section>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const classSelect = document.querySelector("[data-schedule-class-select]");
                const teacherPreview = document.querySelector("[data-schedule-teacher-preview]");

                if (!classSelect || !teacherPreview) {
                    return;
                }

                const syncTeacherPreview = () => {
                    const selectedOption = classSelect.options[classSelect.selectedIndex];
                    const teacherName = selectedOption ? selectedOption.getAttribute("data-teacher-name") : "";
                    teacherPreview.value = teacherName && teacherName.trim() !== "" ? teacherName : "Belum ada pengajar";
                };

                classSelect.addEventListener("change", syncTeacherPreview);
                syncTeacherPreview();
            });
        </script>
    @endif
@endif

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
});
</script>
@endpush
