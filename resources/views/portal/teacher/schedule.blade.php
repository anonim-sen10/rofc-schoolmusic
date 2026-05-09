@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Attendance', 'url' => route('teacher.attendance.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'My Schedule')
@section('page-title', 'My Schedule')
@section('content')

<style>
    .badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 0.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-present { background-color: #dcfce7; color: #166534; }
    .badge-absent { background-color: #fee2e2; color: #991b1b; }
    .badge-reschedule { background-color: #ffedd5; color: #9a3412; }
    
    .btn-action {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 0.3rem;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .btn-action:hover { background: #2563eb; }

    /* Modal Styles */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        display: none; justify-content: center; align-items: center;
        z-index: 1000; padding: 1rem;
    }
    .modal-content {
        background: white; border-radius: 1rem;
        width: 100%; max-width: 450px; 
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        position: relative;
    }
    .modal-body { padding: 1.5rem; }
    .modal-header { 
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        font-size: 1.1rem; font-weight: 700; color: #1e293b;
        display: flex; justify-content: space-between; align-items: center;
        position: sticky; top: 0; background: white; z-index: 10;
    }
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em; }
    .form-input { width: 100%; padding: 0.625rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; }
    .form-input:focus { outline: none; border-color: #3b82f6; ring: 2px #3b82f6; }
    
    .camera-box {
        position: relative; width: 100%; border-radius: 0.75rem; 
        overflow: hidden; background: #0f172a; 
        aspect-ratio: 4/3; /* Changed to more horizontal */
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #e2e8f0;
    }
    
    .location-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        background: #3b82f6; color: white; border: none; padding: 0.625rem; width: 100%;
        border-radius: 0.5rem; cursor: pointer; font-weight: 600; font-size: 0.9rem;
        transition: all 0.2s;
    }
    .location-btn:hover { background: #2563eb; transform: translateY(-1px); }
    .status-group { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 0.5rem; }
    .status-item { 
        display: flex; align-items: center; gap: 0.4rem; cursor: pointer;
        padding: 0.4rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;
        font-size: 0.85rem; font-weight: 500;
    }
    .status-item input { margin: 0; }
    
    .btn-submit { 
        background: #0f172a; color: white; padding: 0.75rem; border: none; 
        border-radius: 0.5rem; cursor: pointer; width: 100%; font-weight: 700;
        margin-top: 0.5rem; transition: all 0.2s;
    }
    .btn-submit:hover:not(:disabled) { background: #1e293b; transform: translateY(-1px); }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    
    .btn-cancel { 
        background: transparent; color: #94a3b8; padding: 0.5rem; border: none; 
        cursor: pointer; width: 100%; font-size: 0.85rem; font-weight: 600;
    }
    .location-status { font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem; }
</style>

<section class="card">
    <h3>Jadwal Mengajar Saya</h3>
    
    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Jadwal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td><strong>{{ $schedule->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}</strong></td>
                        <td>{{ $schedule->student->user->name ?? ($schedule->student->name ?? '-') }}</td>
                        <td>{{ $schedule->musicClass->name ?? '-' }}</td>
                        <td>{{ $schedule->student->address ?? '-' }}</td>
                        <td>
                            @php
                                $pendingRequest = $schedule->rescheduleRequests->where('status', 'pending')->first();
                            @endphp

                            @if($schedule->status === 'rescheduled')
                                <span class="badge" style="background-color: #f3f4f6; color: #4b5563;">Rescheduled</span>
                            @elseif($pendingRequest)
                                <span class="badge" style="background-color: #fef9c3; color: #a16207;">Reschedule Requested</span>
                            @else
                                <span class="badge" style="background-color: #dcfce7; color: #15803d;">Active</span>
                            @endif
                        </td>
                        <td>
                            @if($schedule->status === 'rescheduled')
                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Locked (Rescheduled)</span>
                            @elseif($schedule->status === 'completed')
                                <span style="font-size: 0.8rem; color: #10b981; font-weight: 600;">Selesai</span>
                            @elseif($schedule->attendance)
                                @php $status = strtolower($schedule->attendance->status); @endphp
                                <span class="badge badge-{{ $status }}">
                                    @if($status === 'present') ✔
                                    @elseif($status === 'absent') ✖
                                    @elseif($status === 'reschedule') ↻
                                    @endif
                                    {{ $status }}
                                </span>
                            @elseif($schedule->session_date->isFuture())
                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Upcoming</span>
                            @else
                                <button type="button" class="btn-action" 
                                    onclick="openAttendanceModal('{{ $schedule->id }}', '{{ $schedule->student->name }}', '{{ $schedule->musicClass->name }}', '{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}')">
                                    Mark Attendance
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada jadwal yang di-booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Attendance Modal -->
<div id="attendanceModal" class="modal-overlay">
    <div class="modal-content">
        <header class="modal-header">
            <span>Mark Attendance</span>
            <button type="button" onclick="closeAttendanceModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8;"><i data-lucide="x"></i></button>
        </header>
        
        <div class="modal-body">
            <form action="{{ route('teacher.schedule.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="session_id" id="modal_session_id">
                <input type="hidden" name="latitude" id="modal_lat">
                <input type="hidden" name="longitude" id="modal_lng">
                <input type="hidden" name="attendance_image" id="modal_image">

                <div class="form-group">
                    <div style="font-size: 0.85rem; color: #475569; padding: 1rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #f1f5f9;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
                            <span style="color:#64748b;">Siswa:</span>
                            <span id="modal_student_name" style="font-weight:700;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
                            <span style="color:#64748b;">Kelas:</span>
                            <span id="modal_class_name" style="font-weight:600;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#64748b;">Waktu:</span>
                            <span id="modal_time" style="font-weight:600;"></span>
                        </div>
                    </div>
                </div>

                <!-- Camera Section -->
                <div class="form-group">
                    <label class="form-label">Bukti Foto & Lokasi (GPS Camera)</label>
                    <div class="camera-box" id="camera_container">
                        <video id="webcam" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <img id="captured_image" style="display:none; width: 100%; height: 100%; object-fit: cover;">
                        
                        <div id="camera_placeholder" style="color: #94a3b8; text-align: center; padding: 1rem; display: flex; flex-direction: column; align-items: center;">
                            <i data-lucide="camera" style="width: 40px; height: 40px; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                            <p style="font-size:0.8rem; font-weight:500;">Kamera Belum Aktif</p>
                        </div>
                    </div>
                    
                    <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                        <button type="button" class="location-btn" style="background: #3b82f6;" onclick="startCamera()" id="btn_start_camera">
                            <i data-lucide="camera"></i> Buka Kamera
                        </button>
                        <button type="button" class="location-btn" style="display: none;" onclick="capturePhoto()" id="btn_capture">
                            <i data-lucide="aperture"></i> Ambil Foto
                        </button>
                        <button type="button" class="location-btn" style="background: #f1f5f9; color: #475569; display: none;" onclick="retakePhoto()" id="btn_retake">
                            <i data-lucide="refresh-cw"></i> Ulangi
                        </button>
                    </div>
                    <div id="location_status" class="location-status">
                        <i data-lucide="info" style="width:14px; height:14px;"></i>
                        <span>Selesaikan foto untuk mengunci lokasi.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Kehadiran</label>
                    <div class="status-group">
                        <label class="status-item">
                            <input type="radio" name="status" value="present" required> Present
                        </label>
                        <label class="status-item">
                            <input type="radio" name="status" value="absent" required> Absent
                        </label>
                        <label class="status-item">
                            <input type="radio" name="status" value="reschedule" required> Reschedule
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea name="note" class="form-input" rows="2" placeholder="Tulis catatan jika ada..."></textarea>
                </div>

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn-submit" id="btn_submit_attendance" disabled>SIMPAN ABSENSI</button>
                    <button type="button" class="btn-cancel" onclick="closeAttendanceModal()">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let stream = null;

    function openAttendanceModal(sessionId, studentName, className, time) {
        document.getElementById('modal_session_id').value = sessionId;
        document.getElementById('modal_student_name').textContent = studentName;
        document.getElementById('modal_class_name').textContent = className;
        document.getElementById('modal_time').textContent = time;
        
        // Reset state
        retakePhoto();
        document.getElementById('attendanceModal').style.display = 'flex';
    }

    function closeAttendanceModal() {
        stopCamera();
        document.getElementById('attendanceModal').style.display = 'none';
    }

    async function startCamera() {
        const video = document.getElementById('webcam');
        const placeholder = document.getElementById('camera_placeholder');
        const btnStart = document.getElementById('btn_start_camera');
        const btnCapture = document.getElementById('btn_capture');

        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment' }, // Prioritize back camera
                audio: false 
            });
            video.srcObject = stream;
            placeholder.style.display = 'none';
            btnStart.style.display = 'none';
            btnCapture.style.display = 'block';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    function capturePhoto() {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const img = document.getElementById('captured_image');
        const btnCapture = document.getElementById('btn_capture');
        const btnRetake = document.getElementById('btn_retake');
        const statusEl = document.getElementById('location_status');
        const submitBtn = document.getElementById('btn_submit_attendance');

        // Set canvas size to match video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        
        // Draw video frame to canvas
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Get GPS and overlay text
        statusEl.textContent = 'Mengunci lokasi GPS...';
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('modal_lat').value = lat;
                document.getElementById('modal_lng').value = lng;

                // DRAW OVERLAY
                // Black box at bottom
                const boxHeight = canvas.height * 0.2;
                ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
                ctx.fillRect(0, canvas.height - boxHeight, canvas.width, boxHeight);

                // Text styling
                ctx.fillStyle = 'white';
                const fontSize = Math.floor(canvas.height * 0.035);
                ctx.font = `bold ${fontSize}px sans-serif`;
                
                const padding = 20;
                const now = new Date().toLocaleString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });

                // Write text
                ctx.fillText(`Lokasi Absensi ROFC`, padding, canvas.height - boxHeight + padding + fontSize);
                ctx.font = `${fontSize * 0.8}px sans-serif`;
                ctx.fillText(`Lat: ${lat.toFixed(6)}`, padding, canvas.height - boxHeight + padding + (fontSize * 2.5));
                ctx.fillText(`Long: ${lng.toFixed(6)}`, padding, canvas.height - boxHeight + padding + (fontSize * 3.7));
                ctx.fillText(now, padding, canvas.height - boxHeight + padding + (fontSize * 4.9));

                // Set as final image
                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                document.getElementById('modal_image').value = dataUrl;
                img.src = dataUrl;
                
                // Toggle UI
                video.style.display = 'none';
                img.style.display = 'block';
                btnCapture.style.display = 'none';
                btnRetake.style.display = 'block';
                statusEl.textContent = 'Lokasi terkunci di foto.';
                statusEl.style.color = '#10b981';
                submitBtn.disabled = false;
                
                stopCamera();
            },
            (err) => {
                alert('Gagal mendapatkan GPS. Mohon aktifkan GPS untuk absen: ' + err.message);
                statusEl.textContent = 'Gagal mengambil GPS.';
                statusEl.style.color = '#ef4444';
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    function retakePhoto() {
        const video = document.getElementById('webcam');
        const img = document.getElementById('captured_image');
        const btnStart = document.getElementById('btn_start_camera');
        const btnCapture = document.getElementById('btn_capture');
        const btnRetake = document.getElementById('btn_retake');
        const placeholder = document.getElementById('camera_placeholder');
        const statusEl = document.getElementById('location_status');
        const submitBtn = document.getElementById('btn_submit_attendance');

        video.style.display = 'block';
        img.style.display = 'none';
        img.src = '';
        document.getElementById('modal_image').value = '';
        document.getElementById('modal_lat').value = '';
        document.getElementById('modal_lng').value = '';
        
        btnStart.style.display = 'block';
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'none';
        placeholder.style.display = 'flex';
        statusEl.textContent = 'GPS belum dikunci. Selesaikan foto untuk mengunci lokasi.';
        statusEl.style.color = '#64748b';
        submitBtn.disabled = true;
    }
</script>
@endsection
