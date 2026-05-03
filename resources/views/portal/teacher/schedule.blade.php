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
        background: rgba(0,0,0,0.5);
        display: none; justify-content: center; align-items: center;
        z-index: 1000;
    }
    .modal-content {
        background: white; padding: 2rem; border-radius: 0.5rem;
        width: 100%; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .modal-header { font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; }
    .form-input { width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; }
    .location-btn {
        background: #10b981; color: white; border: none; padding: 0.5rem; width: 100%;
        border-radius: 0.25rem; cursor: pointer; margin-bottom: 0.5rem;
    }
    .location-btn:hover { background: #059669; }
    .status-group { display: flex; gap: 1rem; margin-top: 0.5rem; }
    .btn-submit { background: #0f172a; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; width: 100%; }
    .btn-submit:disabled { background: #94a3b8; cursor: not-allowed; }
    .btn-cancel { background: transparent; color: #64748b; padding: 0.5rem 1rem; border: none; cursor: pointer; width: 100%; margin-top: 0.5rem; }
    .location-status { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }
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
                    <th>Waktu</th>
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
                        <td>{{ $schedule->session_date->translatedFormat('l, d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}</td>
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
        <div class="modal-header">Mark Attendance</div>
        <form action="{{ route('teacher.schedule.attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="session_id" id="modal_session_id">
            <input type="hidden" name="latitude" id="modal_lat">
            <input type="hidden" name="longitude" id="modal_lng">

            <div class="form-group">
                <div style="font-size: 0.9rem; color: #475569; margin-bottom: 1rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.25rem;">
                    <strong>Student:</strong> <span id="modal_student_name"></span><br>
                    <strong>Class:</strong> <span id="modal_class_name"></span><br>
                    <strong>Time:</strong> <span id="modal_time"></span>
                </div>
            </div>

            <div class="form-group">
                <button type="button" class="location-btn" onclick="getLocation()" id="btn_get_location">
                    <i data-lucide="map-pin"></i> Ambil Lokasi Live
                </button>
                <div id="location_status" class="location-status">Lokasi belum diambil.</div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Kehadiran</label>
                <div class="status-group">
                    <label><input type="radio" name="status" value="present" required> Present</label>
                    <label><input type="radio" name="status" value="absent" required> Absent</label>
                    <label><input type="radio" name="status" value="reschedule" required> Reschedule</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Note (Optional)</label>
                <textarea name="note" class="form-input" rows="2" placeholder="Catatan tambahan..."></textarea>
            </div>

            <button type="submit" class="btn-submit" id="btn_submit_attendance" disabled>Save Attendance</button>
            <button type="button" class="btn-cancel" onclick="closeAttendanceModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openAttendanceModal(sessionId, studentName, className, time) {
        document.getElementById('modal_session_id').value = sessionId;
        document.getElementById('modal_student_name').textContent = studentName;
        document.getElementById('modal_class_name').textContent = className;
        document.getElementById('modal_time').textContent = time;
        
        // Reset location
        document.getElementById('modal_lat').value = '';
        document.getElementById('modal_lng').value = '';
        document.getElementById('location_status').textContent = 'Lokasi belum diambil.';
        document.getElementById('location_status').style.color = '#64748b';
        document.getElementById('btn_submit_attendance').disabled = true;

        document.getElementById('attendanceModal').style.display = 'flex';
    }

    function closeAttendanceModal() {
        document.getElementById('attendanceModal').style.display = 'none';
    }

    function getLocation() {
        const statusEl = document.getElementById('location_status');
        const latEl = document.getElementById('modal_lat');
        const lngEl = document.getElementById('modal_lng');
        const submitBtn = document.getElementById('btn_submit_attendance');
        const btnGet = document.getElementById('btn_get_location');

        if (!navigator.geolocation) {
            statusEl.textContent = 'Geolocation tidak didukung oleh browser ini.';
            statusEl.style.color = '#ef4444';
            return;
        }

        statusEl.textContent = 'Mencari lokasi...';
        btnGet.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                latEl.value = position.coords.latitude;
                lngEl.value = position.coords.longitude;
                statusEl.textContent = 'Lokasi berhasil didapatkan!';
                statusEl.style.color = '#10b981';
                submitBtn.disabled = false;
                btnGet.disabled = false;
            },
            (error) => {
                statusEl.textContent = 'Gagal mengambil lokasi: ' + error.message;
                statusEl.style.color = '#ef4444';
                submitBtn.disabled = true;
                btnGet.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    }
</script>
@endsection
