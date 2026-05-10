@section('page-subtitle', 'Lakukan absensi harian dan catat kehadiran siswa dengan bukti foto & lokasi.')
@section('content')
<div class="split-grid-sa">
    <section class="card premium-form-card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">Absen Guru</h3>
                <p class="ui-card-subtitle">Self-attendance dengan GPS & Foto</p>
            </div>
            <span class="ui-badge {{ $hasTeacherAttendanceToday ? 'ui-badge-success' : 'ui-badge-warning' }}">
                {{ $hasTeacherAttendanceToday ? 'SUDAH ABSEN' : 'BELUM ABSEN' }}
            </span>
        </div>

        <form class="module-form" method="POST" action="{{ route('teacher.attendance.teacher.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="module-form-grid">
                <label>Nama Guru
                    <input type="text" value="{{ $teacher->name }}" readonly class="bg-muted">
                </label>
                <label>Tanggal
                    <input type="date" name="attendance_date" value="{{ now()->format('Y-m-d') }}" required>
                </label>
            </div>

            <div class="camera-container" id="camera-section">
                <div class="camera-preview-wrapper">
                    <video id="video" autoplay playsinline class="camera-preview"></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <img id="photo-preview" class="photo-preview" style="display:none;">
                    <div class="camera-overlay">
                        <div class="camera-timestamp" id="cam-time">{{ now()->format('d/m/Y H:i:s') }}</div>
                        <div class="camera-location" id="cam-loc">Waiting for GPS...</div>
                    </div>
                </div>
                <div class="camera-controls">
                    <button type="button" id="btn-start-cam" class="btn-secondary"><i data-lucide="camera"></i> Aktifkan Kamera</button>
                    <button type="button" id="btn-capture" class="btn-primary" style="display:none;"><i data-lucide="aperture"></i> Ambil Foto Proof</button>
                    <button type="button" id="btn-retake" class="btn-danger" style="display:none;"><i data-lucide="refresh-cw"></i> Ulangi</button>
                </div>
                <input type="hidden" name="image_proof" id="image-proof-data">
            </div>

            <div class="location-box mt-4">
                <label>Live Location</label>
                <div class="input-group">
                    <input type="text" id="teacher-location-text" name="location_text" placeholder="Klik tombol untuk ambil lokasi" readonly required>
                    <button type="button" id="btn-get-location" class="btn-icon"><i data-lucide="map-pin"></i></button>
                </div>
                <input type="hidden" id="teacher-latitude" name="latitude">
                <input type="hidden" id="teacher-longitude" name="longitude">
            </div>

            <label class="mt-4">Status
                <select name="status" class="premium-select">
                    <option value="present" @selected(old('status') === 'present')>Hadir (Present)</option>
                    <option value="absent" @selected(old('status') === 'absent')>Izin / Sakit (Absent)</option>
                    <option value="late" @selected(old('status') === 'late')>Terlambat (Late)</option>
                </select>
            </label>

            <label>Note / Keterangan
                <textarea name="note" rows="2" placeholder="Catatan tambahan..."></textarea>
            </label>

            <button type="submit" class="btn-primary w-full mt-4" id="btn-submit-attendance">
                <i data-lucide="check-circle"></i> Simpan Absen Guru
            </button>
        </form>
    </section>

    <section class="card">
        <div class="ui-card-header">
            <div>
                <h3 class="ui-card-title">Absen Siswa</h3>
                <p class="ui-card-subtitle">Catat kehadiran siswa di kelas Anda</p>
            </div>
        </div>

        @if($hasTeacherAttendanceToday)
            @if($hasAssignedClasses)
                <form class="module-form" method="POST" action="{{ route('teacher.attendance.store') }}">
                    @csrf
                    <label>Pilih Kelas
                        <select name="class_id" id="class-id-select" required class="premium-select">
                            @foreach($classOptions as $musicClass)
                                <option value="{{ $musicClass->id }}" @selected(old('class_id') == $musicClass->id)>{{ $musicClass->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label>Nama Siswa
                        <select name="student_id" id="student-id-select" required class="premium-select">
                            <option value="">Pilih siswa</option>
                        </select>
                    </label>

                    <div class="module-form-grid">
                        <label>Tanggal
                            <input type="date" name="attendance_date" value="{{ now()->format('Y-m-d') }}" required>
                        </label>
                        <label>Status
                            <select name="status" class="premium-select">
                                <option value="present">Hadir</option>
                                <option value="absent">Absen</option>
                                <option value="late">Terlambat</option>
                            </select>
                        </label>
                    </div>

                    <label>Note
                        <textarea name="note" rows="2" placeholder="Catatan progress singkat..."></textarea>
                    </label>

                    <button type="submit" class="btn-primary w-full mt-4">
                        <i data-lucide="users"></i> Simpan Absen Siswa
                    </button>
                </form>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i data-lucide="book-x"></i></div>
                    <h4>Belum Ada Kelas</h4>
                    <p>Anda belum di-assign ke kelas manapun. Hubungi Admin.</p>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i data-lucide="lock"></i></div>
                <h4>Absen Guru Belum Diisi</h4>
                <p>Silakan isi absen guru terlebih dahulu sebelum mencatat kehadiran siswa.</p>
            </div>
        @endif
    </section>
</div>

<section class="card mt-6">
    <div class="ui-card-header">
        <h3 class="ui-card-title">Riwayat Absensi Terbaru</h3>
    </div>
    
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="tab-teacher">Absen Guru</button>
            <button class="tab-btn" data-tab="tab-student">Absen Siswa</button>
        </div>
        
        <div id="tab-teacher" class="tab-content active">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teacherRecords as $row)
                            <tr>
                                <td><strong>{{ optional($row->attendance_date)->translatedFormat('l, d M Y') }}</strong></td>
                                <td>
                                    <span class="ui-badge {{ $row->status === 'present' ? 'ui-badge-success' : 'ui-badge-danger' }}">
                                        {{ strtoupper($row->status) }}
                                    </span>
                                </td>
                                <td><small class="muted">{{ $row->location_text ?? '-' }}</small></td>
                                <td>
                                    @if($row->image_path)
                                        <button class="btn-icon" title="Lihat Bukti Foto"><i data-lucide="image"></i></button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-student" class="tab-content">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Siswa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $row)
                            <tr>
                                <td>{{ optional($row->attendance_date)->format('d/m/Y') }}</td>
                                <td>{{ $row->class?->name }}</td>
                                <td><strong>{{ $row->student?->name }}</strong></td>
                                <td>
                                    <span class="ui-badge {{ $row->status === 'present' ? 'ui-badge-success' : 'ui-badge-neutral' }}">
                                        {{ strtoupper($row->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Tab Logic
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // Student Selection Logic
    const classStudents = @json($classStudents);
    const classSelect = document.getElementById('class-id-select');
    const studentSelect = document.getElementById('student-id-select');
    const oldStudentId = @json(old('student_id'));

    const renderStudentOptions = (classId) => {
        if (!studentSelect) return;
        const students = classStudents[classId] ?? [];
        studentSelect.innerHTML = '<option value="">Pilih siswa</option>';
        students.forEach((student) => {
            const option = document.createElement('option');
            option.value = student.id;
            option.textContent = student.name;
            if (oldStudentId && Number(oldStudentId) === Number(student.id)) option.selected = true;
            studentSelect.appendChild(option);
        });
    };

    if (classSelect) {
        renderStudentOptions(classSelect.value);
        classSelect.addEventListener('change', () => renderStudentOptions(classSelect.value));
    }

    // Camera Logic
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photo-preview');
    const imageInput = document.getElementById('image-proof-data');
    const btnStart = document.getElementById('btn-start-cam');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const camLoc = document.getElementById('cam-loc');

    let stream = null;

    btnStart.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            video.srcObject = stream;
            btnStart.style.display = 'none';
            btnCapture.style.display = 'inline-flex';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    });

    btnCapture.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Add Watermark to Canvas
        context.font = "20px Arial";
        context.fillStyle = "white";
        context.shadowBlur = 4;
        context.shadowColor = "black";
        context.fillText(document.getElementById('cam-time').innerText, 20, canvas.height - 50);
        context.fillText(camLoc.innerText, 20, canvas.height - 25);

        const data = canvas.toDataURL('image/jpeg', 0.8);
        imageInput.value = data;
        
        photoPreview.src = data;
        photoPreview.style.display = 'block';
        video.style.display = 'none';
        
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'inline-flex';

        if(stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });

    btnRetake.addEventListener('click', async () => {
        photoPreview.style.display = 'none';
        video.style.display = 'block';
        imageInput.value = '';
        btnStart.click();
        btnRetake.style.display = 'none';
    });

    // Location Logic
    const btnLocation = document.getElementById('btn-get-location');
    const textInput = document.getElementById('teacher-location-text');
    const latInput = document.getElementById('teacher-latitude');
    const lngInput = document.getElementById('teacher-longitude');

    const getLocation = () => {
        if (!navigator.geolocation) {
            textInput.value = 'Browser tidak mendukung GPS';
            return;
        }

        textInput.value = 'Mengambil lokasi...';
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);
                latInput.value = lat;
                lngInput.value = lng;
                textInput.value = `${lat}, ${lng}`;
                camLoc.innerText = `GPS: ${lat}, ${lng}`;
            },
            () => {
                textInput.value = 'Gagal mengambil lokasi';
                camLoc.innerText = 'GPS Gagal';
            }
        );
    };

    btnLocation.addEventListener('click', getLocation);
    getLocation(); // Auto-fetch on load
});
</script>

<style>
    .camera-preview-wrapper { position: relative; width: 100%; aspect-ratio: 4/3; background: #000; border-radius: 12px; overflow: hidden; margin-bottom: 1rem; border: 3px solid var(--border); }
    .camera-preview, .photo-preview { width: 100%; height: 100%; object-fit: cover; }
    .camera-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 1rem; background: linear-gradient(transparent, rgba(0,0,0,0.7)); color: #fff; font-size: 0.75rem; pointer-events: none; }
    .camera-timestamp { font-weight: 700; }
    .camera-controls { display: flex; gap: 0.5rem; justify-content: center; }
    .input-group { display: flex; gap: 0.5rem; }
    .input-group input { flex: 1; }
    .tabs-header { display: flex; gap: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 1.5rem; }
    .tab-btn { padding: 0.75rem 1rem; border: none; background: none; color: var(--muted); font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; transition: 0.2s; }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .bg-muted { background-color: var(--card-bg-alt) !important; cursor: not-allowed; }
</style>
@endsection

@endsection