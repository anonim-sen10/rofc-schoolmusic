@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
        ['label' => 'Profile', 'url' => route('teacher.profile.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'My Schedule')
@section('page-title', 'My Schedule')
@section('content')

<section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 mt-2">
    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
        <div>
            <h3 class="text-base font-bold text-slate-900 leading-none">Jadwal Mengajar Saya</h3>
            <p class="text-[11px] font-medium text-slate-400 mt-1">Daftar seluruh sesi mengajar Anda.</p>
        </div>
        <i data-lucide="calendar-days" class="w-4 h-4 text-slate-300"></i>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jadwal</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kelas</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-bold border border-blue-100">
                                {{ $schedule->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-[10px] uppercase border border-slate-200">
                                    {{ substr($schedule->student->user->name ?? ($schedule->student->name ?? '-'), 0, 2) }}
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $schedule->student->user->name ?? ($schedule->student->name ?? '-') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs font-medium text-slate-500">{{ $schedule->musicClass->name ?? '-' }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                <span class="max-w-[150px] truncate">{{ $schedule->student->address ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $pendingRequest = $schedule->rescheduleRequests->where('status', 'pending')->first();
                            @endphp

                            @if($schedule->status === 'rescheduled')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-50 text-slate-600 text-[10px] font-bold border border-slate-200">RESCHEDULED</span>
                            @elseif($pendingRequest)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-100 uppercase tracking-tight">RESCHEDULE REQ</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold border border-green-100">ACTIVE</span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                @if($schedule->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-green-600"><i data-lucide="check-circle" class="w-4 h-4"></i> SELESAI</span>
                                @elseif($schedule->attendance)
                                    @php $status = strtolower($schedule->attendance->status); @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $status === 'present' ? 'bg-green-50 text-green-700 border-green-100' : ($status === 'absent' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-amber-50 text-amber-700 border-amber-100') }} text-[10px] font-bold border uppercase">
                                        {{ $status }}
                                    </span>
                                @elseif($schedule->session_date->isFuture())
                                    <span class="text-[10px] font-medium text-slate-400 italic">Upcoming</span>
                                @else
                                    <button type="button" 
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 text-white text-[10px] font-bold shadow-sm hover:bg-blue-700 transition-all active:scale-95"
                                        onclick="openAttendanceModal('{{ $schedule->id }}', '{{ $schedule->student->name }}', '{{ $schedule->musicClass->name }}', '{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}')">
                                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                        <span>ATTENDANCE</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                                    <i data-lucide="calendar-off" class="w-8 h-8"></i>
                                </div>
                                <h4 class="text-slate-900 font-bold text-sm">Belum ada jadwal</h4>
                                <p class="text-slate-400 text-xs">Belum ada jadwal yang di-booking untuk saat ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Premium Attendance Modal (TailwindCSS) --}}
<div id="attendanceModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 sm:p-6">
    {{-- Glassmorphism Overlay --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAttendanceModal()"></div>
    
    {{-- Modal Container --}}
    <div class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white shadow-2xl transition-all animate-in fade-in zoom-in duration-300">
        
        {{-- Header Section --}}
        <header class="relative border-b border-slate-100 bg-white px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i data-lucide="user-check" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Mark Attendance</h3>
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                            <i data-lucide="clock" class="h-3 w-3"></i>
                            <span id="realtime-clock">Loading...</span>
                            <span class="text-slate-300">•</span>
                            <span id="modal-current-date">{{ now()->format('D, d M Y') }}</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="closeAttendanceModal()" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-red-50 hover:text-red-500">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
        </header>

        <div class="max-h-[calc(100vh-12rem)] overflow-y-auto">
            <div class="p-6">
                {{-- Stepper Progress --}}
                <div class="mb-8 flex items-center justify-between px-2">
                    <div id="step-1-indicator" class="flex flex-col items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white ring-4 ring-blue-100">
                            <i data-lucide="camera" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Photo</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100 mx-2 mb-6" id="line-1"></div>
                    <div id="step-2-indicator" class="flex flex-col items-center gap-2 opacity-40">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                            <i data-lucide="map-pin" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">GPS Lock</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100 mx-2 mb-6" id="line-2"></div>
                    <div id="step-3-indicator" class="flex flex-col items-center gap-2 opacity-40">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</span>
                    </div>
                </div>

                <form action="{{ route('teacher.schedule.attendance.store') }}" method="POST" id="attendance-form">
                    @csrf
                    <input type="hidden" name="session_id" id="modal_session_id">
                    <input type="hidden" name="latitude" id="modal_lat">
                    <input type="hidden" name="longitude" id="modal_lng">
                    <input type="hidden" name="attendance_image" id="modal_image">

                    {{-- Session Details Pill --}}
                    <div class="mb-6 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Student</p>
                                <p id="modal_student_name" class="font-bold text-slate-800"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Class</p>
                                <p id="modal_class_name" class="font-bold text-slate-800"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Camera Section --}}
                    <div class="group relative mb-8 overflow-hidden rounded-3xl border-4 border-slate-100 bg-slate-900 shadow-inner">
                        <div class="aspect-[4/3] relative w-full overflow-hidden">
                            <video id="webcam" autoplay playsinline class="h-full w-full object-cover"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <img id="captured_image" class="hidden h-full w-full object-cover">
                            
                            {{-- Scanner Effect --}}
                            <div id="scanner-line" class="absolute left-0 top-0 hidden h-1 w-full bg-gradient-to-r from-transparent via-blue-400 to-transparent shadow-[0_0_15px_rgba(96,165,250,0.8)] animate-scan"></div>
                            
                            {{-- Camera Overlay --}}
                            <div id="camera-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/40 backdrop-blur-[2px]">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md animate-pulse">
                                    <i data-lucide="camera" class="h-8 w-8"></i>
                                </div>
                                <p class="mt-4 text-sm font-medium text-white">Camera Ready</p>
                            </div>

                            {{-- Badges --}}
                            <div class="absolute left-4 top-4 flex flex-col gap-2">
                                <div id="badge-gps" class="flex items-center gap-1.5 rounded-full bg-slate-900/60 px-2.5 py-1 text-[10px] font-bold text-white backdrop-blur-md border border-white/10">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-400" id="gps-dot"></div>
                                    GPS STANDBY
                                </div>
                            </div>
                        </div>

                        {{-- Camera Action Bar --}}
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center px-4">
                            <button type="button" onclick="startCamera()" id="btn_start_camera" class="flex items-center gap-2 rounded-2xl bg-white px-6 py-3 text-sm font-bold text-slate-900 shadow-xl transition-all hover:scale-105 active:scale-95">
                                <i data-lucide="play" class="h-4 w-4"></i> START CAMERA
                            </button>
                            <button type="button" onclick="capturePhoto()" id="btn_capture" class="hidden h-14 w-14 items-center justify-center rounded-full bg-white text-blue-600 shadow-xl transition-all hover:scale-110 active:scale-90">
                                <div class="h-10 w-10 rounded-full border-4 border-blue-100 flex items-center justify-center">
                                    <div class="h-6 w-6 rounded-full bg-blue-600"></div>
                                </div>
                            </button>
                            <button type="button" onclick="retakePhoto()" id="btn_retake" class="hidden flex items-center gap-2 rounded-2xl bg-white/20 px-6 py-3 text-sm font-bold text-white shadow-xl backdrop-blur-md transition-all hover:bg-white/30">
                                <i data-lucide="refresh-cw" class="h-4 w-4"></i> RETAKE
                            </button>
                        </div>
                    </div>

                    {{-- Attendance Status Cards --}}
                    <div class="mb-8">
                        <label class="mb-3 block text-xs font-bold uppercase tracking-widest text-slate-400">Attendance Status</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="present" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 transition-all group-hover:bg-slate-100 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-600 peer-checked:shadow-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 peer-checked:bg-green-100 peer-checked:text-green-600 shadow-sm">
                                        <i data-lucide="check" class="h-6 w-6"></i>
                                    </div>
                                    <span class="text-xs font-bold">PRESENT</span>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="absent" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 transition-all group-hover:bg-slate-100 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 peer-checked:shadow-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 peer-checked:bg-red-100 peer-checked:text-red-600 shadow-sm">
                                        <i data-lucide="x" class="h-6 w-6"></i>
                                    </div>
                                    <span class="text-xs font-bold">ABSENT</span>
                                </div>
                            </label>

                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="reschedule" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 transition-all group-hover:bg-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-600 peer-checked:shadow-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 peer-checked:bg-amber-100 peer-checked:text-amber-600 shadow-sm">
                                        <i data-lucide="calendar-range" class="h-6 w-6"></i>
                                    </div>
                                    <span class="text-xs font-bold">RESCHEDULE</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-4">
                        <label class="mb-3 block text-xs font-bold uppercase tracking-widest text-slate-400">Notes (Optional)</label>
                        <textarea name="note" rows="3" class="w-full rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 text-sm font-medium text-slate-900 placeholder-slate-400 transition-all focus:border-blue-500 focus:bg-white focus:outline-none" placeholder="Add any session notes here..."></textarea>
                    </div>
                </form>
            </div>
        </div>

        {{-- Footer Section --}}
        <footer class="border-t border-slate-100 bg-slate-50/50 px-6 py-5 backdrop-blur-md">
            <div class="flex gap-3">
                <button type="button" onclick="closeAttendanceModal()" class="flex-1 rounded-2xl border border-slate-200 bg-white py-3.5 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 active:scale-95">
                    CANCEL
                </button>
                <button type="submit" form="attendance-form" id="btn_submit_attendance" disabled class="flex-[2] rounded-2xl bg-blue-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-blue-700 disabled:bg-slate-200 disabled:text-slate-500 disabled:shadow-none active:scale-95">
                    SUBMIT ATTENDANCE
                </button>
            </div>
        </footer>
    </div>
</div>

<style>
    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
    .animate-scan {
        animation: scan 2.5s linear infinite;
    }
    #attendanceModal.active {
        display: flex;
    }
</style>

<script>
    let stream = null;

    // Realtime Clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clockEl = document.getElementById('realtime-clock');
        if (clockEl) clockEl.textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function openAttendanceModal(sessionId, studentName, className, time) {
        const modal = document.getElementById('attendanceModal');
        const sessIdInput = document.getElementById('modal_session_id');
        const studNameEl = document.getElementById('modal_student_name');
        const classNameEl = document.getElementById('modal_class_name');

        if (sessIdInput) sessIdInput.value = sessionId;
        if (studNameEl) studNameEl.textContent = studentName;
        if (classNameEl) classNameEl.textContent = className;
        
        // Reset state
        retakePhoto();
        modal.classList.add('active');
        if (window.lucide) window.lucide.createIcons();
    }

    function closeAttendanceModal() {
        stopCamera();
        document.getElementById('attendanceModal').classList.remove('active');
    }

    async function startCamera() {
        const video = document.getElementById('webcam');
        const overlay = document.getElementById('camera-overlay');
        const btnStart = document.getElementById('btn_start_camera');
        const btnCapture = document.getElementById('btn_capture');
        const scanner = document.getElementById('scanner-line');

        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment' },
                audio: false 
            });
            video.srcObject = stream;
            overlay.classList.add('hidden');
            if (scanner) scanner.classList.remove('hidden');
            btnStart.classList.add('hidden');
            btnCapture.classList.remove('hidden');
            
            // Update Stepper
            const step1 = document.getElementById('step-1-indicator');
            if (step1) step1.classList.remove('opacity-40');
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
        const scanner = document.getElementById('scanner-line');
        const submitBtn = document.getElementById('btn_submit_attendance');
        const badgeGps = document.getElementById('badge-gps');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // GPS Lock logic
        if (badgeGps) {
            badgeGps.innerHTML = '<i data-lucide="loader-2" class="h-3 w-3 animate-spin"></i> LOCKING GPS...';
            if (window.lucide) window.lucide.createIcons();
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const latInput = document.getElementById('modal_lat');
                const lngInput = document.getElementById('modal_lng');
                const imgInput = document.getElementById('modal_image');

                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;

                // DRAW OVERLAY ON PHOTO
                const boxHeight = canvas.height * 0.22;
                ctx.fillStyle = 'rgba(15, 23, 42, 0.7)';
                ctx.fillRect(0, canvas.height - boxHeight, canvas.width, boxHeight);

                ctx.fillStyle = 'white';
                const fontSize = Math.floor(canvas.height * 0.035);
                ctx.font = `bold ${fontSize}px Inter, sans-serif`;
                
                const padding = 24;
                const nowText = new Date().toLocaleString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                ctx.fillText(`ROFC ATTENDANCE SYSTEM`, padding, canvas.height - boxHeight + padding + fontSize);
                ctx.font = `${fontSize * 0.8}px Inter, sans-serif`;
                ctx.fillStyle = 'rgba(255,255,255,0.7)';
                ctx.fillText(`Coord: ${lat.toFixed(6)}, ${lng.toFixed(6)}`, padding, canvas.height - boxHeight + padding + (fontSize * 2.5));
                ctx.fillText(nowText, padding, canvas.height - boxHeight + padding + (fontSize * 3.7));

                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                if (imgInput) imgInput.value = dataUrl;
                img.src = dataUrl;
                
                video.classList.add('hidden');
                img.classList.remove('hidden');
                btnCapture.classList.add('hidden');
                btnRetake.classList.remove('hidden');
                if (scanner) scanner.classList.add('hidden');
                
                // Update Badge
                if (badgeGps) {
                    badgeGps.innerHTML = '<div class="h-1.5 w-1.5 rounded-full bg-green-500"></div> GPS LOCKED';
                    badgeGps.classList.remove('bg-slate-900/60');
                    badgeGps.classList.add('bg-green-600/80');
                    if (window.lucide) window.lucide.createIcons();
                }

                // Update Stepper
                const step2 = document.getElementById('step-2-indicator');
                const step3 = document.getElementById('step-3-indicator');
                const line1 = document.getElementById('line-1');
                const line2 = document.getElementById('line-2');

                if (step2) step2.classList.remove('opacity-40');
                if (step3) step3.classList.remove('opacity-40');
                if (line1) line1.classList.add('bg-blue-600');
                if (line2) line2.classList.add('bg-blue-600');

                if (submitBtn) submitBtn.disabled = false;
                stopCamera();
            },
            (err) => {
                alert('GPS Error: ' + err.message);
                if (badgeGps) badgeGps.innerHTML = '<div class="h-1.5 w-1.5 rounded-full bg-red-500"></div> GPS FAILED';
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
        const overlay = document.getElementById('camera-overlay');
        const badgeGps = document.getElementById('badge-gps');
        const submitBtn = document.getElementById('btn_submit_attendance');

        if (video) video.classList.remove('hidden');
        if (img) {
            img.classList.add('hidden');
            img.src = '';
        }
        const imgInput = document.getElementById('modal_image');
        if (imgInput) imgInput.value = '';
        
        if (btnStart) btnStart.classList.remove('hidden');
        if (btnCapture) btnCapture.classList.add('hidden');
        if (btnRetake) btnRetake.classList.add('hidden');
        if (overlay) overlay.classList.remove('hidden');
        
        if (badgeGps) {
            badgeGps.innerHTML = '<div class="h-1.5 w-1.5 rounded-full bg-slate-400"></div> GPS STANDBY';
            badgeGps.classList.add('bg-slate-900/60');
            badgeGps.classList.remove('bg-green-600/80');
        }
        
        // Reset Stepper
        const step2 = document.getElementById('step-2-indicator');
        const step3 = document.getElementById('step-3-indicator');
        const line1 = document.getElementById('line-1');
        const line2 = document.getElementById('line-2');

        if (step2) step2.classList.add('opacity-40');
        if (step3) step3.classList.add('opacity-40');
        if (line1) line1.classList.remove('bg-blue-600');
        if (line2) line2.classList.remove('bg-blue-600');

        if (submitBtn) submitBtn.disabled = true;
        if (window.lucide) window.lucide.createIcons();
    }
</script>
@endsection
