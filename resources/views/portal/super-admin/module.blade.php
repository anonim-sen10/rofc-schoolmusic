@extends('portal.layout')

@section('title', $moduleTitle.' | ROFC')
@section('page-title', $moduleTitle)

@section('content')
<section class="dashboard-hero module-head" data-searchable>
    <div>
        <p class="eyebrow">Module Workspace</p>
        <h2>{{ $moduleTitle }}</h2>
        <p>{{ $moduleDescription }}</p>
    </div>
    <div class="hero-actions">
        <a class="ghost-btn" href="{{ route('super-admin.dashboard') }}">Back to Dashboard</a>
    </div>
</section>

@if (session('success'))
    <section class="card" data-searchable>
        <x-ui.badge type="success">SUCCESS</x-ui.badge>
        <p style="margin-top: 0.5rem;">{{ session('success') }}</p>
    </section>
@endif

@if ($errors->any())
    <section class="card" data-searchable>
        <x-ui.badge type="danger">ERROR</x-ui.badge>
        <ul class="list">
            @foreach ($errors->all() as $error)
                <li><span>{{ $error }}</span></li>
            @endforeach
        </ul>
    </section>
@endif

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
                                    <a href="{{ route('super-admin.users.edit', $userRow) }}" class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></a>
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
                <label>Class
                    <select name="class_name" required>
                        <option value="">Pilih class</option>
                        @foreach ($classTypeOptions as $classKey => $classLabel)
                            <option value="{{ $classKey }}" @selected(old('class_name') === $classKey)>{{ $classLabel }}</option>
                        @endforeach
                    </select>
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
                                    <a href="{{ route('super-admin.teachers.edit', $teacher) }}" class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></a>
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
                <label>Jadwal
                    <input type="text" name="schedule" value="{{ old('schedule') }}" placeholder="Contoh: Mon & Wed 16:00">
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
                                    <details class="action-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="module-form action-popover-form" method="POST" action="{{ route('super-admin.classes.update', $classItem) }}">
                                        @csrf
                                        @method('PUT')
                                        <label>Nama Kelas
                                            <input type="text" name="name" value="{{ $classItem->name }}" required>
                                        </label>
                                        <label>Deskripsi
                                            <textarea name="description" rows="2">{{ $classItem->description }}</textarea>
                                        </label>
                                        <label>Harga
                                            <input type="number" name="price" min="0" step="1000" value="{{ (int) ($classItem->price ?? 0) }}">
                                        </label>
                                        <label>Jadwal
                                            <input type="text" name="schedule" value="{{ $classItem->schedule }}">
                                        </label>
                                        <label>Guru
                                            <select name="teacher_id">
                                                <option value="">Tanpa guru</option>
                                                @foreach ($teachersForClassOptions as $teacherOption)
                                                    <option value="{{ $teacherOption->id }}" @selected((int) $classItem->teacher_id === (int) $teacherOption->id)>{{ $teacherOption->name }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                        <label>Status
                                            <select name="status" required>
                                                <option value="active" @selected($classItem->status === 'active')>Active</option>
                                                <option value="inactive" @selected($classItem->status === 'inactive')>Inactive</option>
                                            </select>
                                        </label>
                                        <button type="submit">Update</button>
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
        <h3>Daftar Student Approved</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Kelas</th>
                        <th>Status Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedRegistrationsForStudents as $registrationItem)
                        <tr>
                            <td>{{ $registrationItem->full_name }}</td>
                            <td>{{ $registrationItem->email }}</td>
                            <td>{{ $registrationItem->phone }}</td>
                            <td>{{ $registrationItem->class?->name ?? '-' }}</td>
                            <td>
                                <x-ui.badge type="success">APPROVED</x-ui.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada student approved. Approve data di menu Registrations untuk menampilkannya di sini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'registrations')
    <section class="card" data-searchable>
        <h3>Create Registration</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.registrations.store') }}">
            @csrf
            <label>Nama
                <input type="text" name="full_name" value="{{ old('full_name') }}" required>
            </label>
            <label>Umur
                <input type="number" name="age" min="4" max="80" value="{{ old('age') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>Telepon
                <input type="text" name="phone" value="{{ old('phone') }}" required>
            </label>
            <label>Kelas
                <select name="class_id">
                    <option value="">Pilih kelas</option>
                    @foreach($classesForManagement as $classItem)
                        <option value="{{ $classItem->id }}" @selected((string) old('class_id') === (string) $classItem->id)>{{ $classItem->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Preferensi Jadwal
                <input type="text" name="preferred_schedule" value="{{ old('preferred_schedule') }}" required>
            </label>
            <label>Status
                <select name="status" required>
                    <option value="pending" @selected(old('status', 'pending') === 'pending')>pending</option>
                    <option value="accepted" @selected(old('status') === 'accepted')>accepted</option>
                    <option value="rejected" @selected(old('status') === 'rejected')>rejected</option>
                </select>
            </label>
            <label>Catatan
                <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
            </label>
            <div class="form-actions">
                <button type="submit">Simpan Registration</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>

    <section class="card" data-searchable>
        <h3>Daftar Registration</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrationsForManagement as $registrationItem)
                        <tr>
                            <td colspan="5">
                                <form class="module-form" method="POST" action="{{ route('super-admin.registrations.update', $registrationItem) }}">
                                    @csrf
                                    @method('PUT')
                                    <label>Nama
                                        <input type="text" name="full_name" value="{{ $registrationItem->full_name }}" required>
                                    </label>
                                    <label>Umur
                                        <input type="number" name="age" min="4" max="80" value="{{ $registrationItem->age }}" required>
                                    </label>
                                    <label>Email
                                        <input type="email" name="email" value="{{ $registrationItem->email }}" required>
                                    </label>
                                    <label>Telepon
                                        <input type="text" name="phone" value="{{ $registrationItem->phone }}" required>
                                    </label>
                                    <label>Kelas
                                        <select name="class_id">
                                            <option value="">Pilih kelas</option>
                                            @foreach($classesForManagement as $classItem)
                                                <option value="{{ $classItem->id }}" @selected((int) $registrationItem->class_id === (int) $classItem->id)>{{ $classItem->name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label>Preferensi Jadwal
                                        <input type="text" name="preferred_schedule" value="{{ $registrationItem->preferred_schedule }}" required>
                                    </label>
                                    <label>Status
                                        <select name="status" required>
                                            <option value="pending" @selected($registrationItem->status === 'pending')>pending</option>
                                            <option value="accepted" @selected($registrationItem->status === 'accepted')>accepted</option>
                                            <option value="rejected" @selected($registrationItem->status === 'rejected')>rejected</option>
                                        </select>
                                    </label>
                                    <label>Catatan
                                        <textarea name="notes" rows="2">{{ $registrationItem->notes }}</textarea>
                                    </label>
                                    <div class="action-icons">
                                        <button type="submit">Update</button>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('super-admin.registrations.destroy', $registrationItem) }}" onsubmit="return confirm('Hapus registration ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No registrations yet. Website leads will appear here automatically.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
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

@if ($moduleKey === 'schedule')
    <section class="card" data-searchable>
        <h3>Tentukan Pengajar per Class</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.schedule.teacher') }}">
            @csrf
            <label>Class
                <select name="class_id" required>
                    <option value="">Pilih class</option>
                    @foreach($classesForSchedule as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Pengajar
                <select name="teacher_id" required>
                    <option value="">Pilih pengajar</option>
                    @foreach($teachersForClassOptions as $teacherOption)
                        <option value="{{ $teacherOption->id }}">{{ $teacherOption->name }}</option>
                    @endforeach
                </select>
            </label>
            <div class="form-actions">
                <button type="submit">Simpan Pengajar</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>

    <section class="card" data-searchable>
        <h3>Tentukan Siswa per Class</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.schedule.students') }}">
            @csrf
            <label>Class
                <select name="class_id" required>
                    <option value="">Pilih class</option>
                    @foreach($classesForSchedule as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Siswa (boleh lebih dari satu)
                <select name="student_ids[]" multiple required size="8">
                    @foreach($studentsForSchedule as $studentOption)
                        <option value="{{ $studentOption->id }}">{{ $studentOption->name }} ({{ $studentOption->email ?? '-' }})</option>
                    @endforeach
                </select>
            </label>
            <div class="form-actions">
                <button type="submit">Tambah Siswa ke Class</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>

    <section class="card" data-searchable>
        <h3>Ringkasan Schedule</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Pengajar</th>
                        <th>Status Jadwal</th>
                        <th>Jumlah Siswa</th>
                        <th>Daftar Siswa</th>
                        <th>Catatan Respon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classesForSchedule as $class)
                        <tr>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->teacher?->name ?? '-' }}</td>
                            <td>
                                @php
                                    $assignmentStatus = strtolower($class->assignment_status ?? 'pending');
                                    $assignmentBadge = $assignmentStatus === 'accepted' ? 'success' : ($assignmentStatus === 'rejected' ? 'danger' : 'warning');
                                @endphp
                                <x-ui.badge :type="$assignmentBadge">{{ strtoupper($assignmentStatus) }}</x-ui.badge>
                            </td>
                            <td>{{ $class->students->count() }}</td>
                            <td>{{ $class->students->pluck('name')->implode(', ') ?: '-' }}</td>
                            <td>{{ $class->assignment_note ?? '-' }}</td>
                            <td>
                                @if($class->teacher_id)
                                    <form method="POST" action="{{ route('super-admin.schedule.teacher.destroy', $class) }}" style="margin-bottom: 0.35rem;" onsubmit="return confirm('Lepas pengajar dari class ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Lepas Guru</button>
                                    </form>
                                @endif
                                @forelse($class->students as $studentInClass)
                                    <form method="POST" action="{{ route('super-admin.schedule.students.destroy', [$class, $studentInClass]) }}" style="margin-bottom: 0.35rem;" onsubmit="return confirm('Hapus siswa {{ $studentInClass->name }} dari class ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Hapus {{ $studentInClass->name }}</button>
                                    </form>
                                @empty
                                    -
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No schedule data yet. Assign class-teacher pairs to activate scheduling.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if (! in_array($moduleKey, ['users', 'roles', 'teachers', 'schedule', 'classes', 'students', 'registrations', 'blog', 'gallery', 'events', 'testimonials', 'settings', 'logs'], true))
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


